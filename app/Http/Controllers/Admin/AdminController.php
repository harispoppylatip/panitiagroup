<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function scanLoginSetting(): View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        return view('admin.scanloginsetting', [
            'user' => $this->akunScan(),
        ]);
    }

    /**
     * Ubah username/password akun ber-role scanabsen (dipakai di /loginbarcode),
     * bukan akun admin yang sedang login. Kalau belum ada, akun dibuat.
     */
    public function updateScanLoginSetting(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $scan = $this->akunScan();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($scan?->id)],
            'password' => [$scan ? 'nullable' : 'required', 'min:8', 'confirmed'],
        ]);

        $scan ??= new User([
            'name' => 'Scan Absen',
            'email' => 'scanabsen+' . uniqid() . '@paz.local',
            'role' => 'scanabsen',
        ]);

        $scan->username = $validated['username'];
        if (!empty($validated['password'])) {
            $scan->password = Hash::make($validated['password']);
        }
        $scan->save();

        return back()->with('status', 'Akun login scan berhasil diperbarui.');
    }

    private function akunScan(): ?User
    {
        return User::where('role', 'scanabsen')->orderBy('id')->first();
    }
}
