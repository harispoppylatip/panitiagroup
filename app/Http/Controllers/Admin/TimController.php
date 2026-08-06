<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Datasikadmodel;
use App\Models\grubkas;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TimController extends Controller
{
    private function processPhoto(Request $request, $oldImageUrl = null)
    {
        if ($request->hasFile('photo_image')) {
            if ($oldImageUrl && !filter_var($oldImageUrl, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImageUrl));
            }

            $file = $request->file('photo_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('beranda', $filename, 'public');
            return '/storage/' . $path;
        }

        if ($request->filled('photo_image_url')) {
            if ($oldImageUrl && !filter_var($oldImageUrl, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImageUrl));
            }
            return $request->input('photo_image_url');
        }

        return $oldImageUrl;
    }

    /**
     * Sinkronkan kartu tampilan beranda (team_members) dengan datasikad.
     * Sumber kebenaran = datasikad (Management Tim): setiap anggota tanpa kartu
     * otomatis dibuatkan kartu, dan kartu yang NIM-nya sudah tidak ada di datasikad dihapus.
     */
    private function sinkronkanKartu(): void
    {
        $anggota = Datasikadmodel::orderBy('nama')->get();
        $nimValid = $anggota->pluck('Nim')->map(fn ($n) => (string) $n)->all();

        $kartuByNim = TeamMember::all();
        $nimTerkartu = $kartuByNim->pluck('nim')->map(fn ($n) => (string) $n)->filter()->all();
        $nextOrder = (int) TeamMember::max('order') + 1;

        // Buat kartu untuk anggota yang belum punya kartu
        foreach ($anggota as $item) {
            if (!in_array((string) $item->Nim, $nimTerkartu, true)) {
                TeamMember::create([
                    'nim' => $item->Nim,
                    'name' => $item->nama,
                    'role' => 'Anggota',
                    'image_url' => null,
                    'order' => $nextOrder++,
                ]);
            }
        }

        // Hapus kartu yang NIM-nya kosong atau sudah tidak ada di datasikad
        foreach ($kartuByNim as $kartu) {
            $nimKartu = $kartu->nim ? (string) $kartu->nim : '';
            if ($nimKartu === '' || !in_array($nimKartu, $nimValid, true)) {
                if ($kartu->image_url && !filter_var($kartu->image_url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $kartu->image_url));
                }
                $kartu->delete();
            }
        }
    }

    public function index(): View
    {
        $this->sinkronkanKartu();

        $data = Datasikadmodel::orderBy('nama')->get();
        $displayByNim = TeamMember::all()->keyBy('nim');

        // Status pembayaran kas per anggota
        $statusPembayaran = grubkas::select('Nim_key', 'Status_Pembayaran')
            ->get()
            ->keyBy('Nim_key');

        return view('admin.tim.index', compact('data', 'displayByNim', 'statusPembayaran'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'Nim' => 'required|string|max:20|unique:datasikad,Nim',
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'status_onoff' => 'nullable|in:on,off',
            'role' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'photo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photo_image_url' => 'nullable|url',
        ]);

        $imageUrl = $this->processPhoto($request);

        DB::transaction(function () use ($validated, $imageUrl) {
            $anggota = Datasikadmodel::create([
                'nama' => $validated['nama'],
                'Nim' => $validated['Nim'],
                'access_token' => $validated['access_token'],
                'refresh_token' => $validated['refresh_token'],
                'status_onoff' => $validated['status_onoff'] ?? 'off',
            ]);

            // Sinkron: buat kartu tampilan beranda untuk anggota yang sama
            TeamMember::create([
                'nim' => $anggota->Nim,
                'name' => $validated['nama'],
                'role' => $validated['role'],
                'image_url' => $imageUrl,
                'order' => $validated['order'],
            ]);
        });

        return redirect()->route('admin.tim.index')
            ->with('success', 'Anggota tim berhasil ditambahkan (token, tampilan beranda, dan kas tersinkron)');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $anggota = Datasikadmodel::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'Nim' => 'required|string|max:20|unique:datasikad,Nim,' . $anggota->id,
            'access_token' => 'required|string',
            'refresh_token' => 'required|string',
            'status_onoff' => 'nullable|in:on,off',
            'role' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'photo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'photo_image_url' => 'nullable|url',
        ]);

        $oldNim = $anggota->Nim;
        $display = TeamMember::where('nim', $oldNim)->first();
        $imageUrl = $this->processPhoto($request, $display?->image_url);

        DB::transaction(function () use ($anggota, $display, $validated, $imageUrl, $oldNim) {
            // Lepas relasi kartu lama dulu agar NIM bisa diubah tanpa bentrok FK
            if ($display) {
                $display->delete();
            }

            $anggota->update([
                'nama' => $validated['nama'],
                'Nim' => $validated['Nim'],
                'access_token' => $validated['access_token'],
                'refresh_token' => $validated['refresh_token'],
                'status_onoff' => $validated['status_onoff'] ?? 'off',
            ]);

            // Sinkron: buat ulang kartu tampilan beranda dengan NIM baru
            TeamMember::create([
                'nim' => $anggota->Nim,
                'name' => $validated['nama'],
                'role' => $validated['role'],
                'image_url' => $imageUrl,
                'order' => $validated['order'],
            ]);
        });

        return redirect()->route('admin.tim.index')
            ->with('success', 'Anggota tim berhasil diperbarui (token, tampilan beranda, dan kas tersinkron)');
    }

    public function destroy($id): RedirectResponse
    {
        $anggota = Datasikadmodel::findOrFail($id);

        DB::transaction(function () use ($anggota) {
            // Hapus kartu tampilan beranda dulu, lalu data anggota (kas ikut terhapus via cascade)
            $display = TeamMember::where('nim', $anggota->Nim)->first();
            if ($display) {
                if ($display->image_url && !filter_var($display->image_url, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $display->image_url));
                }
                $display->delete();
            }

            $anggota->delete();
        });

        return redirect()->route('admin.tim.index')
            ->with('success', 'Anggota tim berhasil dihapus');
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

        return redirect()->route('admin.tim.index')->with([
            'success' => 'Refresh token selesai',
            'hasil_refresh' => $hasil,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ]);
    }
}
