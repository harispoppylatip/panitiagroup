<?php

namespace App\Http\Controllers\payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\validasicheckout;
use App\Models\grubkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class grubkascontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datauser = grubkas::with('datasikad')->get();
        return view('pages.grubkas', compact('datauser'));
    }

    public function detail(Request $request) {
        $request->session()->put('bayarsession', [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'tagihan' => $request->tagihan,
        ]);

        $data = grubkas::with('datasikad')->where('Nim_Key', $request->nim )->first();
        return view('pages.grubkas-detail', compact('data'));
    }

    public function bayar(Request $request) {
        $data = Http::withHeaders([
                'X-API-Key' => config('services.temanqris.apikey'),
                'Content-Type' => 'application/json'
            ])->post('https://temanqris.com/api/qris/generate', [
                 "amount"=> $request->uang,
                 "fee_type"=> "rupiah"
            ]);

        $api = $data->json();

        $payload = [
            'name' => $request->nama,
            'amount' => $request->uang,
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
        return redirect()->route('grubkas.kirim-dana.page')->with('success', 'Pembayaran berhasil dikonfirmasi.');
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
