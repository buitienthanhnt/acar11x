<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookExpectOrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(BookExpectOrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date(BookExpectOrderInterface::DATE_FROM);
            $table->date(BookExpectOrderInterface::DATE_TO);
            $table->json(BookExpectOrderInterface::SELECTED_TIME);

            $table->integer(BookExpectOrderInterface::BOOK_ID);
            $table->integer(BookExpectOrderInterface::QTY)->default(1);
            $table->float(BookExpectOrderInterface::TOTAL_PRICE)->default(0);
            $table->char(BookExpectOrderInterface::STATUS)->default('complete');
            $table->char(BookExpectOrderInterface::PAYMENT_METHOD)->nullable();

            $table->char(BookExpectOrderInterface::SHIPPING_METHOD)->nullable();
            $table->json(BookExpectOrderInterface::SHIPPING_ADDRESS);
            $table->json(BookExpectOrderInterface::CUSTOMER_INFO);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BookExpectOrderInterface::TABLE_NAME);
    }
};
