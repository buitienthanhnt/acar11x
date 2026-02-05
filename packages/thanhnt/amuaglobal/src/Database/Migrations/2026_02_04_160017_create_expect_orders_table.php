<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\ExpectOrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ExpectOrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->ulid(ExpectOrderInterface::ID)->primary();
            $table->date(ExpectOrderInterface::DATE_FROM);
            $table->date(ExpectOrderInterface::DATE_TO);
            $table->json(ExpectOrderInterface::SELECTED_TIME);

            $table->integer(ExpectOrderInterface::ITEM_ID);
            $table->integer(ExpectOrderInterface::QTY)->default(1);
            $table->float(ExpectOrderInterface::TOTAL_PRICE)->default(0);
            $table->char(ExpectOrderInterface::STATUS)->default('complete');
            $table->char(ExpectOrderInterface::PAYMENT_METHOD)->nullable();

            $table->char(ExpectOrderInterface::SHIPPING_METHOD)->nullable();
            $table->json(ExpectOrderInterface::SHIPPING_ADDRESS);
            $table->json(ExpectOrderInterface::CUSTOMER_INFO);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ExpectOrderInterface::TABLE_NAME);
    }
};
