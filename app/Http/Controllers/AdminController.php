<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function upload(): View
    {
        return view('admin.uploadfile');
    }

    public function inserttoken(): View
    {
        return view('admin.inserttoken');
    }

    public function membertoken(){
        return view('admin.membertoken');
    }

    public function scanLoginSetting(): View
    {
        return view('admin.scanloginsetting', [
            'user' => Auth::user(),
        ]);
    }

    public function updateScanLoginSetting(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }

        $validated = $request->validate([
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->username = $validated['username'];
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        return back()->with('status', 'Login Scan berhasil diperbarui.');
    }

    
}
