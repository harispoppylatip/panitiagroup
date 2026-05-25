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
        if (!Schema::hasTable('Status_Pembayaran')) {
            Schema::create('Status_Pembayaran', function (Blueprint $table) {
                $table->id();
                $table->integer('Status_id')->unique();
                $table->text('Status');
                $table->timestamps();
            });
        } else {
            Schema::table('Status_Pembayaran', function (Blueprint $table) {
                $table->unique('Status_id');
            });
        }

        if (Schema::hasTable('grubkas_info')) {
            Schema::table('grubkas_info', function (Blueprint $table) {
                $table->foreign('Status_Pembayaran')
                    ->references('Status_id')
                    ->on('Status_Pembayaran')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('grubkas_info')) {
            Schema::table('grubkas_info', function (Blueprint $table) {
                $table->dropForeign(['Status_Pembayaran']);
            });
        }

        Schema::dropIfExists('Status_Pembayaran');
    }
};
