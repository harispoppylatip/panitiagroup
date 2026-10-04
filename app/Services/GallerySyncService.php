<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GallerySyncService
{
    public function __construct(
        private readonly GoogleDriveService $drive,
        private readonly VideoConverterService $converter,
    ) {}

    /**
     * Sinkronisasi foto (dan video bila $denganVideo) dari Google Drive ke storage lokal (public/galeri).
     * Video non-MP4 (MOV iPhone) langsung dikonversi ke MP4 di sini, supaya pengunjung tidak menunggu.
     * Konversi bisa lama → jalankan dengan video hanya dari CLI (`galeri:sync`), bukan dari request web.
     *
     * @return array{total: int, synced: int, skipped: int, removed: int, videos: int, video_ready: int, video_new: int, video_failed: int}
     */
    public function run(bool $denganVideo = true): array
    {
        $this->drive->clearListCache();
        $media = $this->drive->listMedia(600, fresh: true);
        Cache::forget('galeri.drive.media_files');

        $images = array_values(array_filter($media, fn (array $f) => ! $this->isVideo($f)));
        $videos = array_values(array_filter($media, fn (array $f) => $this->isVideo($f)));

        $disk = Storage::disk('public');
        $synced = 0;
        $skipped = 0;

        foreach ($images as $file) {
            $path = $this->localPathFor($file);

            if ($disk->exists($path)) {
                $skipped++;

                continue;
            }

            if ($this->drive->downloadImage($file['id'], $file['mime_type'], $disk->path($path))) {
                Cache::forever("galeri.local.{$file['id']}", $path);
                $synced++;
            }
        }

        $videoReady = 0;
        $videoNew = 0;
        $videoFailed = 0;

        foreach ($videos as $file) {
            if ($disk->exists($this->localPathFor($file))) {
                $videoReady++;

                continue;
            }

            if (! $denganVideo) {
                continue;
            }

            $hasil = $this->converter->playablePath($file['id'], $file['mime_type'], $this->drive);

            if ($hasil && str_ends_with($hasil, '.mp4')) {
                $videoNew++;
            } else {
                $videoFailed++;
                Log::warning("Galeri sync: video {$file['id']} ({$file['name']}) belum bisa disiapkan.");
            }
        }

        // Hapus file lokal yang tidak lagi ada di folder Drive.
        // File dot (mis. .gitignore) tidak dihitung sebagai file galeri — dilindungi.
        $valid = collect($media)->map(fn (array $file) => $this->localPathFor($file))->all();
        $idValid = collect($media)->pluck('id')->all();
        $removed = 0;

        foreach ($disk->files('galeri') as $existing) {
            $nama = basename($existing);

            if (str_starts_with($nama, '.') || in_array($existing, $valid, true)) {
                continue;
            }

            // File kerja video (-src / .part) milik video yang masih ada: jangan diganggu
            // selama baru (< 1 jam), bisa jadi sedang dikonversi proses lain.
            $id = preg_replace('/(-src)?(\.part)?\.[^.]+$/', '', $nama);
            if (in_array($id, $idValid, true) && $disk->lastModified($existing) > now()->subHour()->timestamp) {
                continue;
            }

            $disk->delete($existing);
            $removed++;
        }

        Cache::put('galeri.synced_at', now()->toISOString());

        return [
            'total' => count($images),
            'synced' => $synced,
            'skipped' => $skipped,
            'removed' => $removed,
            'videos' => count($videos),
            'video_ready' => $videoReady,
            'video_new' => $videoNew,
            'video_failed' => $videoFailed,
        ];
    }

    /**
     * Path lokal relatif disk public untuk sebuah file Drive.
     * HEIF/HEIC disimpan sebagai .jpg karena isinya thumbnail JPEG (bukan file mentah HEIF).
     * Video selalu disimpan sebagai MP4 hasil konversi.
     */
    public function localPathFor(array $file): string
    {
        if ($this->isVideo($file)) {
            return VideoConverterService::mp4PathFor($file['id']);
        }

        $ext = GoogleDriveService::isHeic($file['mime_type'])
            ? 'jpg'
            : GoogleDriveService::extensionForMime($file['mime_type']);

        return 'galeri/'.$file['id'].'.'.$ext;
    }

    private function isVideo(array $file): bool
    {
        return str_starts_with((string) ($file['mime_type'] ?? ''), 'video/');
    }
}
