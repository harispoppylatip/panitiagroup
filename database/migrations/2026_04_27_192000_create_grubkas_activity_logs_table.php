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
        Schema::create('grubkas_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_nim', 20)->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('activity_type', 30)->default('payment');
            $table->string('direction', 10)->default('in');
            $table->integer('amount')->default(0);
            $table->string('title', 150);
            $table->string('description', 255)->nullable();
            $table->string('order_id', 80)->nullable()->unique();
            $table->string('transaction_status', 30)->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();

            $table->foreign('user_nim')->references('Nim')->on('datasikad')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grubkas_activity_logs');
    }
};
