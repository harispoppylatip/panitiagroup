<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Samakan tabel tugas dengan n8n Data Table "tugas":
     * id, namatugas, penjelasan, deadline (teks), deadline_tanggal (tanggal), created_at, updated_at.
     * Data lama tetap: judul -> namatugas, deskripsi -> penjelasan, deadline -> deadline_tanggal.
     */
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->renameColumn('judul', 'namatugas');
            $table->renameColumn('deskripsi', 'penjelasan');
            $table->renameColumn('deadline', 'deadline_tanggal');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->text('penjelasan')->nullable()->change();
            $table->date('deadline_tanggal')->nullable()->change();
            $table->string('deadline')->nullable()->after('penjelasan');
            $table->dropColumn(['mata_kuliah', 'status', 'prioritas']);
        });
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('deadline');
            $table->string('mata_kuliah')->default('-');
            $table->string('status')->default('Belum Dikerjakan');
            $table->string('prioritas')->default('Sedang');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->renameColumn('namatugas', 'judul');
            $table->renameColumn('penjelasan', 'deskripsi');
            $table->renameColumn('deadline_tanggal', 'deadline');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->string('deskripsi')->nullable()->change();
            $table->string('deadline')->nullable()->change();
        });
    }
};
