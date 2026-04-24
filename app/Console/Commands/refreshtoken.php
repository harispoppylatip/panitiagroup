<?php

namespace App\Console\Commands;

use App\Models\Datasikadmodel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class refreshtoken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refreshtoken';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $waktusekarang = Carbon::now()->format('H:i:s');
        $this->info($waktusekarang);
        
        $rows = Datasikadmodel::all();
        $hasil = [];

        foreach ($rows as $row) {
            $response = Http::withHeaders([
                'college-id' => '111024',
                'Accept' => 'application/json, text/plain, */*',
            ])->post('https://mahasiswa.umkt.ac.id/v2/access_token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $row->refresh_token,
                'client_id' => 'web',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $row->update([
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                $hasil[] = $row->nama . ' ✅';
            } else {
                $hasil[] = $row->nama . ' ❌';
            }
        }

        foreach ($hasil as $item) {
            $this->info($item);
        }
    }
}
