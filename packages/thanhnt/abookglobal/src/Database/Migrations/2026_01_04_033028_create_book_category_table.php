<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookCateInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(BookCateInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(BookCateInterface::NAME);
            $table->char(BookCateInterface::ALIAS)->nullable()->unique();
            $table->string(BookCateInterface::IMAGE_PATH)->nullable();
            $table->string(BookCateInterface::DESCRIPTION)->nullable();
            $table->integer(BookCateInterface::PARENT)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BookCateInterface::TABLE_NAME);
    }
};
