<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom foto hero khusus tampilan mobile.
     * Jika kosong, tampilan mobile otomatis memakai foto desktop (image_url).
     */
    public function up(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->string('image_url_mobile')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropColumn('image_url_mobile');
        });
    }
};
