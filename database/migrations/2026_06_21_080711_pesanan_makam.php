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
        Schema::create('makamorders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('makam_type_id')->constrained('makam_types')->onDelete('cascade');
            $table->string('nama_customer');
            $table->string('email_customer')->nullable();
            $table->string('no_wa_customer');
            $table->text('alamat_customer')->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status', ['baru', 'diproses', 'selesai', 'dibatalkan'])->default('baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makamorders');
    }
};
