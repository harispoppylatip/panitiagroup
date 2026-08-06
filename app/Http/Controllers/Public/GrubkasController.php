<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\validasicheckout;
use App\Models\Datasikadmodel;
use App\Models\grubkas;
use App\Models\payment\GrubkasDashboard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GrubkasController extends Controller
{
    public function index()
    {
        $datauser = grubkas::with('datasikad', 'Status')->orderByDesc('updated_at')->get();

        // Kas disimpan sebagai key-value 'kas_total' di grubkas_dashboard,
        // independen dari status pembayaran anggota.
        $kas = GrubkasDashboard::totalKas();
        $totalMasuk = $kas['in'];
        $totalKeluar = $kas['out'];
        $totalKas = $totalMasuk - $totalKeluar;

        $activityLogs = collect();

        if (Schema::hasTable('grubkas_activity_logs')) {
            $activityLogs = DB::table('grubkas_activity_logs')
                ->orderByDesc(DB::raw('COALESCE(occurred_at, created_at)'))
                ->limit(5)
                ->get()
                ->map(function ($log) {
                    $amount = (int) $log->amount;
                    $type = match ($log->direction) {
                        'out' => 'out',
                        default => 'in',
                    };

                    if (!in_array($type, ['in', 'out'], true)) {
                        return null;
                    }

                    return [
                        'type' => $type,
                        'title' => $log->title,
                        'detail' => trim(($log->user_nim ? 'NIM ' . $log->user_nim . ' · ' : '') . ($log->description ?? '')),
                        'amount' => $type === 'in'
                            ? '+Rp ' . number_format($amount, 0, ',', '.')
                            : '-Rp ' . number_format($amount, 0, ',', '.'),
                        'time' => Carbon::parse($log->occurred_at ?? $log->created_at)->format('d M Y H:i'),
                    ];
                })
                ->filter()
                ->values();
        }

        if ($activityLogs->isEmpty()) {
            $activityLogs = $datauser->take(5)->map(function ($payment) {
                $amount = (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih));

                if ((int) $payment->Status_Pembayaran !== 3) {
                    return null;
                }

                return [
                    'type' => 'in',
                    'title' => 'Pembayaran dikonfirmasi untuk ' . ($payment->datasikad?->nama ?? 'Tanpa Nama'),
                    'detail' => 'NIM ' . $payment->Nim_key,
                    'amount' => '+Rp ' . number_format($amount, 0, ',', '.'),
                    'time' => Carbon::parse($payment->updated_at ?? $payment->created_at)->format('d M Y H:i'),
                ];
            })->filter()->values();
        }

        $memberChoices = Datasikadmodel::query()
            ->orderBy('nama')
            ->get(['Nim', 'nama']);

        return view('pages.grubkas', compact('datauser', 'activityLogs', 'totalKas', 'totalMasuk', 'totalKeluar', 'memberChoices'));
    }

    public function detail(Request $request)
    {
        $request->session()->put('bayarsession', [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'tagihan' => $request->tagihan,
        ]);

        $data = grubkas::with('datasikad', 'Status')->where('Nim_key', $request->nim)->first();

        if (!$data || !$data->datasikad) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Saldo lebih otomatis dipakai untuk membayar utang, sisa yang harus dibayar
        $sisaUtang = (int) $data->sisa_utang;
        $statusId = (int) ($data->Status_Pembayaran ?? 1);
        $paymentStatusLabel = $data->Status?->Status ?? match ($statusId) {
            1 => 'Belum Bayar',
            2 => 'Menunggu Konfirmasi',
            3 => 'Sudah Bayar',
            4 => 'Ditolak',
            default => 'Belum Bayar',
        };
        $canPay = $sisaUtang > 0 && $statusId !== 2;
        $rejectionReason = $statusId === 4 ? ($data->Keterangan ?: null) : null;

        return view('pages.grubkas-detail', compact('data', 'sisaUtang', 'paymentStatusLabel', 'canPay', 'rejectionReason'));
    }

    public function bayar(Request $request)
    {
        $nim = $request->session()->get('bayarsession.nim');

        if (!$nim) {
            return redirect()->route('grubkas.index')->with('error', 'Silakan cek data terlebih dahulu.');
        }

        $data = grubkas::with('datasikad', 'Status')->where('Nim_key', $nim)->first();

        if (!$data || !$data->datasikad) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Default: sisa utang (utang dikurangi saldo lebih). User boleh isi custom.
        $amount = (int) ($request->input('uang') ?: $data->sisa_utang);
        $name = $data->datasikad->nama;

        // Buat QRIS via API temanqris sesuai nominal yang dipilih user
        $qris = $this->generateQris($amount);

        $qrimage = $qris['qr_image'] ?? '';
        $expired = $qris['expired'] ?? '';
        $link_code = $qris['link_code'] ?? '';
        $order_id = $qris['order_id'] ?? '';
        $qrisError = $qris['error'] ?? '';

        $request->session()->put('bayarsession', array_merge($request->session()->get('bayarsession', []), [
            'amount' => $amount,
            'qrimage' => $qrimage,
            'expired' => $expired,
            'link_code' => $link_code,
            'order_id' => $order_id,
            'qris_error' => $qrisError,
        ]));

        return view('pages.grubkas-checkout', compact('data', 'amount', 'name', 'qrimage', 'expired', 'link_code', 'order_id', 'qrisError'));
    }

    /**
     * Membuat QRIS baru via https://temanqris.com/api/qris/generate,
     * lalu merender gambar QR-nya lewat endpoint /api/qris/render.
     */
    private function generateQris(int $amount): array
    {
        $apiKey = config('services.temanqris.apikey');
        $webhookUrl = config('services.temanqris.qris_webhook');

        if (!$apiKey) {
            return ['error' => 'API key QRIS belum dikonfigurasi.'];
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-API-KEY' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->post('https://temanqris.com/api/qris/generate', [
                    'amount' => $amount,
                    'fee_type' => 'rupiah',
                    'webhook_url' => $webhookUrl,
                ]);

            if (!$response->successful()) {
                return ['error' => 'Gagal membuat QRIS: ' . $response->body()];
            }

            $payload = $response->json() ?? [];

            if (empty($payload['success'])) {
                return ['error' => $payload['message'] ?? 'Gagal membuat QRIS.'];
            }

            $qrisDataId = (int) ($payload['payment_link']['id'] ?? 0);
            $qrImage = $payload['qr_image'] ?? '';
            $expired = $payload['expires_at'] ?? '';
            $linkCode = $payload['payment_link']['link_code'] ?? '';
            $orderId = $payload['payment_link']['order_id'] ?? '';

            // Gambar QR diambil dari endpoint render menggunakan qris_data_id
            if ($qrisDataId) {
                try {
                    $render = Http::timeout(15)
                        ->withHeaders([
                            'X-API-KEY' => $apiKey,
                            'Accept' => 'application/json',
                        ])
                        ->post('https://temanqris.com/api/qris/render', [
                            'qris_data_id' => $qrisDataId,
                        ]);

                    if ($render->successful() && !empty($render->json('qr_image'))) {
                        $qrImage = $render->json('qr_image');
                    }
                } catch (\Throwable $e) {
                    // fallback ke qr_image dari respons generate
                }
            }

            return [
                'qr_image' => $qrImage,
                'expired' => $expired,
                'link_code' => $linkCode,
                'order_id' => $orderId,
            ];
        } catch (\Throwable $e) {
            return ['error' => 'Gagal terhubung ke layanan QRIS.'];
        }
    }

    public function upload(validasicheckout $request)
    {
        $nim = $request->session()->get('bayarsession.nim');

        if (!$nim) {
            return redirect()->route('grubkas.index')->with('error', 'Sesi habis, silakan cek data terlebih dahulu.');
        }

        $data = grubkas::with('datasikad', 'Status')->where('Nim_key', $nim)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $file = $request->file('gambar');
        $filename = time() . '_' . $nim . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

        // Nominal yang dibayar diambil dari sesi checkout (bukan utang),
        // fallback ke field tersembunyi dari form kalau sesi kosong.
        $amount = max(0, (int) $request->session()->get('bayarsession.amount', 0));
        if ($amount <= 0) {
            $amount = max(0, (int) $request->input('amount', 0));
        }

        $data->update([
            'Bukti_Pembayaran' => $path,
            'Nominal_Bayar' => $amount,
            'Tanggal_Pembayaran' => now()->toDateString(),
            'Status_Pembayaran' => 2,
            'Keterangan' => 'Menunggu konfirmasi admin',
            'order_id' => $request->session()->get('bayarsession.order_id') ?: $data->order_id,
            'link_code' => $request->session()->get('bayarsession.link_code') ?: $data->link_code,
        ]);

        return view('pages.grubkas-sukses', compact('data'));
    }

    public function confirm(Request $request)
    {
        $nim = $request->session()->get('bayarsession.nim');

        if (!$nim) {
            return redirect()->route('grubkas.index')->with('error', 'Sesi habis, silakan cek data terlebih dahulu.');
        }

        $data = grubkas::with('datasikad', 'Status')->where('Nim_key', $nim)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $request->session()->forget('bayarsession');

        return view('pages.grubkas-sukses', compact('data'));
    }
}
