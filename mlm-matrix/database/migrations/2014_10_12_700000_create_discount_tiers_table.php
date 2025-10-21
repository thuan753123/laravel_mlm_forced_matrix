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
        Schema::create('discount_tiers', function (Blueprint $table) {
            $table->id();
            $table->integer('tier')->comment('Bậc chiết khấu');
            $table->decimal('revenue_from', 15, 2)->comment('Doanh thu từ (triệu đồng)');
            $table->decimal('revenue_to', 15, 2)->nullable()->comment('Doanh thu đến (triệu đồng)');
            $table->decimal('applicable_revenue_from', 15, 2)->comment('Doanh thu áp dụng từ (triệu đồng)');
            $table->decimal('applicable_revenue_to', 15, 2)->nullable()->comment('Doanh thu áp dụng đến (triệu đồng)');
            $table->decimal('discount_rate', 5, 2)->comment('Tỷ lệ chiết khấu (%)');
            $table->string('tier_name')->comment('Tên bậc (NPP mới, NPP tiêu chuẩn, etc.)');
            $table->text('note')->nullable()->comment('Ghi chú');
            $table->boolean('is_active')->default(true)->comment('Trạng thái kích hoạt');
            $table->timestamps();
            
            // Indexes
            $table->index('tier');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_tiers');
    }
};

