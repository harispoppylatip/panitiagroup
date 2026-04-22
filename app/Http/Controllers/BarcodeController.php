<?php

namespace App\Http\Controllers;

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
        $payload = $this->buildScanPayload($request);
        $users = $this->getActiveScannerUsers();

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
            $result = $this->scanForUser($user, $payload);
            $results[] = $result;

            if ($result['success']) {
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
            ],
        ]);
    }

    private function buildScanPayload(Request $request): array
    {
        $validated = $request->validate(array_fill_keys(self::SCAN_FIELDS, 'required'));

        return array_map('strval', $validated);
    }

    private function getActiveScannerUsers()
    {
        return Datasikadmodel::query()
            ->select('id', 'nama', 'Nim', 'access_token', 'status_onoff')
            ->where('status_onoff', 'on')
            ->whereNotNull('Nim')
            ->where('Nim', '!=', '')
            ->whereNotNull('access_token')
            ->where('access_token', '!=', '')
            ->orderBy('nama')
            ->get();
    }

    private function scanForUser(Datasikadmodel $user, array $payload): array
    {
        $nim = trim((string) $user->Nim);
        $token = trim((string) $user->access_token);

        if ($nim === '' || $token === '') {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'nim' => $user->Nim,
                'success' => false,
                'status' => 'gagal',
                'api_message' => 'NIM atau token akses tidak lengkap.',
                'http_status' => 0,
                'response' => 'Validasi data lokal gagal',
            ];
        }

        $response = $this->sendScanRequest($nim, $token, $payload);

        $responseBody = $response->json();
        if ($responseBody === null) {
            $responseBody = $response->body();
        }

        $httpStatus = $response->status();
        $apiMessage = $this->extractApiMessage($responseBody, $httpStatus);
        $apiSuccess = $this->isApiSuccess($response, $responseBody, $apiMessage);

        return [
            'id' => $user->id,
            'nama' => $user->nama,
            'nim' => $user->Nim,
            'success' => $apiSuccess,
            'status' => $apiSuccess ? 'berhasil' : 'gagal',
            'api_message' => $apiMessage,
            'http_status' => $httpStatus,
            'response' => $responseBody,
        ];
    }

    private function sendScanRequest(string $nim, string $token, array $payload)
    {
        $endpoint = $this->buildScanEndpoint($nim);

        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->withToken($token)->post($endpoint, $payload);
    }

    private function buildScanEndpoint(string $nim): string
    {
        return 'https://mahasiswa.umkt.ac.id/v0/mahasiswa/' . $nim . '/presensi-kuliah/qr-code';
    }

    private function isApiSuccess($response, $responseBody, string $apiMessage): bool
    {
        $success = $response->successful();

        if (is_array($responseBody)) {
            if (array_key_exists('success', $responseBody)) {
                $success = (bool) $responseBody['success'];
            }

            if (isset($responseBody['status']) && is_string($responseBody['status'])) {
                $statusLower = strtolower($responseBody['status']);

                if (str_contains($statusLower, 'gagal') || str_contains($statusLower, 'error')) {
                    $success = false;
                }

                if (str_contains($statusLower, 'berhasil') || str_contains($statusLower, 'success')) {
                    $success = true;
                }
            }
        }

        $messageLower = strtolower($apiMessage);
        if (str_contains($messageLower, 'expired') || str_contains($messageLower, 'invalid') || str_contains($messageLower, 'gagal') || str_contains($messageLower, 'error')) {
            $success = false;
        }

        return $success;
    }

    private function extractApiMessage($responseBody, int $httpStatus = 200): string
    {
        if (is_string($responseBody)) {
            $text = trim(strip_tags($responseBody));

            return $text !== '' ? $text : $this->fallbackMessageByStatus($httpStatus);
        }

        if (!is_array($responseBody)) {
            return $this->fallbackMessageByStatus($httpStatus);
        }

        foreach (['message', 'pesan', 'detail', 'error', 'msg'] as $key) {
            if (isset($responseBody[$key]) && is_string($responseBody[$key]) && trim($responseBody[$key]) !== '') {
                return trim($responseBody[$key]);
            }
        }

        if (isset($responseBody['errors']) && is_array($responseBody['errors'])) {
            foreach ($responseBody['errors'] as $value) {
                if (is_array($value) && isset($value[0]) && is_string($value[0])) {
                    return trim($value[0]);
                }

                if (is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
        }

        foreach ($responseBody as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }

            if (is_array($value)) {
                $nested = $this->extractApiMessage($value, $httpStatus);
                if ($nested !== $this->fallbackMessageByStatus($httpStatus)) {
                    return $nested;
                }
            }
        }

        return $this->fallbackMessageByStatus($httpStatus);
    }

    private function fallbackMessageByStatus(int $httpStatus): string
    {
        return match ($httpStatus) {
            401 => 'Token akses tidak valid atau sudah kedaluwarsa.',
            403 => 'Akses ditolak oleh server presensi.',
            404 => 'QR expired atau data presensi tidak ditemukan.',
            422 => 'Format data QR tidak sesuai.',
            500, 502, 503, 504 => 'Server presensi sedang bermasalah, coba lagi.',
            default => 'Tidak ada pesan dari API',
        };
    }
}
