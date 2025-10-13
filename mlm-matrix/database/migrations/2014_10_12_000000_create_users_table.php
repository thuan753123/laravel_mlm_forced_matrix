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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 191)->unique();
            $table->string('password', 191);
            $table->string('role', 32)->default('member'); // admin, manager, agent, member
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('provider', 32)->nullable(); // local, google, facebook, apple
            $table->string('fullname', 191)->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('plan', 64)->nullable();
            $table->string('avatar_url', 512)->nullable();
            $table->string('phone_number', 32)->nullable();
            $table->boolean('active')->default(true);
            $table->string('referral_code', 32)->unique()->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->rememberToken();
            
            // Indexes
            $table->index('phone_number');
            $table->index('active');
            $table->index('last_login_time');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
            
            // Note: Check constraint for role validation is handled at application level
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};