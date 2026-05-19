<?php

namespace App\Http\Controllers;

use App\Models\GrubkasActivityLog;
use App\Models\grubkas;
use App\Models\Datasikadmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentVerificationController extends Controller
{
    /**
     * Apply incoming payment to member debt and saldo.
     */
    private function applyPaymentToMemberBalance(?string $userNim, int $paidAmount, string $orderId): void
    {
        if (!$userNim || $paidAmount <= 0) {
            return;
        }

        $grubkas = grubkas::where('user_nim', $userNim)
            ->latest('created_at')
            ->latest('id')
            ->first();

        if (!$grubkas) {
            return;
        }

        $currentDebt = max(0, (int) $grubkas->Nominal);
        $currentPositiveBalance = max(0, (int) $grubkas->Saldo_Lebih);

        $remainingDebt = max(0, $currentDebt - $paidAmount);
        $overPayment = max(0, $paidAmount - $currentDebt);
        $newPositiveBalance = $currentPositiveBalance + $overPayment;
        $isPaidOff = $remainingDebt === 0;

        $description = $grubkas->Keterangan;
        if ($isPaidOff && $overPayment > 0) {
            $description = 'Pembayaran lebih Rp ' . number_format($overPayment, 0, ',', '.') . ' menjadi saldo positif';
        } elseif (!$isPaidOff) {
            $description = 'Sisa utang Rp ' . number_format($remainingDebt, 0, ',', '.');
        }

        $grubkas->Nominal = $remainingDebt;
        $grubkas->Saldo_Lebih = $newPositiveBalance;
        $grubkas->Status_Bayar = $isPaidOff ? 1 : 0;
        $grubkas->Keterangan = $description;
        $grubkas->save();

        Log::info("Applied payment allocation for NIM {$userNim}, Order {$orderId}", [
            'paid_amount' => $paidAmount,
            'old_debt' => $currentDebt,
            'remaining_debt' => $remainingDebt,
            'old_positive_balance' => $currentPositiveBalance,
            'new_positive_balance' => $newPositiveBalance,
            'status_bayar' => $grubkas->Status_Bayar,
        ]);
    }

    /**
     * List pending payments awaiting verification
     */
    public function index()
    {
        $pendingPayments = GrubkasActivityLog::where('transaction_status', 'awaiting_confirmation')
            ->latest('occurred_at')
            ->paginate(15);

        return view('admin.payment-verification', [
            'pendingPayments' => $pendingPayments,
        ]);
    }

    /**
     * Verify payment via QRIS API and update database
     */
    public function verify(Request $request, $orderId)
    {
        // Find activity log
        $activityLog = GrubkasActivityLog::where('order_id', $orderId)
            ->where('transaction_status', 'awaiting_confirmation')
            ->first();

        if (!$activityLog) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan atau sudah diverifikasi.');
        }

        try {
            // Call QRIS API to verify payment
            $apiKey = config('services.qris.api_key');
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post(config('services.qris.api_url') . "/orders/{$orderId}/verify", []);

            if (!$response->successful()) {
                $errorMessage = $response->json('message') ?? 'Gagal memverifikasi pembayaran di API QRIS';
                Log::error("QRIS verification failed for order {$orderId}: " . $response->body());
                return redirect()->back()->with('error', 'Verifikasi gagal: ' . $errorMessage);
            }

            // Update transaction status to paid
            $activityLog->transaction_status = 'paid';
            $activityLog->save();

            // Apply payment to member debt/saldo so status stays synchronized.
            $this->applyPaymentToMemberBalance($activityLog->user_nim, (int) $activityLog->amount, $orderId);

            Log::info("Payment verified successfully: Order {$orderId}");
            return redirect()->back()->with('success', "Pembayaran order {$orderId} berhasil diverifikasi.");

        } catch (\Exception $e) {
            Log::error("Exception during payment verification: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Approve a pending activity (payments or send_funds) without calling external API.
     */
    public function approve(Request $request, $orderId)
    {
        $activityLog = GrubkasActivityLog::where('order_id', $orderId)
            ->where('transaction_status', 'awaiting_confirmation')
            ->first();

        if (!$activityLog) {
            return redirect()->back()->with('error', 'Permintaan tidak ditemukan atau sudah diproses.');
        }

        // Mark as paid/approved
        $activityLog->transaction_status = 'paid';
        $activityLog->save();

        // If it's an incoming payment, allocate to member debt
        if ($activityLog->direction === 'in') {
            $this->applyPaymentToMemberBalance($activityLog->user_nim, (int) $activityLog->amount, $orderId);
        }

        // For outgoing send_funds, nothing else required; the 'out' record will be counted after status=paid

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui.');
    }

    /**
     * Reject/cancel payment via QRIS API and update database
     */
    public function reject(Request $request, $orderId)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        // Find activity log
        $activityLog = GrubkasActivityLog::where('order_id', $orderId)
            ->where('transaction_status', 'awaiting_confirmation')
            ->first();

        if (!$activityLog) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan atau sudah diproses.');
        }

        try {
            // Call QRIS API to cancel order
            $apiKey = config('services.qris.api_key');
            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post(config('services.qris.api_url') . "/orders/{$orderId}/cancel", []);

            if (!$response->successful()) {
                $errorMessage = $response->json('message') ?? 'Gagal membatalkan order di API QRIS';
                Log::error("QRIS cancellation failed for order {$orderId}: " . $response->body());
                return redirect()->back()->with('error', 'Pembatalan gagal: ' . $errorMessage);
            }

            // Update transaction status to cancelled
            $activityLog->transaction_status = 'cancelled';
            $rejectionReason = $request->input('rejection_reason', 'Ditolak oleh admin');
            $activityLog->description = 'Ditolak: ' . $rejectionReason;
            $activityLog->save();

            // Reset Status_Bayar if user still has debt after rejection
            if ($activityLog->user_nim) {
                $grubkas = grubkas::where('user_nim', $activityLog->user_nim)
                    ->latest('created_at')
                    ->latest('id')
                    ->first();

                if ($grubkas) {
                    $grubkas->Status_Bayar = ((int) $grubkas->Nominal) === 0 ? 1 : 0;
                    $grubkas->save();
                    Log::info("Re-synced Status_Bayar for NIM: {$activityLog->user_nim}, Order: {$orderId}");
                }
            }

            Log::info("Payment cancelled: Order {$orderId}. Reason: {$rejectionReason}");
            return redirect()->back()->with('success', "Pembayaran order {$orderId} berhasil ditolak. User dapat mengupload ulang bukti pembayaran.");

        } catch (\Exception $e) {
            Log::error("Exception during payment rejection: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display payment history with verified status
     */
    public function paidHistory()
    {
        $paidPayments = GrubkasActivityLog::where('transaction_status', 'paid')
            ->orderBy('occurred_at', 'desc')
            ->paginate(20);

        return view('admin.payment-history', [
            'paidPayments' => $paidPayments,
        ]);
    }

    /**
     * Show detail of a specific paid payment
     */
    public function showPaymentDetail($id)
    {
        $payment = GrubkasActivityLog::findOrFail($id);

        // Only show paid/verified payments
        if ($payment->transaction_status !== 'paid') {
            return redirect()->route('admin.payment.history')->with('error', 'Pembayaran tidak ditemukan atau belum terverifikasi.');
        }

        // Get member data if exists
        $member = null;
        if ($payment->user_nim) {
            $member = Datasikadmodel::where('Nim', $payment->user_nim)->first();
        }

        return view('admin.payment-detail', [
            'payment' => $payment,
            'member' => $member,
        ]);
    }
}
