<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookOrderTimeInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(BookOrderTimeInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->date(BookOrderTimeInterface::DATE);
            $table->json(BookOrderTimeInterface::BOOK_ID);
            $table->json(BookOrderTimeInterface::ORDER_IDS)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BookOrderTimeInterface::TABLE_NAME);
    }
};
