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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receiver_user_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payer_user_id');
            $table->integer('level');
            $table->bigInteger('amount'); // in VND
            $table->string('currency', 8)->default('VND');
            $table->enum('status', ['pending', 'approved', 'void'])->default('pending');
            $table->unsignedBigInteger('cycle_id')->nullable();
            $table->string('idempotency_key', 64)->unique()->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('receiver_user_id');
            $table->index('order_id');
            $table->index('payer_user_id');
            $table->index('level');
            $table->index('status');
            $table->index('cycle_id');
            $table->index('created_at');
            
            // Foreign keys
            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payer_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cycle_id')->references('id')->on('cycles')->onDelete('set null');
            
            // Unique constraint for idempotency
            $table->unique(['order_id', 'receiver_user_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};