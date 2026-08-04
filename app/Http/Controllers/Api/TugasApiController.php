<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasApiController extends Controller
{
    public function storeapi(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string',
            'mata_kuliah' => 'required|string',
            'deadline' => 'required|date_format:Y-m-d',
            'prioritas' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $tugas = Tugas::create($validatedData);

        return response()->json(['message' => 'Tugas created successfully.', 'data' => $tugas], 201);
    }

    public function gettugasapi()
    {
        $tugas = Tugas::all();
        return response()->json(['message' => $tugas]);
    }

    public function deletetugasapi(Request $request)
    {
        $id = $request->id;
        $pesan = '';
        if (Tugas::destroy($id)) {
            $pesan = 'berhasil menghapus';
        } else {
            $pesan = 'Gagal menghapus';
            return response()->json(['message' => $pesan], 401);
        }

        return response()->json(['message' => $pesan], 201);
    }

    public function edittugasapi(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:tugas,id',
            'judul' => 'nullable|string',
            'mata_kuliah' => 'nullable|string',
            'deadline' => 'nullable|date_format:Y-m-d',
            'prioritas' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $id = $validatedData['id'];
        $tugas = Tugas::find($id);

        if (!$tugas) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 401);
        }

        unset($validatedData['id']);
        $tugas->update($validatedData);

        return response()->json(['message' => 'Tugas berhasil diperbarui', 'data' => $tugas], 200);
    }
}
