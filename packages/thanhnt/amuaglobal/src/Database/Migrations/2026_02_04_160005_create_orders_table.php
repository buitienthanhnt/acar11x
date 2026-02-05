<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer(OrderInterface::ITEM_ID);
            $table->date(OrderInterface::DATE_FROM)->nullable();
            $table->date(OrderInterface::DATE_TO)->nullable();
            $table->json(OrderInterface::SELECTED_TIME)->nullable();
            $table->integer(OrderInterface::QTY)->default(1);
            $table->float(OrderInterface::TOTAL_PRICE)->default(0);
            $table->char(OrderInterface::STATUS)->default('complete');
            $table->char(OrderInterface::INCREMENT_ID);
            $table->char(OrderInterface::PAYMENT_METHOD)->nullable();

            $table->char(OrderInterface::SHIPPING_METHOD)->nullable();
            $table->json(OrderInterface::SHIPPING_ADDRESS)->nullable();
            $table->json(OrderInterface::CUSTOMER_INFO);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderInterface::TABLE_NAME);
    }
};
