<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel log ikut ter-drop di 2026_05_20_000001_drop_payment_tables padahal
     * FinanceController & tagihan mingguan masih menulis ke sini (diam-diam dilewati).
     */
    public function up(): void
    {
        if (Schema::hasTable('grubkas_activity_logs')) {
            return;
        }

        Schema::create('grubkas_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_nim', 20)->nullable()->index();
            $table->string('user_name', 100)->nullable();
            $table->string('activity_type', 30)->default('payment');
            $table->string('direction', 10)->default('in');
            $table->integer('amount')->default(0);
            $table->string('title', 150);
            $table->string('description', 255)->nullable();
            $table->string('order_id', 80)->nullable();
            $table->string('transaction_status', 30)->nullable();
            $table->string('proof_path', 255)->nullable();
            $table->string('proof_name', 255)->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grubkas_activity_logs');
    }
};
