<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(BookInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(BookInterface::NAME);
            $table->char(BookInterface::ALIAS)->nullable()->unique();
            $table->string(BookInterface::DESCRIPTION)->nullable();
            $table->string(BookInterface::IMAGE_PATH)->nullable();
            $table->integer(BookInterface::PRICE);
            $table->integer(BookInterface::QTY)->default(1);
            $table->integer(BookInterface::RATE)->default(3);
            $table->integer(BookInterface::PUBLISHER)->nullable();
            $table->string(BookInterface::ALIAS, 256)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BookInterface::TABLE_NAME);
    }
};
