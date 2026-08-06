<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('grubkas_info')) {
            Schema::table('grubkas_info', function (Blueprint $table) {
                if (!Schema::hasColumn('grubkas_info', 'order_id')) {
                    $table->string('order_id', 100)->nullable()->after('Tanggal_Pembayaran');
                }

                if (!Schema::hasColumn('grubkas_info', 'link_code')) {
                    $table->string('link_code', 50)->nullable()->after('order_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('grubkas_info')) {
            Schema::table('grubkas_info', function (Blueprint $table) {
                if (Schema::hasColumn('grubkas_info', 'link_code')) {
                    $table->dropColumn('link_code');
                }

                if (Schema::hasColumn('grubkas_info', 'order_id')) {
                    $table->dropColumn('order_id');
                }
            });
        }
    }
};
