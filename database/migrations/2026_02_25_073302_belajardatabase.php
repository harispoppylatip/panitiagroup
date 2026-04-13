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
        Schema::create('postingan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('postingan');
            $table->timestamp('tanggal_upload');
        });

        Schema::create('gambar', function (Blueprint $table) {
            $table->id();
            $table->string('detail');
            $table->string('gambar');
            $table->timestamps();
        });

        Schema::create('sikad', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            $table->text('Nim');
            $table->text('refresh_token');
            $table->text('access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postingan');
        Schema::dropIfExists('sikad');
        Schema::dropIfExists('gambar');
    }
};
