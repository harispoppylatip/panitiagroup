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
        Schema::table('grubkas_dashboard', function (Blueprint $table) {
            $table->string('key')->unique()->nullable()->after('id');
            $table->text('value')->nullable()->after('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grubkas_dashboard', function (Blueprint $table) {
            $table->dropColumn(['key', 'value']);
        });
    }
};
