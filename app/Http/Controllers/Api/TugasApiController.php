<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasApiController extends Controller
{
    public function index()
    {
        $tugas = Tugas::orderBy('deadline_tanggal')->get();

        // 'message' dipertahankan untuk bot WhatsApp lama
        return response()->json(['data' => $tugas, 'message' => $tugas]);
    }

    /**
     * Tambah / edit tugas. Body boleh 1 objek, array objek, atau {"data": [...]}.
     * Kolom sama dengan n8n Data Table: id, namatugas, penjelasan, deadline, deadline_tanggal.
     * "id" sudah ada = edit, "id" belum ada = tambah dengan id itu, tanpa "id" = tambah id baru.
     */
    public function simpan(Request $request)
    {
        $payload = $request->json()->all() ?: $request->all();
        $batch = array_is_list($payload) || isset($payload['data']);
        $rows = $batch ? ($payload['data'] ?? $payload) : [$payload];

        if (empty($rows)) {
            return response()->json(['message' => 'Body kosong, tidak ada tugas yang dikirim'], 422);
        }

        $results = array_map(fn ($row) => $this->simpanSatu((array) $row), $rows);

        if (! $batch) {
            $r = $results[0];
            return response()->json(
                $r['ok']
                    ? ['message' => $r['aksi'] === 'tambah' ? 'Tugas ditambahkan' : 'Tugas diperbarui', 'data' => $r['data']]
                    : ['message' => $r['message'], 'errors' => $r['errors']],
                $r['code']
            );
        }

        $gagal = count(array_filter($results, fn ($r) => ! $r['ok']));

        return response()->json([
            'message' => 'Berhasil: ' . (count($results) - $gagal) . ', Gagal: ' . $gagal,
            'results' => $results,
        ], $gagal === count($results) ? 422 : 200);
    }

    public function hapus(Request $request)
    {
        $id = $request->input('id', $request->input('n8n_id'));
        $tugas = $id ? Tugas::find($id) : null;

        if (! $tugas) {
            return response()->json(['message' => 'Tugas tidak ditemukan'], 404);
        }

        $tugas->delete();

        return response()->json(['message' => 'Tugas dihapus', 'data' => $tugas]);
    }

    private function simpanSatu(array $row): array
    {
        $data = $this->normalisasi($row);
        $tugas = isset($data['id']) ? Tugas::find($data['id']) : null;
        $baru = ! $tugas;

        $validator = Validator::make($data, [
            'id' => 'sometimes|integer|min:1',
            'namatugas' => ($baru ? 'required' : 'sometimes') . '|string|max:255',
            'penjelasan' => 'sometimes|nullable|string|max:65000',
            'deadline' => 'sometimes|nullable|string|max:255',
            'deadline_tanggal' => 'sometimes|nullable|date',
        ], [
            'deadline_tanggal.date' => 'deadline_tanggal harus tanggal, contoh 2026-10-11.',
        ]);

        if ($validator->fails()) {
            return ['ok' => false, 'code' => 422, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()];
        }

        $data = $validator->validated();
        if (isset($data['deadline_tanggal'])) {
            $data['deadline_tanggal'] = Carbon::parse($data['deadline_tanggal'])->format('Y-m-d');
        }

        if ($baru) {
            $tugas = Tugas::create($data);
        } else {
            $tugas->update($data);
        }

        return ['ok' => true, 'code' => $baru ? 201 : 200, 'aksi' => $baru ? 'tambah' : 'edit', 'data' => $tugas->fresh()];
    }

    /**
     * Nama kolom lama bot WhatsApp (judul, deskripsi, deadline berupa tanggal) tetap diterima.
     * Nilai null / "" diabaikan supaya edit sebagian tidak menimpa kolom.
     */
    private function normalisasi(array $row): array
    {
        $deadline = $row['deadline'] ?? null;
        $tanggal = $row['deadline_tanggal'] ?? null;

        // format lama: "deadline": "2026-10-11"
        if (! $tanggal && is_string($deadline) && preg_match('/^\d{4}-\d{2}-\d{2}/', $deadline)) {
            $tanggal = $deadline;
            $deadline = null;
        }

        $data = [
            'id' => $row['id'] ?? $row['n8n_id'] ?? null,
            'namatugas' => $row['namatugas'] ?? $row['judul'] ?? null,
            'penjelasan' => $row['penjelasan'] ?? $row['deskripsi'] ?? null,
            'deadline' => $deadline,
            'deadline_tanggal' => $tanggal,
        ];

        return array_filter($data, fn ($v) => $v !== null && $v !== '');
    }
}
