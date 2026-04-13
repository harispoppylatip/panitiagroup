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
        Schema::create('datasikad', function (Blueprint $table) {
            $table->id();
            $table->text('nama');
            $table->text('Nim');
            $table->text('access_token');
            $table->text('refresh_token');
            $table->text('status_onoff')->nullable();
            $table->text('urlpost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasikad');
    }
};
