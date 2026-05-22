<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class tokencontroller extends Controller
{
    public function index(): View
    {
        $data = Datasikadmodel::orderBy('nama')->get();

        return view('admin.managementtoken', compact('data'));
    }

    public function membertokenproses(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'Nim' => 'required|string',
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'urlpost' => 'nullable|url',
            'status_onoff' => 'nullable|in:on,off',
        ]);

        Datasikadmodel::create($validated);

        return redirect()->route('admin.membertoken')->with('success', 'Token berhasil ditambahkan');
    }

    public function destroy($id): RedirectResponse
    {
        Datasikadmodel::destroy($id);

        return redirect()->back()->with('success', 'Token berhasil dihapus');
    }

    public function edit($id): View
    {
        $data = Datasikadmodel::findOrFail($id);

        return view('admin.edittoken', compact('data'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'Nim' => 'required|string',
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'urlpost' => 'nullable|url',
            'status_onoff' => 'nullable|in:on,off',
        ]);

        Datasikadmodel::findOrFail($id)->update($validated);

        return redirect()->route('admin.membertoken')->with('success', 'Token berhasil diperbarui');
    }

    public function refreshAllTokens()
    {
        $rows = Datasikadmodel::all();
        $hasil = [];
        $successCount = 0;
        $failedCount = 0;

        foreach ($rows as $row) {
            $response = Http::withHeaders([
                'college-id' => '111024',
                'Accept' => 'application/json, text/plain, */*',
            ])->post('https://mahasiswa.umkt.ac.id/v2/access_token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $row->refresh_token,
                'client_id' => 'web',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $row->update([
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                $hasil[] = [
                    'nama' => $row->nama,
                    'status' => 'berhasil',
                    'icon' => '✅',
                ];
                $successCount++;
            } else {
                $hasil[] = [
                    'nama' => $row->nama,
                    'status' => 'gagal',
                    'icon' => '❌',
                ];
                $failedCount++;
            }
        }

        return redirect()->route('admin.membertoken')->with([
            'success' => 'Refresh token selesai',
            'hasil_refresh' => $hasil,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ]);
    }

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

    public function refreshtoken()
    {
        $rows = Datasikadmodel::all();
        $hasil = [];

        foreach ($rows as $row) {
            $response = Http::withHeaders([
                'college-id' => '111024',
                'Accept' => 'application/json, text/plain, */*',
            ])->post('https://mahasiswa.umkt.ac.id/v2/access_token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $row->refresh_token,
                'client_id' => 'web',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $row->update([
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                $hasil[] = $row->nama . ' ✅';
            } else {
                $hasil[] = $row->nama . ' ❌';
            }
        }

        return implode('<br>', $hasil);
    }
}



