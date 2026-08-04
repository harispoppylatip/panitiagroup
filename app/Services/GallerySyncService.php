<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GallerySyncService
{
    public function __construct(private readonly GoogleDriveService $drive) {}

    /**
     * Sinkronisasi semua foto dari Google Drive ke storage lokal (public/galeri).
     *
     * @return array{total: int, synced: int, skipped: int, removed: int}
     */
    public function run(): array
    {
        $this->drive->clearListCache();
        $files = $this->drive->listImages(600, fresh: true);

        $disk = Storage::disk('public');
        $synced = 0;
        $skipped = 0;

        foreach ($files as $file) {
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

        // Hapus file lokal yang tidak lagi ada di folder Drive.
        // File dot (mis. .gitignore) tidak dihitung sebagai file galeri — dilindungi.
        $valid = collect($files)->map(fn (array $file) => $this->localPathFor($file))->all();
        $removed = 0;

        foreach ($disk->files('galeri') as $existing) {
            if (str_starts_with(basename($existing), '.')) {
                continue;
            }

            if (! in_array($existing, $valid, true)) {
                $disk->delete($existing);
                $removed++;
            }
        }

        Cache::put('galeri.synced_at', now()->toISOString());

        return [
            'total' => count($files),
            'synced' => $synced,
            'skipped' => $skipped,
            'removed' => $removed,
        ];
    }

    /**
     * Path lokal relatif disk public untuk sebuah file Drive.
     * HEIF/HEIC disimpan sebagai .jpg karena isinya thumbnail JPEG (bukan file mentah HEIF).
     */
    public function localPathFor(array $file): string
    {
        $ext = GoogleDriveService::isHeic($file['mime_type'])
            ? 'jpg'
            : GoogleDriveService::extensionForMime($file['mime_type']);

        return 'galeri/'.$file['id'].'.'.$ext;
    }
}
