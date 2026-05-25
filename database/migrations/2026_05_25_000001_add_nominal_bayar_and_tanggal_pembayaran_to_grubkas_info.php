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
                if (!Schema::hasColumn('grubkas_info', 'Nominal_Bayar')) {
                    $table->integer('Nominal_Bayar')->default(0)->after('Bukti_Pembayaran');
                }

                if (!Schema::hasColumn('grubkas_info', 'Tanggal_Pembayaran')) {
                    $table->date('Tanggal_Pembayaran')->nullable()->after('Nominal_Bayar');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('grubkas_info')) {
            Schema::table('grubkas_info', function (Blueprint $table) {
                if (Schema::hasColumn('grubkas_info', 'Tanggal_Pembayaran')) {
                    $table->dropColumn('Tanggal_Pembayaran');
                }

                if (Schema::hasColumn('grubkas_info', 'Nominal_Bayar')) {
                    $table->dropColumn('Nominal_Bayar');
                }
            });
        }
    }
};