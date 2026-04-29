<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'akuntan', 'anggota', 'scanabsen') NOT NULL DEFAULT 'anggota'");
        DB::statement("UPDATE users SET role = 'anggota' WHERE role = 'scanabsen'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'akuntan', 'anggota') NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'akuntan', 'anggota', 'scanabsen') NOT NULL DEFAULT 'anggota'");
        DB::statement("UPDATE users SET role = 'scanabsen' WHERE role = 'anggota'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'scanabsen') NOT NULL DEFAULT 'scanabsen'");
    }
};