<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GallerySyncService;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Cache;

class AdminGalleryController extends Controller
{
    /**
     * Halaman kelola galeri (info + sinkronisasi).
     */
    public function index(GoogleDriveService $drive)
    {
        $configured = $drive->isConfigured();
        $files = $configured ? $drive->listImages(60) : [];
        $syncedAt = Cache::get('galeri.synced_at');

        return view('admin.galeri.index', compact('configured', 'files', 'syncedAt'));
    }

    /**
     * Jalankan sinkronisasi foto dari Google Drive.
     */
    public function sync(GallerySyncService $syncer, GoogleDriveService $drive)
    {
        if (! $drive->isConfigured()) {
            return back()->with('error', 'Google Drive belum dikonfigurasi. Ikuti langkah setup di halaman ini.');
        }

        try {
            $result = $syncer->run();
        } catch (\Throwable $e) {
            return back()->with('error', 'Sinkronisasi gagal: '.$e->getMessage());
        }

        $message = sprintf(
            'Sinkronisasi selesai. Total %d foto (baru: %d, sudah ada: %d, dihapus: %d).',
            $result['total'],
            $result['synced'],
            $result['skipped'],
            $result['removed']
        );

        return back()->with('success', $message);
    }
}
