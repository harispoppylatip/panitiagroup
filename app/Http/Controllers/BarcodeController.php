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
                $apiMessage = match ($httpStatus) {
                    401 => 'Token akses tidak valid atau sudah kedaluwarsa.',
                    403 => 'Akses ditolak oleh server presensi.',
                    404 => 'QR expired atau data presensi tidak ditemukan.',
                    422 => 'Format data QR tidak sesuai.',
                    500, 502, 503, 504 => 'Server presensi sedang bermasalah, coba lagi.',
                    default => 'Tidak ada pesan dari API',
                };
            } else {
                $apiMessage = '';

                foreach (['message', 'pesan', 'detail', 'error', 'msg'] as $key) {
                    if (isset($responseBody[$key]) && is_string($responseBody[$key]) && trim($responseBody[$key]) !== '') {
                        $apiMessage = trim($responseBody[$key]);
                        break;
                    }
                }

                if ($apiMessage === '' && isset($responseBody['errors']) && is_array($responseBody['errors'])) {
                    foreach ($responseBody['errors'] as $value) {
                        if (is_array($value) && isset($value[0]) && is_string($value[0])) {
                            $apiMessage = trim($value[0]);
                            break;
                        }

                        if (is_string($value) && trim($value) !== '') {
                            $apiMessage = trim($value);
                            break;
                        }
                    }
                }

                if ($apiMessage === '') {
                    foreach ($responseBody as $value) {
                        if (is_string($value) && trim($value) !== '') {
                            $apiMessage = trim($value);
                            break;
                        }

                        if (is_array($value)) {
                            foreach ($value as $nestedValue) {
                                if (is_string($nestedValue) && trim($nestedValue) !== '') {
                                    $apiMessage = trim($nestedValue);
                                    break 2;
                                }
                            }
                        }
                    }
                }

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
            }

            $apiSuccess = $response->successful();

            if (is_array($responseBody)) {
                if (array_key_exists('success', $responseBody)) {
                    $apiSuccess = (bool) $responseBody['success'];
                }

                if (isset($responseBody['status']) && is_string($responseBody['status'])) {
                    $statusLower = strtolower($responseBody['status']);

                    if (str_contains($statusLower, 'gagal') || str_contains($statusLower, 'error')) {
                        $apiSuccess = false;
                    }

                    if (str_contains($statusLower, 'berhasil') || str_contains($statusLower, 'success')) {
                        $apiSuccess = true;
                    }
                }
            }

            $messageLower = strtolower($apiMessage);
            if (str_contains($messageLower, 'expired') || str_contains($messageLower, 'invalid') || str_contains($messageLower, 'gagal') || str_contains($messageLower, 'error')) {
                $apiSuccess = false;
            }

            $results[] = [
                'id' => $user->id,
                'nama' => $user->nama,
                'nim' => $user->Nim,
                'success' => $apiSuccess,
                'status' => $apiSuccess ? 'berhasil' : 'gagal',
                'api_message' => $apiMessage,
                'http_status' => $httpStatus,
                'response' => $responseBody,
            ];

            // Kirim notifikasi WhatsApp jika API dikonfigurasi
            if (config('api.whatsapp_api') && !empty(config('api.whatsapp_api'))) {
                Http::withBasicAuth(config('api.whatsapp_username'), config('api.Whatsapp_password'))->post(config('api.whatsapp_api').'send/message', [
                    "phone" => "120363332274172697@g.us",
                    "message" => "Nama: {$user->nama}\nstatus: " . ($apiSuccess ? '✅berhasil' : '🚫gagal') . "\nKeterangan: {$apiMessage} \n \n",
                ]);
            }


            if ($apiSuccess) {
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
}
