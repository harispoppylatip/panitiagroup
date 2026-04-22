<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    private function dummyTugas(): array
    {
        return [
            [
                'id' => 1,
                'judul' => 'Membuat Wireframe Dashboard',
                'deskripsi' => 'Rancang wireframe untuk dashboard admin menggunakan Figma.',
                'mata_kuliah' => 'UI/UX Design',
                'deadline' => '2026-04-24',
                'status' => 'Belum Dikerjakan',
                'prioritas' => 'Tinggi',
            ],
            [
                'id' => 2,
                'judul' => 'Implementasi API Autentikasi',
                'deskripsi' => 'Buat endpoint login dan refresh token untuk aplikasi mobile.',
                'mata_kuliah' => 'Pemrograman Web Lanjut',
                'deadline' => '2026-04-27',
                'status' => 'Proses',
                'prioritas' => 'Sedang',
            ],
            [
                'id' => 3,
                'judul' => 'Laporan Pengujian Unit',
                'deskripsi' => 'Susun laporan hasil unit test dan validasi untuk modul token.',
                'mata_kuliah' => 'Rekayasa Perangkat Lunak',
                'deadline' => '2026-05-02',
                'status' => 'Selesai',
                'prioritas' => 'Rendah',
            ],
        ];
    }

    private function getTugasById(int $id): array
    {
        $tugas = collect($this->dummyTugas())->firstWhere('id', $id);

        return $tugas ?? $this->dummyTugas()[0];
    }

    public function front()
    {
        $tugas = Tugas::all();

        return view('pages.tugas', compact('tugas'));
    }

    public function index()
    {
        $tugas = Tugas::all();

        return view('admin.tugas.index', compact('tugas'));
    }

    public function create()
    {
        return view('admin.tugas.create');
    }

    public function postnew(Request $request) {
        Tugas::create([
            'judul' => $request->judul,
            'mata_kuliah' => $request->matkul,
            'deadline' => $request->deadline,
            'prioritas' => $request->prioritas,
            'deskripsi' => $request->deks,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.tugas.index');
    }

    public function show(int $id)
    {
        $tugas = Tugas::find($id);

        return view('admin.tugas.show', compact('tugas'));
    }

    public function edit(int $id)
    {
        $tugas = Tugas::find($id);

        return view('admin.tugas.edit', ['tugas' => $tugas,
        'id' => $id]);
    }

    public function update(Request $request, $id) {
        $tugas = Tugas::find($id);
        $tugas->update([
            'judul' => $request->judul,
            'mata_kuliah' => $request->mata_kuliah,
            'deadline' => $request->deadline,
            'prioritas' => $request->prioritas,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);
        return redirect()->route('admin.tugas.index');
    }

    public function destroy($id) {
        Tugas::destroy($id);
        return redirect()->back();
    }
}
