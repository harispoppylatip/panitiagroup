<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use App\Models\GrubkasActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GrubkasController extends Controller
{
    public function index() {
        $weeklyFee = FinanceSetting::weeklyFee();

        $allMembers = Datasikadmodel::with('latestIuran')
            ->orderBy('nama')
            ->get();

        $memberStats = $allMembers->map(function ($member) use ($weeklyFee) {
            $latestIuran = $member->latestIuran;
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
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'out' THEN -amount ELSE amount END), 0) as total")
            ->value('total');

        $datauser = $allMembers
            ->filter(function ($member) {
                return (int) optional($member->latestIuran)->Status_Bayar === 0;
            })
            ->values();

        $activityLogs = GrubkasActivityLog::latest('occurred_at')
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

        return view('pages.grubkas', compact(
            'datauser',
            'activityLogs',
            'sudahBayarMembers',
            'belumBayarMembers',
            'memberStats',
            'totalKasTerkumpul',
            'sudahBayar',
            'belumBayar',
            'totalAnggota',
            'weeklyFee'
        ));
    }

    public function bayar(Request $request){
        $niminputuser = $request->input('payer_info', $request->input('nim'));

        if (!$niminputuser) {
            return redirect()->route('grubkas')->with('error', 'Pilih anggota terlebih dahulu.');
        }

        $data = Datasikadmodel::with('latestIuran')->where('Nim', $niminputuser)->first();

        if (!$data) {
            return redirect()->route('grubkas')->with('error', 'User Tidak Ada!!');
        }

        $latestIuran = $data->latestIuran;
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

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.Server_Key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $data->Nim . '-' . rand(),
                'gross_amount' => $customAmount,
            ],
            'customer_details' => [
                'name' => $data->nama,
            ],
            'item_details' => [
                [
                    'id' => 'grubkas-' . $data->Nim,
                    'price' => $customAmount,
                    'quantity' => 1,
                    'name' => $customDescription,
                ],
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('pages.grubkas-detail', [
            'snapToken' => $snapToken,
            'namauser' => $data->nama,
            'nimuser' => $data->Nim,
            'jumlah' => $customAmount,
            'keterangan' => $customDescription,
            'openSnapOnLoad' => true,
        ]);
    }

    public function callback(Request $request) {

        $serverKey = config('midtrans.Server_Key');
        $hashed = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        if ($hashed == $request->signature_key) {
            if (in_array($request->transaction_status, ['capture', 'settlement'], true)) {
                $existingLog = GrubkasActivityLog::where('order_id', $request->order_id)->first();
                if ($existingLog) {
                    return response()->json(['message' => 'Order sudah diproses sebelumnya'], 200);
                }

                if (Str::startsWith((string) $request->order_id, 'NONMEM-')) {
                    $totalBayar = (int) round((float) $request->gross_amount);
                    $recipientName = trim((string) $request->custom_field1);
                    $description = trim((string) $request->custom_field2);

                    if ($recipientName === '') {
                        $recipientName = 'Donatur Non-Anggota';
                    }

                    if ($description === '') {
                        $description = 'Pembayaran grubkas non-anggota';
                    }

                    GrubkasActivityLog::create([
                        'user_nim' => null,
                        'user_name' => $recipientName,
                        'activity_type' => 'non_member_transfer',
                        'direction' => 'in',
                        'amount' => $totalBayar,
                        'title' => 'Kirim Dana',
                        'description' => $description,
                        'order_id' => $request->order_id,
                        'transaction_status' => $request->transaction_status,
                        'occurred_at' => now(),
                    ]);

                    return response()->json([
                        'message' => 'Callback kirim dana non-anggota berhasil diproses',
                        'nama' => $recipientName,
                        'nominal' => $totalBayar,
                    ]);
                }

                $nim = Str::before($request->order_id, '-');

                $data = Datasikadmodel::with('iuran')->where('Nim', $nim)->first();
                if (!$data) {
                    return response()->json(['message' => 'User tidak ditemukan'], 404);
                }

                $iuran = $data->iuran()->latest('id')->first();
                if (!$iuran) {
                    return response()->json(['message' => 'Data iuran tidak ditemukan'], 404);
                }

                $totalBayar = (int) round((float) $request->gross_amount);
                $utangSaatIni = (int) $iuran->Nominal;

                $sisaUtang = $utangSaatIni - $totalBayar;
                $saldoLebihBaru = 0;

                if ($sisaUtang < 0) {
                    $saldoLebihBaru = abs($sisaUtang);
                    $sisaUtang = 0;
                }

                $iuran->update([
                    'Nominal' => $sisaUtang,
                    'Status_Bayar' => $sisaUtang === 0 ? 1 : 0,
                    'Saldo_Lebih' => ((int) $iuran->Saldo_Lebih) + $saldoLebihBaru,
                ]);

                GrubkasActivityLog::create([
                    'user_nim' => $data->Nim,
                    'user_name' => $data->nama,
                    'activity_type' => 'payment',
                    'direction' => 'in',
                    'amount' => $totalBayar,
                    'title' => $data->nama,
                    'description' => 'Pembayaran kas via Midtrans',
                    'order_id' => $request->order_id,
                    'transaction_status' => $request->transaction_status,
                    'occurred_at' => now(),
                ]);

                return response()->json([
                    'message' => 'Callback pembayaran berhasil diproses',
                    'nama' => $data->nama,
                    'nim' => $data->Nim,
                    'nominal_sisa' => $sisaUtang,
                    'saldo_lebih' => $iuran->Saldo_Lebih,
                ]);
            }
        }

        return response()->json(['message' => 'Signature atau status transaksi tidak valid'], 400);
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

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.Server_Key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $orderId = 'NONMEM-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'name' => $recipientName,
            ],
            'item_details' => [
                [
                    'id' => 'non-member-fund',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => Str::limit($descriptionInput, 50, ''),
                ],
            ],
            // Simpan metadata agar callback bisa mencatat log dari data form.
            'custom_field1' => Str::limit($recipientName, 255, ''),
            'custom_field2' => Str::limit($descriptionInput, 255, ''),
            'custom_field3' => 'non_member_transfer',
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('pages.grubkas-kirim-dana', [
            'snapToken' => $snapToken,
            'openSnapOnLoad' => true,
            'recipientName' => $recipientName,
            'amount' => $amount,
            'description' => $descriptionInput,
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
