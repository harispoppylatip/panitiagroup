<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class test extends Controller
{
    public function index(){
        // Cek apakah WhatsApp API sudah dikonfigurasi
        if (!config('api.whatsapp_api') || empty(config('api.whatsapp_api'))) {
            return response()->json([
                'error' => 'WhatsApp API tidak dikonfigurasi. Set Whatsapp_UrlApi di .env',
            ]);
        }

        $requets = Http::withBasicAuth(config('api.whatsapp_username'), config('api.Whatsapp_password'))->post(config('api.whatsapp_api').'send/message', [
            "phone" => "120363332274172697@g.us",
            "message" => "Halo dari API"
        ]);

        dd($requets);
    }

   public function callback(Request $request) {
    $data = $request->all();
    Log::info('Webhook masuk', $data);   
    return response()->json([
        'pesan' => 'Berhasil Mendapatkan Data',
        'isi_pesan' => $data
    ]);
   }
}
