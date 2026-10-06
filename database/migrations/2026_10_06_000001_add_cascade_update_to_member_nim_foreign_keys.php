<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $foreignKeys = [
            'grubkas_info' => 'Nim_key',
            'grubkas' => 'user_nim',
            'grubkas_activity_logs' => 'user_nim',
        ];

        foreach ($foreignKeys as $table => $column) {
            $this->rebuildForeignKey($table, $column, true);
        }
    }

    public function down(): void
    {
        $foreignKeys = [
            'grubkas_info' => 'Nim_key',
            'grubkas' => 'user_nim',
            'grubkas_activity_logs' => 'user_nim',
        ];

        foreach ($foreignKeys as $table => $column) {
            $this->rebuildForeignKey($table, $column, false);
        }
    }

    private function rebuildForeignKey(string $table, string $column, bool $cascadeUpdate): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $constraint = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->where('REFERENCED_TABLE_NAME', 'datasikad')
            ->value('CONSTRAINT_NAME');

        Schema::table($table, function (Blueprint $blueprint) use ($column, $constraint, $cascadeUpdate) {
            if ($constraint) {
                $blueprint->dropForeign($constraint);
            }

            $foreign = $blueprint->foreign($column)
                ->references('Nim')
                ->on('datasikad')
                ->cascadeOnDelete();

            if ($cascadeUpdate) {
                $foreign->cascadeOnUpdate();
            }
        });
    }
};