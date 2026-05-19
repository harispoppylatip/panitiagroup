<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use App\Models\GrubkasActivityLog;
use App\Http\Requests\validationcheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GrubkasController extends Controller
{
    private function getGrubkasData() {
        $weeklyFee = FinanceSetting::weeklyFee();

        $allMembers = Datasikadmodel::orderBy('nama')
            ->get();

        $memberStats = $allMembers->map(function ($member) use ($weeklyFee) {
            // Force fetch fresh latest iuran to avoid stale data
            $latestIuran = $member->iuran()->latest('created_at')->latest('id')->first();
            $isPaid = (int) optional($latestIuran)->Status_Bayar === 1;
            $displayNominal = (int) optional($latestIuran)->Nominal;

            if ($displayNominal === 0) {
                $displayNominal = $weeklyFee;
            }

            $initials = collect(preg_split('/\s+/', trim((string) $member->nama), -1, PREG_SPLIT_NO_EMPTY))
                ->take(2)
                ->map(function ($part) {
                    return Str::upper(Str::substr($part, 0, 1));
                })
                ->implode('');

            return [
                'nim' => $member->Nim,
                'nama' => $member->nama,
                'initials' => $initials !== '' ? $initials : Str::upper(Str::substr((string) $member->nama, 0, 2)),
                'status_bayar' => $isPaid ? 1 : 0,
                'status_label' => $isPaid ? 'Lunas' : 'Pending',
                'status_class' => $isPaid ? 'success' : 'warning',
                'nominal' => $displayNominal,
                'saldo_lebih' => (int) optional($latestIuran)->Saldo_Lebih,
                'keterangan' => optional($latestIuran)->Keterangan ?: ($isPaid ? 'Sudah membayar iuran' : 'Belum membayar iuran'),
            ];
        });

        $sudahBayarMembers = $memberStats->where('status_bayar', 1)->values();
        $belumBayarMembers = $memberStats->where('status_bayar', 0)->values();

        $totalAnggota = $memberStats->count();
        $sudahBayar = $sudahBayarMembers->count();
        $belumBayar = $belumBayarMembers->count();

        $totalKasTerkumpul = (int) GrubkasActivityLog::query()
            ->whereIn('direction', ['in', 'out'])
            ->whereIn('transaction_status', ['paid', 'manual'])
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'out' THEN -amount ELSE amount END), 0) as total")
            ->value('total');

        // Get members that haven't paid - fetch fresh latest iuran for each
        $datauser = $allMembers
            ->filter(function ($member) {
                $latestIuran = $member->iuran()->latest('created_at')->latest('id')->first();
                return (int) optional($latestIuran)->Status_Bayar === 0;
            })
            ->values();

        // IMPORTANT: User only sees in/out transactions (payments and expenses), not adjustments
        $activityLogs = GrubkasActivityLog::whereIn('direction', ['in', 'out'])
            ->whereIn('transaction_status', ['paid', 'manual'])
            ->latest('occurred_at')
            ->latest('id')
            ->limit(100)
            ->get();

        $now = Carbon::now();
        $mingguIniMulai = $now->copy()->startOfWeek();
        $mingguIniAkhir = $now->copy()->endOfWeek();
        $mingguLaluMulai = $now->copy()->subWeek()->startOfWeek();
        $mingguLaluAkhir = $now->copy()->subWeek()->endOfWeek();
        $bulanIniMulai = $now->copy()->startOfMonth();
        $bulanIniAkhir = $now->copy()->endOfMonth();
        $bulanLaluMulai = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $bulanLaluAkhir = $now->copy()->subMonthNoOverflow()->endOfMonth();

        $activityLogs = $activityLogs->map(function ($log) use (
            $mingguIniMulai,
            $mingguIniAkhir,
            $mingguLaluMulai,
            $mingguLaluAkhir,
            $bulanIniMulai,
            $bulanIniAkhir,
            $bulanLaluMulai,
            $bulanLaluAkhir
        ) {
            $periodKeys = ['semua'];

            if ($log->occurred_at) {
                if ($log->occurred_at->between($mingguIniMulai, $mingguIniAkhir)) {
                    $periodKeys[] = 'minggu-1';
                }

                if ($log->occurred_at->between($mingguLaluMulai, $mingguLaluAkhir)) {
                    $periodKeys[] = 'minggu-2';
                }

                if ($log->occurred_at->between($bulanIniMulai, $bulanIniAkhir)) {
                    $periodKeys[] = 'bulan-ini';
                }

                if ($log->occurred_at->between($bulanLaluMulai, $bulanLaluAkhir)) {
                    $periodKeys[] = 'bulan-lalu';
                }
            }

            $log->period_keys = $periodKeys;
            return $log;
        });

        return [
            'datauser' => $datauser,
            'activityLogs' => $activityLogs,
            'sudahBayarMembers' => $sudahBayarMembers,
            'belumBayarMembers' => $belumBayarMembers,
            'memberStats' => $memberStats,
            'totalKasTerkumpul' => $totalKasTerkumpul,
            'sudahBayar' => $sudahBayar,
            'belumBayar' => $belumBayar,
            'totalAnggota' => $totalAnggota,
            'weeklyFee' => $weeklyFee,
        ];
    }

    public function index() {
        $data = $this->getGrubkasData();
        return view('pages.grubkas', $data);
    }

    public function grubkasinfoapi(){
        $data = $this->getGrubkasData();
        return response()->json([
            'belumbayar' => $data['belumBayarMembers'],
            'sudahBayar' => $data['sudahBayarMembers'],
            'totalKasTerkumpul' => $data['totalKasTerkumpul'],
            'activityLogs' => $data['activityLogs'],
            ]);
    }

    public function bayar(Request $request){
        $niminputuser = $request->input('payer_info', $request->input('nim'));

        if (!$niminputuser) {
            return redirect()->route('grubkas')->with('error', 'Pilih anggota terlebih dahulu.');
        }

        // Force reload from DB to ensure latest data
        $data = Datasikadmodel::where('Nim', $niminputuser)->first();

        if (!$data) {
            return redirect()->route('grubkas')->with('error', 'User Tidak Ada!!');
        }

        // Fetch latest iuran record directly from DB to avoid stale data
        $latestIuran = $data->iuran()->latest('created_at')->latest('id')->first();
        $defaultAmount = (int) ($latestIuran->Nominal ?? 0);
        if ($defaultAmount <= 0) {
            $defaultAmount = FinanceSetting::weeklyFee();
        }

        $defaultDescription = optional($latestIuran)->Keterangan ?: 'Pembayaran grubkas';

        if (!$request->filled('custom_amount')) {
            return view('pages.grubkas-detail', [
                'namauser' => $data->nama,
                'nimuser' => $data->Nim,
                'jumlah' => $defaultAmount,
                'keterangan' => $defaultDescription,
                'snapToken' => null,
                'openSnapOnLoad' => false,
            ]);
        }

        $validated = $request->validate([
            'custom_amount' => ['required', 'integer', 'min:1', 'max:100000000'],
            'custom_description' => ['nullable', 'string', 'max:255'],
        ]);

        $customAmount = (int) $validated['custom_amount'];
        $customDescription = trim((string) ($validated['custom_description'] ?? ''));
        if ($customDescription === '') {
            $customDescription = 'Pembayaran grubkas';
        }

        // Batas order_id dari API QRIS maksimal 30 karakter.
        $orderId = 'GK' . now()->format('ymdHis') . '-' . Str::substr((string) $data->Nim, -4) . '-' . random_int(1000, 9999);

       $response = Http::withHeaders([
            'X-API-Key' => config('services.qris.api_key'),
                ])->post(config('services.qris.api_url') . '/generate', [
                    'amount'   => $customAmount,
                    'fee_type' => 'rupiah',
                    'order_id' => $orderId,
                    'webhook_url' => config('services.qris.webhook_url'),
            ]);

        $dataapi = $response->json();

        Log::info('QRIS API response from bayar()', [
            'status' => $response->status(),
            'response_keys' => array_keys($dataapi ?? []),
            'full_response' => $dataapi,
        ]);

        if (!$response->successful() || !is_array($dataapi) || empty($dataapi['qr_image'])) {
            $errorMessage = is_array($dataapi)
                ? ((string) ($dataapi['message'] ?? $dataapi['error'] ?? 'Gagal membuat QR pembayaran.'))
                : 'Gagal membuat QR pembayaran.';

            return redirect()->route('grubkas')
                ->withInput()
                ->with('error', 'QRIS gagal dibuat: ' . $errorMessage);
        }

        $checkoutPayload = [
            'qrimage' => $dataapi['qr_image'],
            'amount' => $dataapi['amount'],
            'expired' => $dataapi['expires_at'],
            'name' => $data->nama,
            'description' => $customDescription,
            'nim' => $data->Nim,
            'order_id' => $orderId,
            'link_code' => $dataapi['payment_link']['link_code'] ?? null,
        ];

        $request->session()->put('grubkas_checkout', $checkoutPayload);

        return redirect()->route('grubkas.checkout.page');
    }

    /**
     * Initiate send-funds checkout (generate QR and redirect to checkout page)
     */
    public function sendFundsInitiate(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $recipient = trim($validated['recipient_name']);
        $amount = (int) $validated['amount'];
        $description = trim($validated['description'] ?? 'Permintaan kirim dana');

        // Build order id
        $orderId = 'SF' . now()->format('ymdHis') . '-' . random_int(1000, 9999);

        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.qris.api_key'),
            ])->post(config('services.qris.api_url') . '/generate', [
                'amount' => $amount,
                'fee_type' => 'rupiah',
                'order_id' => $orderId,
                'webhook_url' => config('services.qris.webhook_url'),
            ]);

            $dataapi = $response->json();
            
            Log::info('QRIS API response from sendFundsInitiate()', [
                'status' => $response->status(),
                'response_keys' => array_keys($dataapi ?? []),
                'full_response' => $dataapi,
            ]);
            
            if (!$response->successful() || !is_array($dataapi) || empty($dataapi['qr_image'])) {
                $errorMessage = is_array($dataapi) ? ($dataapi['message'] ?? 'Gagal membuat QR') : 'Gagal membuat QR';
                return redirect()->back()->withInput()->with('error', 'QRIS gagal dibuat: ' . $errorMessage);
            }

            $checkoutPayload = [
                'qrimage' => $dataapi['qr_image'],
                'amount' => $dataapi['amount'],
                'expired' => $dataapi['expires_at'] ?? null,
                'name' => $recipient,
                'description' => $description,
                'nim' => Auth::user()?->Nim ?? null,
                'order_id' => $orderId,
                'link_code' => $dataapi['payment_link']['link_code'] ?? null,
                // This flow is a donation / incoming funds -> should increase kas (direction = 'in')
                'activity_type' => 'send_funds',
                'direction' => 'in',
            ];

            $request->session()->put('grubkas_checkout', $checkoutPayload);

            return redirect()->route('grubkas.checkout.page');
        } catch (\Exception $e) {
            Log::error('Error initiating send funds: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat checkout.');
        }
    }


    public function kirimDanaNonAnggota(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'integer', 'min:1000', 'max:100000000'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $recipientName = trim((string) $validated['recipient_name']);
        $amount = (int) $validated['amount'];
        $descriptionInput = trim((string) ($validated['description'] ?? ''));

        if ($descriptionInput === '') {
            $descriptionInput = 'Pembayaran grubkas non-anggota';
        }

        // Catat kirim dana non-anggota secara langsung (tanpa gateway pembayaran)
        $orderId = 'MANUAL-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

        GrubkasActivityLog::create([
            'user_nim' => null,
            'user_name' => $recipientName,
            'activity_type' => 'non_member_transfer',
            'direction' => 'in',
            'amount' => $amount,
            'title' => 'Kirim Dana',
            'description' => $descriptionInput,
            'order_id' => $orderId,
            'transaction_status' => 'manual',
            'occurred_at' => now(),
        ]);

        return redirect()->route('grubkas')->with('success', 'Kirim dana non-anggota telah dicatat.');
    }

    public function checkout(Request $request) {
        $sessionPayload = (array) $request->session()->get('grubkas_checkout', []);
        
        Log::info('Checkout page accessed', [
            'session_keys' => array_keys($sessionPayload),
            'link_code' => $sessionPayload['link_code'] ?? 'NOT_SET',
            'order_id' => $sessionPayload['order_id'] ?? 'NOT_SET',
        ]);

        // Fallback agar tetap kompatibel jika ada akses lama via query string.
        if (empty($sessionPayload) && $request->query('qrimage')) {
            $sessionPayload = [
                'qrimage' => $request->query('qrimage'),
                'amount' => (int) $request->query('amount', 0),
                'expired' => $request->query('expired'),
                'name' => $request->query('name'),
                'description' => $request->query('description'),
                'nim' => $request->query('nim'),
                'order_id' => $request->query('order_id'),
                'link_code' => $request->query('link_code'),
            ];

            $request->session()->put('grubkas_checkout', $sessionPayload);
        }

        if (empty($sessionPayload)) {
            return redirect()->route('grubkas')->with('error', 'Sesi checkout tidak ditemukan. Silakan ulangi pembayaran.');
        }

        return view('pages.grubkas-checkout', [
            'qrimage' => $sessionPayload['qrimage'] ?? null,
            'amount' => (int) ($sessionPayload['amount'] ?? 0),
            'expired' => $sessionPayload['expired'] ?? null,
            'name' => $sessionPayload['name'] ?? null,
            'description' => $sessionPayload['description'] ?? null,
            'nim' => $sessionPayload['nim'] ?? null,
            'order_id' => $sessionPayload['order_id'] ?? null,
            'link_code' => $sessionPayload['link_code'] ?? null,
        ]);
    }

    public function checkoutUpload(validationcheckout $request)
    {
        $proof = $request->file('gambar');
        $path = $proof->store('grubkas_proofs', 'public');

        $nim = $request->input('nim');
        $member = $nim ? Datasikadmodel::where('Nim', $nim)->first() : null;
        $userName = $member?->nama;

        $activityType = $request->input('activity_type', session('grubkas_checkout.activity_type', 'payment'));
        $direction = $request->input('direction', session('grubkas_checkout.direction', $activityType === 'send_funds' ? 'out' : 'in'));
        $title = $activityType === 'send_funds' ? ('Kirim Dana: ' . ($request->input('recipient_name') ?? session('grubkas_checkout.name'))) : ($userName ?? 'Pembayaran QRIS');

        GrubkasActivityLog::updateOrCreate(
            ['order_id' => $request->input('order_id')],
            [
                'user_nim' => $nim,
                'user_name' => $userName,
                'activity_type' => $activityType,
                'direction' => $direction,
                'amount' => (int) $request->input('amount', 0),
                'title' => $title,
                'description' => $request->input('description', 'Pembayaran grubkas'),
                'transaction_status' => 'awaiting_confirmation',
                'proof_path' => $path,
                'proof_name' => $proof->getClientOriginalName(),
                'occurred_at' => now(),
            ]
        );

        // Preserve session checkout data and add proof info
        $checkoutSession = (array) $request->session()->get('grubkas_checkout', []);
        $checkoutSession['proof_path'] = $path;
        $checkoutSession['proof_name'] = $proof->getClientOriginalName();
        $request->session()->put('grubkas_checkout', $checkoutSession);

        return redirect()->route('grubkas.checkout.page')->with([
            'proof_path' => $path,
            'proof_name' => $proof->getClientOriginalName(),
            'success' => 'Bukti berhasil diunggah'
        ]);
    }

    public function checkoutConfirm(Request $request)
    {
        $validated = $request->validate([
            'nim' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'proof_path' => ['required', 'string'],
            'proof_name' => ['nullable', 'string', 'max:255'],
            'order_id' => ['required', 'string', 'max:100'],
            'link_code' => ['nullable', 'string', 'max:100'],
        ]);

        if (!Storage::disk('public')->exists($validated['proof_path'])) {
            return redirect()->back()->with('error', 'Upload bukti terlebih dahulu.');
        }

        $nim = $validated['nim'] ?? null;
        $amount = $validated['amount'];
        $description = $validated['description'] ?? 'Pembayaran grubkas';
        $orderId = $validated['order_id'];
        
        // Try to get link_code from form input first (most recent), then session
        $linkCode = $validated['link_code'] ?? null;
        if (!$linkCode) {
            $checkoutSession = (array) $request->session()->get('grubkas_checkout', []);
            $linkCode = $checkoutSession['link_code'] ?? null;
        }
        
        Log::info('Checkout confirm', [
            'orderId' => $orderId,
            'linkCode_from_form' => $validated['link_code'] ?? 'NULL',
            'linkCode_final' => $linkCode ?? 'NULL',
        ]);

        // Call QRIS confirm API if link_code exists
        if ($linkCode) {
            try {
                $response = Http::post('https://temanqris.com/api/pay/' . $linkCode . '/confirm');

                if (!$response->successful()) {
                    $errorMessage = $response->json('message') ?? 'Gagal mengkonfirmasi pembayaran ke QRIS';
                    Log::error("QRIS confirm failed for link_code {$linkCode}: " . $response->body());
                    return redirect()->back()->with('error', 'Konfirmasi QRIS gagal: ' . $errorMessage);
                }

                Log::info("QRIS payment confirmed for link_code: {$linkCode}, Order: {$orderId}");
            } catch (\Exception $e) {
                Log::error("Exception during QRIS confirmation: " . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            Log::warning("No link_code available for order {$orderId}, proceeding with record creation only");
        }

        $userName = null;
        if ($nim) {
            $member = Datasikadmodel::where('Nim', $nim)->first();
            if ($member) $userName = $member->nama;
        }

        // Persist or refresh the pending payment row so proof data is available from DB
        $activityType = $request->input('activity_type', session('grubkas_checkout.activity_type', 'payment'));
        $direction = $request->input('direction', session('grubkas_checkout.direction', $activityType === 'send_funds' ? 'out' : 'in'));
        $title = $activityType === 'send_funds' ? ('Kirim Dana: ' . ($request->input('recipient_name') ?? session('grubkas_checkout.name'))) : ($userName ?? 'Pembayaran QRIS');

        GrubkasActivityLog::updateOrCreate(
            ['order_id' => $orderId],
            [
                'user_nim' => $nim,
                'user_name' => $userName,
                'activity_type' => $activityType,
                'direction' => $direction,
                'amount' => $amount,
                'title' => $title,
                'description' => $description,
                'transaction_status' => 'awaiting_confirmation',
                'proof_path' => $validated['proof_path'],
                'proof_name' => $validated['proof_name'] ?? basename($validated['proof_path']),
                'occurred_at' => now(),
            ]
        );

        return redirect()->route('grubkas')->with('success', 'Pembayaran sudah dikonfirmasi. Menunggu verifikasi admin.');
    }

    /**
     * Upload proof and create a send-funds request (awaiting admin approval).
     */
    public function kirimDanaUpload(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
            'gambar' => ['required', 'image', 'max:5120'],
        ]);

        $proof = $request->file('gambar');
        $path = $proof->store('grubkas_proofs', 'public');

        $nim = Auth::user()?->Nim ?? null;
        $userName = Auth::user()?->name ?? null;

        $orderId = 'SF' . now()->format('ymdHis') . '-' . random_int(1000, 9999);

        GrubkasActivityLog::create([
            'user_nim' => $nim,
            'user_name' => $userName,
            'activity_type' => 'send_funds',
            'direction' => 'out',
            'amount' => (int) $validated['amount'],
            'title' => 'Kirim Dana: ' . $validated['recipient_name'],
            'description' => $validated['description'] ?? 'Permintaan kirim dana',
            'order_id' => $orderId,
            'transaction_status' => 'awaiting_confirmation',
            'proof_path' => $path,
            'proof_name' => $proof->getClientOriginalName(),
            'occurred_at' => now(),
        ]);

        return redirect()->route('grubkas.kirim-dana.page')->with('success', 'Permintaan kirim dana dikirim. Menunggu persetujuan admin.');
    }

    public function callbackapi(Request $request) {
    $data = $request->all();  
    return response()->json([
        'pesan' => 'Berhasil Mendapatkan Data',
        'isi_pesan' => $data
    ]);
   }

    public function processPaymentWebhook(Request $request)
    {
        $data = $request->all();
        
        // Log webhook for debugging
        \Illuminate\Support\Facades\Log::info('Payment webhook received', $data);

        if (!isset($data['data']['order_id']) || !isset($data['event'])) {
            return response()->json(['status' => 'invalid'], 400);
        }

        $orderId = $data['data']['order_id'];
        $event = $data['event'];
        $paymentStatus = $data['data']['status'] ?? null;

        // Find activity log by order_id
        $activityLog = GrubkasActivityLog::where('order_id', $orderId)->first();

        if (!$activityLog) {
            \Illuminate\Support\Facades\Log::warning("Activity log not found for order_id: {$orderId}");
            return response()->json(['status' => 'not_found'], 404);
        }

        // Map webhook events to transaction status
        $newStatus = null;
        switch ($event) {
            case 'payment.awaiting_confirmation':
                $newStatus = 'awaiting_confirmation';
                break;
            case 'payment.confirmed':
            case 'payment.settlement':
                $newStatus = 'paid';
                break;
            case 'payment.failed':
                $newStatus = 'failed';
                break;
            default:
                $newStatus = $paymentStatus; // fallback to status from webhook
                break;
        }

        // Update activity log
        $activityLog->transaction_status = $newStatus;
        $activityLog->save();

        // If payment confirmed, allocate amount to debt/saldo and sync payment status.
        if ($newStatus === 'paid' && $activityLog->user_nim) {
            try {
                $grubkas = \App\Models\grubkas::where('user_nim', $activityLog->user_nim)
                    ->latest('created_at')
                    ->latest('id')
                    ->first();
                
                if ($grubkas) {
                    $currentDebt = max(0, (int) $grubkas->Nominal);
                    $currentPositiveBalance = max(0, (int) $grubkas->Saldo_Lebih);
                    $paidAmount = max(0, (int) $activityLog->amount);

                    $remainingDebt = max(0, $currentDebt - $paidAmount);
                    $overPayment = max(0, $paidAmount - $currentDebt);

                    $grubkas->Nominal = $remainingDebt;
                    $grubkas->Saldo_Lebih = $currentPositiveBalance + $overPayment;
                    $grubkas->Status_Bayar = $remainingDebt === 0 ? 1 : 0;

                    if ($remainingDebt > 0) {
                        $grubkas->Keterangan = 'Sisa utang Rp ' . number_format($remainingDebt, 0, ',', '.');
                    } elseif ($overPayment > 0) {
                        $grubkas->Keterangan = 'Pembayaran lebih Rp ' . number_format($overPayment, 0, ',', '.') . ' menjadi saldo positif';
                    }

                    $grubkas->save();
                    
                    \Illuminate\Support\Facades\Log::info("Updated grubkas debt/saldo from webhook for NIM: {$activityLog->user_nim}", [
                        'paid_amount' => $paidAmount,
                        'remaining_debt' => $remainingDebt,
                        'saldo_lebih' => $grubkas->Saldo_Lebih,
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error updating Status_Bayar: " . $e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'order_id' => $orderId,
            'transaction_status' => $newStatus
        ]);
    }

    public function halamanKirimDanaNonAnggota()
    {
        return view('pages.grubkas-kirim-dana', [
            'snapToken' => null,
            'openSnapOnLoad' => false,
        ]);
    }
}
