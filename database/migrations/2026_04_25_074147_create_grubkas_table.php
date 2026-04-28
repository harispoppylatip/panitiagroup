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
        Schema::create('grubkas', function (Blueprint $table) {
            $table->id();
            $table->string('user_nim', 20);
            $table->text('Keterangan')->nullable();
            $table->integer('Nominal')->default('10000');
            $table->boolean('Status_Bayar')->default('0');
            $table->timestamps();

            $table->foreign('user_nim')->references('Nim')->on('datasikad')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grubkas');
    }
};
