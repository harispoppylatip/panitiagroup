<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finance_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('finance_settings', 'auto_weekly_enabled')) {
                $table->boolean('auto_weekly_enabled')->default(true)->after('weekly_fee');
            }
            if (!Schema::hasColumn('finance_settings', 'default_weekly_description')) {
                $table->string('default_weekly_description')->nullable()->after('auto_weekly_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('finance_settings', function (Blueprint $table) {
            if (Schema::hasColumn('finance_settings', 'default_weekly_description')) {
                $table->dropColumn('default_weekly_description');
            }
            if (Schema::hasColumn('finance_settings', 'auto_weekly_enabled')) {
                $table->dropColumn('auto_weekly_enabled');
            }
        });
    }
};
