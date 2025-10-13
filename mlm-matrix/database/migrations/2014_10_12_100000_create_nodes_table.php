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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('position')->default(0);
            $table->integer('depth')->default(0);
            $table->unsignedBigInteger('_lft');
            $table->unsignedBigInteger('_rgt');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('depth');
            $table->index(['parent_id', 'position']);
            $table->index(['_lft', '_rgt']);
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
            
            // Unique constraint
            $table->unique(['parent_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};