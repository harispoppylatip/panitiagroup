<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        return response()->json([
            'pesan' => 'Berhasil Mendapatkan Data',
            'isi_pesan' => $data
        ]);
    }
}
