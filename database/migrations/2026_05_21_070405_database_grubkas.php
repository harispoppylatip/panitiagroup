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
        Schema::create('grubkas_info', function (Blueprint $table) {
            $table->id();
            $table->string('Nim_key', 20);
            $table->foreign('Nim_key')->references('Nim')->on('datasikad')->cascadeOnDelete();
            $table->integer('Saldo_Lebih')->default(0);
            $table->integer('Utang_Anggota')->default(0);
            $table->text('Keterangan')->nullable();
            $table->text('Bukti_Pembayaran')->nullable();
            $table->integer('Status_Pembayaran')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grubkas_info');
    }
};
