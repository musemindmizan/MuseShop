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
        Schema::table('products', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('slug');
            $table->text('description')->nullable()->after('short_description');
            $table->decimal('sale_price', 8, 2)->nullable()->after('price');
            $table->string('SKU')->unique()->after('sale_price');
            $table->string('gallery')->nullable()->after('image');
            $table->boolean('featured')->default(false)->after('status');
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['brand_id']);
            $table->dropColumn([
                'short_description',
                'description',
                'sale_price',
                'SKU',
                'gallery',
                'featured',
                'category_id',
                'brand_id',
            ]);
        });
    }
};
