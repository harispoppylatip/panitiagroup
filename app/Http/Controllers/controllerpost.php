<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modelpost;
use App\Models\gambaran;
use App\Models\sikaddata;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Http;

class controllerpost extends Controller
{
    public function home()
    {
        // $hasil = [];
        // $sikad = Sikaddata::all();

        // foreach ($sikad as $item) {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //         'college-id' => '111024',
        //         'Authorization' => 'Bearer ' . $item->access_token,
        //     ])->get("https://mahasiswa.umkt.ac.id/v1/mahasiswa/{$item->nim}/biodata");

        //     if ($response->successful()) {
        //         $hasil[] = $response->json();
        //     }
        // }

        return view('pages.home');
    }

    public function index()
    {
        $data = modelpost::all();
        return view('pages.postingan', compact('data'));
    }

    public function editor()
    {
        $data = modelpost::all();
        return view('admin.editorpost', compact('data'));
    }
    public function delete($id)
    {
        modelpost::destroy($id);
        return redirect('/editor');
    }

    
}
