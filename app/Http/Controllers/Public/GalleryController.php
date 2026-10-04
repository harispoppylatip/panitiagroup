<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\GoogleDriveService;
use App\Services\VideoConverterService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Halaman galeri publik.
     */
    public function index(GoogleDriveService $drive)
    {
        $files = [];
        $perPage = 50;

        if ($drive->isConfigured()) {
            try {
                // TTL pendek (60s) agar perubahan cepat terlihat.
                $files = $drive->listMedia(60);

                // Sertakan URL thumbnail untuk tiap item agar view bisa
                // menampilkan preview video tanpa mengunduh file besar.
                // thumbnailLink sudah tersedia dari hasil listMedia, jadi
                // tidak perlu request API metadata per file (N+1).
                foreach ($files as &$f) {
                    $f['thumb'] = $drive->thumbnailUrlFromLink(
                        $f['thumbnail_link'] ?? null,
                        $f['id']
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('Galeri: gagal membaca Google Drive - '.$e->getMessage());
                $files = [];
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $items = collect($files)->values();

        $files = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );

        return view('pages.galeri', compact('files'));
    }

    /**
     * Sajikan satu foto galeri.
     * Diprioritaskan dari cache lokal, jika belum ada diunduh dari Drive lalu di-cache.
     */
    public function photo(string $fileId, GoogleDriveService $drive)
    {
        $disk = Storage::disk('public');

        $localPath = $this->localPathFor($fileId);

        if (! $localPath) {
            $meta = $drive->getMetadata($fileId);

            if (! $meta) {
                abort(404);
            }

            $mime = $meta->getMimeType();
            // HEIF/HEIC disimpan sebagai .jpg (thumbnail JPEG dari Drive).
            $ext = GoogleDriveService::isHeic($mime)
                ? 'jpg'
                : GoogleDriveService::extensionForMime($mime);
            $localPath = "galeri/{$fileId}.{$ext}";

            if (! $drive->downloadImage($fileId, $mime, $disk->path($localPath))) {
                $thumbUrl = $drive->thumbnailUrl($fileId);

                if ($thumbUrl) {
                    return redirect()->away($thumbUrl);
                }

                abort(404);
            }

            Cache::forever("galeri.local.{$fileId}", $localPath);
        }

        // Stream langsung dari storage lokal (tidak redirect ke /storage URL
        // agar tidak bergantung pada APP_URL / symlink public/storage).
        return $disk->response($localPath);
    }

    /**
     * Sajikan video untuk diputar inline di browser.
     * Video non-MP4 (mis. MOV) dikonversi ke MP4 secara otomatis (ffmpeg)
     * pada permintaan pertama, lalu di-cache lokal agar permintaan berikutnya instan.
     */
    public function video(string $fileId, GoogleDriveService $drive, VideoConverterService $converter)
    {
        $meta = $drive->getMetadata($fileId);

        if (! $meta) {
            abort(404);
        }

        $mime = $meta->getMimeType() ?: 'application/octet-stream';

        // Normalnya MP4 sudah disiapkan `galeri:sync`; kalau belum, disiapkan di sini (fallback).
        $localPath = $converter->playablePath($fileId, $mime, $drive);

        if (! $localPath && $converter->sedangDiproses($fileId)) {
            return response('Video sedang diproses, coba lagi sebentar.', 503, ['Retry-After' => '60']);
        }

        if (! $localPath || ! Storage::disk('public')->exists($localPath)) {
            abort(404);
        }

        // Layani dari disk lokal memakai BinaryFileResponse Symfony yang
        // menangani Range request (206 + Content-Range) sehingga video bisa
        // di-seek dan diputar inline dengan benar.
        $absolutePath = Storage::disk('public')->path($localPath);

        return new \Symfony\Component\HttpFoundation\BinaryFileResponse($absolutePath, 200, [
            'Content-Type' => 'video/mp4',
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    /**
     * Cari file lokal yang sudah ter-sync untuk file Drive tertentu.
     */
    private function localPathFor(string $fileId): ?string
    {
        $cached = Cache::get("galeri.local.{$fileId}");

        if ($cached && Storage::disk('public')->exists($cached)) {
            return $cached;
        }

        $candidates = [
            "galeri/{$fileId}.jpg",
            "galeri/{$fileId}.png",
            "galeri/{$fileId}.webp",
            "galeri/{$fileId}.gif",
        ];

        foreach ($candidates as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                Cache::forever("galeri.local.{$fileId}", $candidate);

                return $candidate;
            }
        }

        return null;
    }
}
