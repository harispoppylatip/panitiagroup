<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\validasicheckout;
use App\Models\Datasikadmodel;
use App\Models\grubkas;
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

        return view('pages.grubkas-detail', compact('data'));
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

        $amount = (int) ($request->input('uang') ?: $data->Nominal_Bayar ?: ((int) $data->Utang_Anggota + (int) $data->Saldo_Lebih));
        $name = $data->datasikad->nama;
        $qrimage = $request->session()->get('bayarsession.qrimage', '');
        $expired = $request->session()->get('bayarsession.expired', '');
        $link_code = $request->session()->get('bayarsession.link_code', '');

        return view('pages.grubkas-checkout', compact('data', 'amount', 'name', 'qrimage', 'expired', 'link_code'));
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

        $data->update([
            'Bukti_Pembayaran' => $path,
            'Status_Pembayaran' => 2,
            'Keterangan' => 'Menunggu konfirmasi admin',
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
