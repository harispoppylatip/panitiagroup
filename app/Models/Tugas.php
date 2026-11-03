<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    // id boleh diisi: id baris n8n Data Table dipakai langsung sebagai id web
    protected $fillable = [
        'id',
        'namatugas',
        'penjelasan',
        'deadline',
        'deadline_tanggal',
        'link_materi',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'deadline_tanggal' => 'date:Y-m-d',
    ];

    /**
     * URL lengkap ke file lampiran tugas (bila ada).
     */
    public function getFileUrlAttribute(): ?string
    {
        if (blank($this->file_path)) {
            return null;
        }

        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return url('/' . ltrim($this->file_path, '/'));
    }

    /**
     * URL tautan materi / referensi tugas (bila ada).
     */
    public function getLinkMateriUrlAttribute(): ?string
    {
        if (blank($this->link_materi)) {
            return null;
        }

        $url = trim($this->link_materi);
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return 'https://' . ltrim($url, '/');
    }

    /**
     * Ekstensi file lampiran (lowercase tanpa dot).
     */
    public function getFileExtensionAttribute(): ?string
    {
        $target = $this->file_name ?: $this->file_path;
        if (! $target) {
            return null;
        }

        $path = parse_url($target, PHP_URL_PATH);
        $ext = pathinfo($path ?? $target, PATHINFO_EXTENSION);

        return $ext ? strtolower($ext) : null;
    }

    /**
     * Icon bootstrap yang relevan berdasarkan tipe file.
     */
    public function getFileIconClassAttribute(): string
    {
        return match ($this->file_extension) {
            'pdf' => 'bi-file-earmark-pdf',
            'doc', 'docx' => 'bi-file-earmark-word',
            'xls', 'xlsx' => 'bi-file-earmark-excel',
            'ppt', 'pptx' => 'bi-file-earmark-ppt',
            'zip', 'rar', '7z', 'tar', 'gz' => 'bi-file-earmark-zip',
            'png', 'jpg', 'jpeg', 'webp', 'gif' => 'bi-file-earmark-image',
            'mp4', 'mkv', 'avi' => 'bi-file-earmark-play',
            'mp3', 'ogg', 'wav' => 'bi-file-earmark-music',
            default => 'bi-paperclip',
        };
    }
}
