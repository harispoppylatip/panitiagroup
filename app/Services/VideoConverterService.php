<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class VideoConverterService
{
    /**
     * Pastikan file video dapat diputar di browser.
     *
     * - Jika sudah ada MP4 lokal → langsung dipakai.
     * - Jika sumber dari Drive sudah MP4 → unduh sekali, layani langsung.
     * - Format lain (MOV, AVI, dll.) → unduh lalu konversi ke MP4 (H.264 + AAC)
     *   agar kompatibel dengan HTML5 <video> di semua browser.
     *
     * @return string|null Path relatif disk public ke file video, atau null bila gagal.
     */
    public function playablePath(string $fileId, string $mime, GoogleDriveService $drive): ?string
    {
        $disk = Storage::disk('public');
        $mp4Path = "galeri/{$fileId}.mp4";

        // Sudah pernah dikonversi?
        if ($disk->exists($mp4Path)) {
            Cache::forever("galeri.local.{$fileId}", $mp4Path);

            return $mp4Path;
        }

        // Sumber asli sudah MP4 → cukup unduh sekali.
        if (str_contains($mime, 'video/mp4')) {
            if (! $disk->exists($mp4Path) && ! $drive->downloadTo($fileId, $disk->path($mp4Path))) {
                return null;
            }

            Cache::forever("galeri.local.{$fileId}", $mp4Path);

            return $mp4Path;
        }

        // Format lain → cari file sumber lokal, unduh bila belum ada.
        $ext = $this->extensionForMime($mime);
        $srcPath = "galeri/{$fileId}-src.{$ext}";

        // Reuse file unduhan lama (nama tanpa -src) bila ada.
        if (! $disk->exists($srcPath)) {
            $legacy = "galeri/{$fileId}.{$ext}";

            if ($disk->exists($legacy)) {
                $srcPath = $legacy;
            } elseif (! $drive->downloadTo($fileId, $disk->path($srcPath))) {
                return null;
            }
        }

        if (! $this->convert($disk->path($srcPath), $disk->path($mp4Path))) {
            Log::warning("Konversi video gagal untuk {$fileId}; fallback ke file asli.");

            // Fallback: layani file sumber apa adanya (mungkin tidak terputar).
            Cache::forever("galeri.local.{$fileId}", $srcPath);

            return $srcPath;
        }

        // Hapus sumber sementara setelah konversi sukses (hemat penyimpanan).
        if (str_contains($srcPath, '-src.')) {
            $disk->delete($srcPath);
        }

        Cache::forever("galeri.local.{$fileId}", $mp4Path);

        return $mp4Path;
    }

    /**
     * Konversi video ke MP4 (H.264 + AAC, faststart) memakai ffmpeg.
     */
    private function convert(string $src, string $dst): bool
    {
        $bin = config('services.google_drive.ffmpeg_path') ?: 'ffmpeg';

        try {
            $process = new Process([
                $bin,
                '-y',
                '-i', $src,
                '-c:v', 'libx264',
                '-preset', 'veryfast',
                '-crf', '23',
                '-pix_fmt', 'yuv420p',
                '-c:a', 'aac',
                '-b:a', '128k',
                '-movflags', '+faststart',
                '-threads', '0',
                $dst,
            ]);
            $process->setTimeout(600);
            $process->run();

            if (! $process->isSuccessful()) {
                Log::warning('ffmpeg error: '.$process->getErrorOutput());

                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal menjalankan ffmpeg: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Peta MIME video ke ekstensi sumber.
     */
    private function extensionForMime(?string $mime): string
    {
        return match ($mime) {
            'video/mp4' => 'mp4',
            'video/x-msvideo' => 'avi',
            'video/webm' => 'webm',
            'video/x-matroska' => 'mkv',
            'video/mpeg' => 'mpeg',
            'video/3gpp' => '3gp',
            'video/ogg' => 'ogv',
            'video/quicktime' => 'mov',
            default => 'mov',
        };
    }
}
