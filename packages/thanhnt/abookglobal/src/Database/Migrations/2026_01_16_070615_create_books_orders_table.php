<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookOrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(BookOrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(BookOrderInterface::BOOK_ID);
            $table->date(BookOrderInterface::DATE_FROM)->nullable();
            $table->date(BookOrderInterface::DATE_TO)->nullable();
            $table->json(BookOrderInterface::SELECTED_TIME)->nullable();
            $table->integer(BookOrderInterface::QTY)->default(1);
            $table->float(BookOrderInterface::TOTAL_PRICE)->default(0);
            $table->char(BookOrderInterface::STATUS)->default('complete');
            $table->char(BookOrderInterface::INCREMENT_ID);
            $table->char(BookOrderInterface::PAYMENT_METHOD)->nullable();

            $table->char(BookOrderInterface::SHIPPING_METHOD)->nullable();
            $table->json(BookOrderInterface::SHIPPING_ADDRESS)->nullable();
            $table->json(BookOrderInterface::CUSTOMER_INFO);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BookOrderInterface::TABLE_NAME);
    }
};
