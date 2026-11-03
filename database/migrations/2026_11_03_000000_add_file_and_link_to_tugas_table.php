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
        Schema::table('tugas', function (Blueprint $table) {
            if (! Schema::hasColumn('tugas', 'link_materi')) {
                $table->text('link_materi')->nullable()->after('deadline_tanggal');
            }
            if (! Schema::hasColumn('tugas', 'file_path')) {
                $table->string('file_path', 500)->nullable()->after('link_materi');
            }
            if (! Schema::hasColumn('tugas', 'file_name')) {
                $table->string('file_name', 255)->nullable()->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('tugas', 'file_name')) {
                $columnsToDrop[] = 'file_name';
            }
            if (Schema::hasColumn('tugas', 'file_path')) {
                $columnsToDrop[] = 'file_path';
            }
            if (Schema::hasColumn('tugas', 'link_materi')) {
                $columnsToDrop[] = 'link_materi';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
