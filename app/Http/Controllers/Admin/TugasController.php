<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function front()
    {
        $hariIni = today()->format('Y-m-d');

        // tanpa tanggal ikut "mendatang", ditaruh paling bawah
        $mendatang = Tugas::where(fn ($q) => $q->where('deadline_tanggal', '>=', $hariIni)->orWhereNull('deadline_tanggal'))
            ->orderByRaw('deadline_tanggal IS NULL, deadline_tanggal')
            ->get();
        $lewat = Tugas::where('deadline_tanggal', '<', $hariIni)->orderByDesc('deadline_tanggal')->limit(10)->get();

        return view('pages.tugas', compact('mendatang', 'lewat'));
    }

    public function index()
    {
        $tugas = Tugas::orderByRaw('deadline_tanggal IS NULL, deadline_tanggal')->get();

        return view('admin.tugas.index', compact('tugas'));
    }

    public function create()
    {
        return view('admin.tugas.create');
    }

    public function postnew(Request $request)
    {
        Tugas::create($this->validasi($request));

        return redirect()->route('admin.tugas.index');
    }

    public function show(int $id)
    {
        $tugas = Tugas::findOrFail($id);

        return view('admin.tugas.show', compact('tugas'));
    }

    public function edit(int $id)
    {
        $tugas = Tugas::findOrFail($id);

        return view('admin.tugas.edit', ['tugas' => $tugas,
        'id' => $id]);
    }

    public function update(Request $request, $id)
    {
        Tugas::findOrFail($id)->update($this->validasi($request));

        return redirect()->route('admin.tugas.index');
    }

    public function destroy($id)
    {
        Tugas::destroy($id);
        return redirect()->back();
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'namatugas' => 'required|string|max:255',
            'penjelasan' => 'nullable|string|max:65000',
            'deadline' => 'nullable|string|max:255',
            'deadline_tanggal' => 'nullable|date',
        ]);
    }
}
