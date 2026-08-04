<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function front(Request $request)
    {
        $status = $request->input('status');
        $query = Tugas::query();

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        $tugas = $query->paginate(10);

        return view('pages.tugas', compact('tugas', 'status'));
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

    public function postnew(Request $request)
    {
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

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
        Tugas::destroy($id);
        return redirect()->back();
    }
}
