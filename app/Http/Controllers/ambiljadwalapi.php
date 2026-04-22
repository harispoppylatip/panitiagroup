<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ambiljadwalapi extends Controller
{
    public function index() {
        $response = Http::withHeaders([
            'Accept' => 'application/json, text/plain, */*',
            'college-id' => '111024',
            'Authorization' => 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhc19uYW1lIjoiIiwiYXNfcHJvZGkiOm51bGwsImFzX3JvbGUiOiIiLCJhc19yb2xlX29wZW5hcGkiOiIiLCJhc191c2VybmFtZSI6IiIsImFzX3VzZXJuYW1lX29wZW5hcGkiOiIiLCJhc191dWlkIjoiIiwiYXVkIjoid2ViIiwiY29sbGVnZV9pZCI6IjExMTAyNCIsImV4cCI6MTc3MjU5NTg1NCwiaWF0IjoxNzcxOTkxMDU0LCJqdGkiOiI4dnA4YXBlMTN2cGh4MjNpZnNtZXU0NHFtOHY5cnI3dWpoZjdwbmdrb2F0am4ycXlnaThqOHozbWZyOTRndWpkbXh1MHY3azVoYnY5cjNqMSIsIm5hbWUiOiJIQVJJIFBPUFBZIExBVElQIiwibmJmIjoxNzcxOTkxMDU0LCJwcm9kaSI6W10sInJvbGUiOiJST0xFX01BSEFTSVNXQSIsInJvbGVfb3BlbmFwaSI6IiIsInNjb3BlcyI6WyJGNTpCIiwiRjY6QiJdLCJzdGF0ZSI6IiIsInN1YiI6IjItMjQxMTEwMjQ0MTAyNCIsInVzZXJuYW1lIjoiMjQxMTEwMjQ0MTAyNCIsInVzZXJuYW1lX29wZW5hcGkiOiIiLCJ1dWlkIjoiZjk5NTkxMjQtODdkYS01YmRlLTk0Y2MtMjdiNzA4MmU4NDQ0In0.AYo5OFWL39TGSueyzniCZ32Y62KpmAQRg6wNpVJEowDUlUh3hAKGAh75h-9uEIF2_EAbdLUpIOgRSK2pj5VkV_gfRHHiLqzS2rlx31suams8-mPkstNO_34ZNRFwQqju3pQmYi3zjwHSxTTEpgxy9ZqP84g99DbEDZGv9TZkCcuQlW1U2xMPNgU2uFa5X0r7SiMnXzm5V4pVr6yoO-4NIyNoqwNuySSpcoekt1bKr-ZD2gI25zPhEWKt6nC554nPp60oGiR5W5PkUup7Bl5Is8hINXzJNvHc6-4qqWTVsmKdC0AwQlERWHKW0Q1DAST58O4xbjMlaHlDNP1VdQqVbg',
        ])->get('https://mahasiswa.umkt.ac.id/v1/mahasiswa/2411102441024/jadwal_kuliah?semester=2&tahun=2026');
    
        $data = $response->json()['data'];
        // $nama = $data[3]['makul']['nama'];
        // echo $nama;
        $jadwal = [];
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

        return view('pages.jadwal', compact('jadwal'));
    }
}
