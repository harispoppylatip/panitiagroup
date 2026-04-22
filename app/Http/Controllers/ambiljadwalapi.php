<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ambiljadwalapi extends Controller
{
    public function index() {
        $data = Datasikadmodel::inRandomOrder()->first();
        $jadwal = [];
        $error = null;

        if (!$data) {
            $error = 'Token Error: Tidak ada data token di database';
            return view('pages.jadwal', compact('jadwal', 'error'));
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json, text/plain, */*',
            'college-id' => '111024',
            'Authorization' => 'Bearer '. $data->access_token,
        ])->get('https://mahasiswa.umkt.ac.id/v1/mahasiswa/2411102441024/jadwal_kuliah?semester=2&tahun=2026');
    
        if (!$response->successful()) {
            $error = 'Token Error: Gagal mengambil data jadwal';
            return view('pages.jadwal', compact('jadwal', 'error'));
        }

        $data = $response->json()['data'];
        
        if (empty($data)) {
            $error = 'Token Error: Data jadwal kosong';
            return view('pages.jadwal', compact('jadwal', 'error'));
        }

        foreach ($data as $item) {
            $jadwal[] = [
                'matkul' => $item['makul']['nama'],
                'hari' => $item['jadwal'][0]['hari']['nama'],
                'ruang' => $item['jadwal'][0]['ruangan']['nama'],
                'dosen' => $item['pengajar'][0]['namaPengajar'],
                'jamberangkat' => Carbon::parse($item['jadwal'][0]['jamMulai'])->format('H:i'),
                'jampulang' => Carbon::parse($item['jadwal'][0]['jamSelesai'])->format('H:i'),
            ];
        };
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $jadwal = collect($jadwal)
            ->groupBy('hari')
            ->sortBy(function ($items, $hari) use ($urutanHari) {
                $index = array_search($hari, $urutanHari, true);

                return $index === false ? PHP_INT_MAX : $index;
            })
            ->map(fn ($items) => $items->values());

        return view('pages.jadwal', compact('jadwal', 'error'));
    }
}
