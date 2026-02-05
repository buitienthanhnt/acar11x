<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ProductInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(ProductInterface::NAME);
            $table->string(ProductInterface::DESCRIPTION)->nullable();
            $table->string(ProductInterface::IMAGE_PATH)->nullable();
            $table->string(ProductInterface::ALIAS);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ProductInterface::TABLE_NAME);
    }
};
