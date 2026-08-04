<?php

namespace App\Console\Commands;

use App\Services\GallerySyncService;
use Illuminate\Console\Command;

class SyncGalleryFromDrive extends Command
{
    protected $signature = 'galeri:sync';

    protected $description = 'Unduh semua foto galeri dari Google Drive ke storage lokal';

    public function handle(GallerySyncService $syncer): int
    {
        $this->info('Memulai sinkronisasi galeri dari Google Drive...');

        $result = $syncer->run();

        $this->info(sprintf(
            'Selesai. Total %d foto (baru: %d, sudah ada: %d, dihapus: %d).',
            $result['total'],
            $result['synced'],
            $result['skipped'],
            $result['removed']
        ));

        return self::SUCCESS;
    }
}
