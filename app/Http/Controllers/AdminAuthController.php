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
        $request->validate([
            'login_input' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginInput = $request->input('login_input');
        $password = $request->input('password');

        // Check if input is email or username
        $credentials = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginInput, 'password' => $password]
            : ['username' => $loginInput, 'password' => $password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user instanceof User) {
                Auth::logout();

                return back()
                    ->withErrors(['login_input' => 'Akun tidak valid'])
                    ->onlyInput('login_input');
            }

            if ($user->role === 'scanabsen') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['login_input' => 'Akun scanabsen hanya bisa login lewat halaman absensi'])
                    ->onlyInput('login_input');
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
            ->withErrors(['login_input' => 'Email/Username atau password salah'])
            ->onlyInput('login_input');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
