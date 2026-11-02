<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\ProductCategoryInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ProductCategoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->integer(ProductCategoryInterface::PRODUCT_ID);
            $table->integer(ProductCategoryInterface::CATEGORY_ID);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ProductCategoryInterface::TABLE_NAME);
    }
};
