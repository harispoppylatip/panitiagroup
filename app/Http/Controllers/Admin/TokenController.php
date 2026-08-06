<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datasikadmodel;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function listUsers()
    {
        $users = Datasikadmodel::query()
            ->select('id', 'nama', 'Nim', 'status_onoff')
            ->orderBy('nama')
            ->get();

        return response()->json($users);
    }

    public function updateUserStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:on,off',
        ]);

        $user = Datasikadmodel::findOrFail($id);
        $user->update([
            'status_onoff' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status user berhasil diperbarui',
            'id' => $user->id,
            'status_onoff' => $user->status_onoff,
        ]);
    }
}
