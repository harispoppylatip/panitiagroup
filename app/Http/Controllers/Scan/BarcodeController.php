<?php

namespace App\Http\Controllers\Scan;

use App\Http\Controllers\Controller;
use App\Models\Datasikadmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BarcodeController extends Controller
{
    private const SCAN_FIELDS = [
        'tahun',
        'semester',
        'id_makul',
        'id_kelas',
        'id_kampus',
        'id_grup',
        'id_pertemuan',
        'id_tanggal',
        'id_sesi',
        'token',
    ];

    public function submitScan(Request $request)
    {
        $validated = $request->validate(array_fill_keys(self::SCAN_FIELDS, 'required'));
        $payload = array_map('strval', $validated);

        $users = Datasikadmodel::query()
            ->select('id', 'nama', 'Nim', 'access_token', 'status_onoff')
            ->where('status_onoff', 'on')
            ->whereNotNull('Nim')
            ->where('Nim', '!=', '')
            ->whereNotNull('access_token')
            ->where('access_token', '!=', '')
            ->orderBy('nama')
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'results' => [],
                'summary' => [
                    'success' => 0,
                    'failed' => 0,
                    'message' => 'Tidak ada user dengan status on.',
                ],
            ]);
        }

        $results = [];
        $successCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            $nim = trim((string) $user->Nim);
            $token = trim((string) $user->access_token);

            if ($nim === '' || $token === '') {
                $results[] = [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'nim' => $user->Nim,
                    'success' => false,
                    'status' => 'gagal',
                    'api_message' => 'NIM atau token akses tidak lengkap.',
                    'http_status' => 0,
                    'response' => 'Validasi data lokal gagal',
                ];

                $failedCount++;
                continue;
            }

            $endpoint = 'https://mahasiswa.umkt.ac.id/v0/mahasiswa/' . $nim . '/presensi-kuliah/qr-code';

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->withToken($token)->post($endpoint, $payload);

            $responseBody = $response->json();
            if ($responseBody === null) {
                $responseBody = $response->body();
            }

            $httpStatus = $response->status();

            if (is_string($responseBody)) {
                $apiMessage = trim(strip_tags($responseBody));
                if ($apiMessage === '') {
                    $apiMessage = match ($httpStatus) {
                        401 => 'Token akses tidak valid atau sudah kedaluwarsa.',
                        403 => 'Akses ditolak oleh server presensi.',
                        404 => 'QR expired atau data presensi tidak ditemukan.',
                        422 => 'Format data QR tidak sesuai.',
                        500, 502, 503, 504 => 'Server presensi sedang bermasalah, coba lagi.',
                        default => 'Tidak ada pesan dari API',
                    };
                }
            } elseif (!is_array($responseBody)) {
                $apiMessage = 'Respons tidak terduga dari API';
            } else {
                $apiMessage = $responseBody['message'] ?? json_encode($responseBody);
            }

            $results[] = [
                'id' => $user->id,
                'nama' => $user->nama,
                'nim' => $user->Nim,
                'success' => $response->successful(),
                'status' => $response->successful() ? 'berhasil' : 'gagal',
                'api_message' => $apiMessage,
                'http_status' => $httpStatus,
                'response' => is_array($responseBody) ? $responseBody : ['raw' => $responseBody],
            ];

            if ($response->successful()) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return response()->json([
            'results' => $results,
            'summary' => [
                'success' => $successCount,
                'failed' => $failedCount,
                'message' => "Berhasil: {$successCount}, Gagal: {$failedCount}",
            ],
        ]);
    }
}
