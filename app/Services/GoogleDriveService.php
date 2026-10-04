<?php

namespace App\Services;

use App\Exceptions\GoogleDriveNotConfiguredException;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private ?Drive $drive = null;

    /**
     * Lokasi file JSON service account Google Cloud.
     */
    public function credentialsPath(): string
    {
        $path = config('services.google_drive.service_account_path')
            ?? storage_path('app/google-drive/service-account.json');

        // Normalisasi ke path absolut — di konteks web (artisan serve) CWD adalah public/,
        // jadi path relatif harus di-resolve terhadap base_path() bukan CWD.
        if (! str_starts_with($path, DIRECTORY_SEPARATOR)
            && ! preg_match('/^[A-Za-z]:[\\\\\\/]/', $path)) {
            return base_path($path);
        }

        return $path;
    }

    /**
     * Apakah konfigurasi Google Drive sudah lengkap?
     */
    public function isConfigured(): bool
    {
        return is_file($this->credentialsPath())
            && filled(config('services.google_drive.folder_id'));
    }

    /**
     * ID folder Google Drive yang menjadi sumber galeri.
     */
    public function folderId(): ?string
    {
        return config('services.google_drive.folder_id') ?: null;
    }

    /**
     * Client Drive API (diinisialisasi secara lazy).
     */
    public function drive(): Drive
    {
        if ($this->drive instanceof Drive) {
            return $this->drive;
        }

        if (! is_file($this->credentialsPath())) {
            throw new GoogleDriveNotConfiguredException(
                'File kredensial service account tidak ditemukan: '.$this->credentialsPath()
            );
        }

        if (! filled($this->folderId())) {
            throw new GoogleDriveNotConfiguredException('GOOGLE_DRIVE_FOLDER_ID belum diisi di .env.');
        }

        $client = new Client;
        $client->setAuthConfig($this->credentialsPath());
        $client->addScope(Drive::DRIVE_READONLY);

        return $this->drive = new Drive($client);
    }

    /**
     * Daftar file gambar di folder galeri (hasilnya di-cache).
     *
     * @return array<int, array{id: string, name: string, mime_type: string, size: int, created_at: ?string, modified_at: ?string}>
     */
    public function listImages(int $ttl = 600, bool $fresh = false): array
    {
        if ($fresh) {
            return $this->fetchFiles();
        }

        return Cache::remember('galeri.drive.files', $ttl, fn () => $this->fetchFiles());
    }

    /**
     * Daftar file media (gambar + video) di folder galeri.
     * Dipakai halaman publik (thumbnail video tanpa unduh file besar) dan
     * GallerySyncService (unduh foto + siapkan MP4 video).
     *
     * @return array<int, array{id: string, name: string, mime_type: string, size: int, created_at: ?string, modified_at: ?string, thumbnail_link: ?string}>
     */
    public function listMedia(int $ttl = 600, bool $fresh = false): array
    {
        if ($fresh) {
            return $this->fetchMedia();
        }

        return Cache::remember('galeri.drive.media_files', $ttl, fn () => $this->fetchMedia());
    }

    /**
     * Hapus cache daftar file agar pencarian berikutnya selalu baru.
     */
    public function clearListCache(): void
    {
        Cache::forget('galeri.drive.files');
    }

    /**
     * Apakah MIME type adalah HEIF/HEIC (format iPhone yang tidak bisa dirender browser Windows/Android).
     */
    public static function isHeic(?string $mime): bool
    {
        return in_array($mime, ['image/heif', 'image/heic'], true);
    }

    /**
     * Unduh gambar; untuk HEIF/HEIC unduh thumbnail JPEG dari Drive
     * (browser tidak bisa menampilkan HEIF/HEIC mentah).
     */
    public function downloadImage(string $fileId, ?string $mime, string $destinationPath): bool
    {
        if (static::isHeic($mime)) {
            return $this->downloadThumbnail($fileId, $destinationPath);
        }

        return $this->downloadTo($fileId, $destinationPath);
    }

    /**
     * Unduh thumbnail JPEG yang digenerate Google Drive (bisa diunduh tanpa autentikasi).
     */
    public function downloadThumbnail(string $fileId, string $destinationPath): bool
    {
        try {
            if (! $this->ensureDestinationDirectory($destinationPath)) {
                return false;
            }

            $url = $this->thumbnailUrl($fileId);

            if (! $url) {
                return false;
            }

            $httpClient = $this->drive()->getClient()->getHttpClient();

            if (! is_object($httpClient) || ! method_exists($httpClient, 'request')) {
                return false;
            }

            $response = $httpClient->request('GET', $url, [
                'sink' => $destinationPath,
            ]);

            return is_object($response)
                && method_exists($response, 'getStatusCode')
                && $response->getStatusCode() === 200;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengunduh thumbnail Google Drive [{$fileId}]: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * URL thumbnail JPEG untuk sebuah file (thumbnailLink dari API, fallback endpoint publik).
     */
    public function thumbnailUrl(string $fileId): ?string
    {
        $link = $this->getMetadata($fileId)?->getThumbnailLink();

        return $this->thumbnailUrlFromLink($link, $fileId);
    }

    /**
     * Bangun URL thumbnail dari thumbnailLink yang sudah tersedia (mis. hasil
     * listMedia) tanpa request API tambahan. Fallback ke endpoint publik.
     */
    public function thumbnailUrlFromLink(?string $link, string $fileId): ?string
    {
        if ($link) {
            // Perbesar dari ukuran default s220 ke w1600.
            $resized = preg_replace('/=s\d+$/', '=w1600', $link);

            return is_string($resized) ? $resized : $link;
        }

        return 'https://drive.google.com/thumbnail?id='.urlencode($fileId).'&sz=w1600';
    }

    /**
     * Stream file content from Drive with optional forwarded headers (eg. Range).
     * Returns a PSR-7 response from the underlying HTTP client or null on error.
     */
    public function streamFile(string $fileId, array $forwardHeaders = [])
    {
        try {
            // Use the Drive SDK method to ensure authentication headers are applied.
            $params = ['alt' => 'media'];

            if (! empty($forwardHeaders)) {
                $params['headers'] = $forwardHeaders;
            }

            return $this->drive()->files->get($fileId, $params);
        } catch (\Throwable $e) {
            Log::warning("Gagal stream Google Drive [{$fileId}]: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Ambil metadata satu file dari Drive.
     */
    public function getMetadata(string $fileId): ?DriveFile
    {
        try {
            return $this->drive()->files->get($fileId, [
                'fields' => 'id,name,mimeType,size,thumbnailLink',
            ]);
        } catch (\Throwable $e) {
            Log::warning("Gagal membaca metadata Google Drive [{$fileId}]: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Unduh isi file langsung ke disk lokal (streaming, hemat memori).
     */
    public function downloadTo(string $fileId, string $destinationPath): bool
    {
        try {
            if (! $this->ensureDestinationDirectory($destinationPath)) {
                return false;
            }

            $response = $this->drive()->files->get($fileId, ['alt' => 'media']);

            if (! is_object($response) || ! method_exists($response, 'getBody')) {
                return false;
            }

            $stream = $response->getBody()->detach();
            $handle = fopen($destinationPath, 'wb');

            if ($stream && $handle) {
                stream_copy_to_stream($stream, $handle);
                fclose($handle);

                return true;
            }

            if ($handle) {
                fclose($handle);
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal mengunduh Google Drive [{$fileId}]: {$e->getMessage()}");
        }

        return false;
    }

    /**
     * Pastikan folder tujuan ada sebelum menulis file unduhan.
     */
    private function ensureDestinationDirectory(string $destinationPath): bool
    {
        $directory = dirname($destinationPath);

        if (is_dir($directory)) {
            return true;
        }

        if (@mkdir($directory, 0775, true)) {
            return true;
        }

        Log::warning("Gagal membuat folder tujuan galeri: {$directory}");

        return is_dir($directory);
    }

    /**
     * Peta MIME type gambar ke ekstensi file.
     */
    public static function extensionForMime(?string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'image/avif' => 'avif',
            'image/bmp' => 'bmp',
            'image/heic' => 'heic',
            'image/heif' => 'heif',
            default => 'jpg',
        };
    }

    /**
     * Ambil daftar file langsung dari API Drive (tanpa cache).
     */
    private function fetchFiles(): array
    {
        $files = [];
        $pageToken = null;

        do {
            $optParams = [
                'q' => sprintf(
                    "'%s' in parents and trashed = false and mimeType contains 'image/'",
                    $this->folderId()
                ),
                'fields' => 'nextPageToken, files(id, name, mimeType, size, createdTime, modifiedTime)',
                'pageSize' => 100,
                'orderBy' => 'createdTime desc',
            ];

            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }

            $result = $this->drive()->files->listFiles($optParams);

            foreach ($result->getFiles() as $file) {
                $files[] = [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => (int) $file->getSize(),
                    'created_at' => $file->getCreatedTime(),
                    'modified_at' => $file->getModifiedTime(),
                ];
            }

            $pageToken = $result->getNextPageToken();
        } while ($pageToken);

        return $files;
    }

    /**
     * Ambil daftar file gambar + video langsung dari API Drive (tanpa cache).
     */
    private function fetchMedia(): array
    {
        $files = [];
        $pageToken = null;

        do {
            $optParams = [
                'q' => sprintf(
                    "'%s' in parents and trashed = false and (mimeType contains 'image/' or mimeType contains 'video/')",
                    $this->folderId()
                ),
                'fields' => 'nextPageToken, files(id, name, mimeType, size, createdTime, modifiedTime, thumbnailLink)',
                'pageSize' => 100,
                'orderBy' => 'createdTime desc',
            ];

            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }

            $result = $this->drive()->files->listFiles($optParams);

            foreach ($result->getFiles() as $file) {
                $files[] = [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => (int) $file->getSize(),
                    'created_at' => $file->getCreatedTime(),
                    'modified_at' => $file->getModifiedTime(),
                    'thumbnail_link' => $file->getThumbnailLink(),
                ];
            }

            $pageToken = $result->getNextPageToken();
        } while ($pageToken);

        return $files;
    }
}
