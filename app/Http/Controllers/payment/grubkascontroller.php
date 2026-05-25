<?php

namespace App\Http\Controllers\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\validasicheckout;
use App\Models\Datasikadmodel;
use App\Models\grubkas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class grubkascontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datauser = grubkas::with('datasikad', 'Status')->orderByDesc('updated_at')->get();

        $totalMasuk = $datauser
            ->filter(fn ($payment) => (int) $payment->Status_Pembayaran === 3)
            ->sum(fn ($payment) => (int) ($payment->Nominal_Bayar ?: ((int) $payment->Utang_Anggota + (int) $payment->Saldo_Lebih)));

        $totalKeluar = Schema::hasTable('grubkas_activity_logs')
            ? (int) DB::table('grubkas_activity_logs')->where('direction', 'out')->sum('amount')
            : 0;

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
                    'title' => 'Pembayaran dikonfirmasi — ' . ($payment->datasikad?->nama ?? 'Tanpa Nama'),
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

    public function detail(Request $request) {
        $request->session()->put('bayarsession', [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'tagihan' => $request->tagihan,
        ]);

        $data = grubkas::with('datasikad', 'Status')->where('Nim_key', $request->nim)->first();

        if (!$data || !$data->datasikad) {
            return redirect()->route('grubkas.index')->with('error', 'Data anggota tidak ditemukan.');
        }

        if ((int) $data->Status_Pembayaran === 3) {
            return redirect()->route('grubkas.index')->with('error', 'Pembayaran anggota ini sudah lunas.');
        }

        $paymentStatusLabel = match ((int) $data->Status_Pembayaran) {
            1 => 'Belum Bayar',
            2 => 'Menunggu Konfirmasi',
            4 => 'Ditolak',
            default => $data->Status?->Status ?? 'Status Tidak Diketahui',
        };

        $canPay = (int) $data->Status_Pembayaran === 1 && (int) $data->Utang_Anggota > 0;
        $rejectionReason = (int) $data->Status_Pembayaran === 4 ? $data->Keterangan : null;

        return view('pages.grubkas-detail', compact('data', 'paymentStatusLabel', 'canPay', 'rejectionReason'));
    }

    public function bayar(Request $request) {
        $amount = (int) $request->uang;

        if ($amount < 1) {
            return redirect()->route('grubkas.index')->with('error', 'Tagihan tidak valid untuk pembayaran.');
        }

        $data = Http::withHeaders([
                'X-API-Key' => config('services.temanqris.apikey'),
                'Content-Type' => 'application/json'
            ])->post('https://temanqris.com/api/qris/generate', [
                 "amount"=> $amount,
                 "fee_type"=> "rupiah"
            ]);

        $api = $data->json();

        $payload = [
            'name' => $request->nama,
            'amount' => $amount,
            'qrimage' => $api['qr_image'] ?? null,
            'expired' => $api['expires_at'] ?? null,
            'link_code' => $api['payment_link']['link_code'] ?? null,
        ];

        $request->session()->put('grubkas_checkout', [
            'name' => $payload['name'],
            'amount' => $payload['amount'],
            'qrimage' => $payload['qrimage'],
            'expired' => $payload['expired'],
            'link_code' => $payload['link_code'],
            'nim' => $request->nim,
        ]);

        return view('pages.grubkas-checkout', $payload);
    }

    public function upload(validasicheckout $request)
    {
        $checkout = session('grubkas_checkout', []);

        if (!$request->hasFile('gambar')) {
            return back()->with('error', 'Bukti pembayaran wajib diunggah.');
        }

        $path = $request->file('gambar')->store('bukti_pembayaran', 'public');

        $request->session()->put('proof_path', $path);
        $request->session()->put('proof_name', $request->file('gambar')->getClientOriginalName());

        return view('pages.grubkas-checkout', [
            'name' => $checkout['name'] ?? $request->input('name'),
            'amount' => $checkout['amount'] ?? $request->input('amount'),
            'qrimage' => $checkout['qrimage'] ?? null,
            'expired' => $checkout['expired'] ?? null,
            'link_code' => $checkout['link_code'] ?? $request->input('link_code'),
        ])->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function confirm(Request $request)
    {   
        $datasession = session('grubkas_checkout', []);
        $proofPath = session('proof_path');
        $amount = (int) ($datasession['amount'] ?? 0);

        if (empty($datasession['nim']) || empty($proofPath)) {
            return redirect()->route('grubkas.index')->with('error', 'Data checkout tidak lengkap. Silakan ulangi proses pembayaran.');
        }

        $dataangggota = Datasikadmodel::where('Nim', $datasession['nim'])->first();

        grubkas::updateOrCreate(
            ['Nim_key' => $datasession['nim']],
            [
                'Bukti_Pembayaran' => $proofPath,
                'Nominal_Bayar' => $amount,
                'Tanggal_Pembayaran' => Carbon::now()->toDateString(),
                'Status_Pembayaran' => 2,
                'Keterangan' => $dataangggota?->nama ? 'Pembayaran QRIS menunggu konfirmasi' : 'Pembayaran menunggu konfirmasi',
            ]
        );

        if (!empty($datasession['link_code'])) {
            Http::post('https://temanqris.com/api/pay/'.$datasession['link_code'].'/confirm');
        }

        $request->session()->forget(['grubkas_checkout', 'proof_path', 'proof_name']);
        return redirect()->route('grubkas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
