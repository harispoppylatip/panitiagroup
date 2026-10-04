<?php

namespace App\Console\Commands;

use App\Services\GallerySyncService;
use App\Services\GoogleDriveService;
use Illuminate\Console\Command;

class SyncGalleryFromDrive extends Command
{
    protected $signature = 'galeri:sync {--tanpa-video : Hanya foto, lewati unduh/konversi video}';

    protected $description = 'Unduh foto galeri dari Google Drive dan siapkan video (MOV → MP4) ke storage lokal';

    public function handle(GallerySyncService $syncer, GoogleDriveService $drive): int
    {
        if (! $drive->isConfigured()) {
            $this->warn('Google Drive belum dikonfigurasi, sinkronisasi dilewati.');

            return self::SUCCESS;
        }

        $this->info('Memulai sinkronisasi galeri dari Google Drive...');

        $result = $syncer->run(! $this->option('tanpa-video'));

        $this->info(sprintf(
            'Foto: total %d (baru: %d, sudah ada: %d). Video: total %d (siap: %d, baru dikonversi: %d, gagal: %d). Dihapus: %d.',
            $result['total'],
            $result['synced'],
            $result['skipped'],
            $result['videos'],
            $result['video_ready'],
            $result['video_new'],
            $result['video_failed'],
            $result['removed']
        ));

        return self::SUCCESS;
    }
}
