<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure tables exist before creating foreign keys
        if (!Schema::hasTable('products') || !Schema::hasTable('categories')) {
            throw new \RuntimeException('Required tables do not exist. Please ensure products and categories tables are created first.');
        }

        Schema::create('category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('product_id');
            $table->primary(['category_id', 'product_id']);
        });

        // Add foreign keys after table creation
        Schema::table('category_product', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnDelete();
                
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
}; 