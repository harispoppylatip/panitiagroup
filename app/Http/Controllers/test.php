<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use Illuminate\Support\Facades\Http;

class test extends Controller
{
    public function index(){
        // Cek apakah WhatsApp API sudah dikonfigurasi
        if (!config('api.whatsapp_api') || empty(config('api.whatsapp_api'))) {
            return response()->json([
                'error' => 'WhatsApp API tidak dikonfigurasi. Set Whatsapp_UrlApi di .env',
            ]);
        }

        $requets = Http::post(config('api.whatsapp_api').'send/message', [
            "phone" => "120363332274172697@g.us",
            "message" => "Halo dari API"
        ]);

        dd($requets);
    }
}
