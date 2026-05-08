<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user instanceof User) {
                Auth::logout();

                return back()
                    ->withErrors(['username' => 'Akun tidak valid'])
                    ->onlyInput('username');
            }

                    if ($user->role === 'scanabsen') {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()
                        ->withErrors(['username' => 'Akun scanabsen hanya bisa login lewat halaman absensi'])
                        ->onlyInput('username');
                    }

                    $role = $user->role;

            $destination = match ($role) {
                'admin' => route('admin.beranda.index'),
                'akuntan' => route('admin.finance.index'),
                'anggota' => route('admin.beranda.index'),
                default => route('scan.barcode'),
            };

            return redirect($destination);
        }

        return back()
            ->withErrors(['username' => 'Username atau password salah'])
            ->onlyInput('username');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
