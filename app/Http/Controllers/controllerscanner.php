<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\sikaddata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class controllerscanner extends Controller
{
    public function index()
    {
        return view('scan.barcode');
    }

    public function loginbarcode()
    {
        return view('scan.loginscan');
    }

    public function login(Request $request)
    {
        $cre = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($cre)) {
            if (Auth::user()?->role !== 'scanabsen') {
                Auth::logout();

                return back()
                    ->withErrors(['username' => 'Halaman ini khusus akun scanabsen'])
                    ->onlyInput('username');
            }

            $request->session()->regenerate();
            return redirect()->route('scan.barcode');
        }

        return back()
            ->withErrors(['username' => 'Username / password salah'])
            ->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/loginbarcode');
    }
}
