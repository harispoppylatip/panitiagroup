<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('grubkas_dashboard')) {
            Schema::create('grubkas_dashboard', function (Blueprint $table) {
                $table->id();
                $table->integer('Iuran_Perminggu');
                $table->integer('Total_Saldo');
                $table->integer('Total_Masuk');
                $table->integer('Total_Keluar');
                $table->integer('Jumlah_belum_bayar');
                $table->integer('Jumlah_Sudah_bayar');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grubkas_dashboard');
    }
};
