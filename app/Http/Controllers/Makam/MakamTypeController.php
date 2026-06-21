<?php

namespace App\Http\Controllers\Makam;

use App\Http\Controllers\Controller;
use App\Models\MakamType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MakamTypeController extends Controller
{
    public function index(): View
    {
        $types = MakamType::orderBy('created_at', 'desc')->paginate(10);
        return view('makam.types.index', compact('types'));
    }

    public function create(): View
    {
        return view('makam.types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'blok' => 'nullable|string|max:255',
            'stok_tersedia' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        MakamType::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'blok' => $request->blok,
            'stok_tersedia' => $request->stok_tersedia,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('makam.types.index')
            ->with('success', 'Jenis makam berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $type = MakamType::findOrFail($id);
        return view('makam.types.edit', compact('type'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'blok' => 'nullable|string|max:255',
            'stok_tersedia' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $type = MakamType::findOrFail($id);
        $type->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'blok' => $request->blok,
            'stok_tersedia' => $request->stok_tersedia,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('makam.types.index')
            ->with('success', 'Jenis makam berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $type = MakamType::findOrFail($id);

        // Cek apakah tipe makam memiliki pesanan
        if ($type->orders()->count() > 0) {
            return redirect()->route('makam.types.index')
                ->with('error', 'Tidak dapat menghapus jenis makam yang memiliki pesanan.');
        }

        $type->delete();

        return redirect()->route('makam.types.index')
            ->with('success', 'Jenis makam berhasil dihapus.');
    }
}
