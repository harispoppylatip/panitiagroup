<?php

namespace App\Http\Controllers;

use App\Models\gambaran;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class indexcontroller extends Controller
{    

    public function upload(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'detailgambar' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $detail = $request->detailgambar;
        $imageName = time() . '.' . $request->gambar->extension();
        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0755, true);
        }
        $request->gambar->move(public_path('images'), $imageName);

        gambaran::create([
            'detail' => $detail,
            'gambar' => $imageName,
        ]);
        return redirect()->route('admin.beranda.index')->with('success', 'Data berhasil disimpan.');
    }
}
