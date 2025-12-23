<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\OrderDetailInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderDetailInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            /**
             * customer_info
             */
            $table->string(OrderDetailInterface::ORDER_ID);
            $table->string(OrderDetailInterface::NAME);
            $table->string(OrderDetailInterface::PHONE);
            $table->string(OrderDetailInterface::EMAIL);

            /**
             * payment method
             */
            $table->string(OrderDetailInterface::PAYMENT_METHOD);
            $table->integer(OrderDetailInterface::QUANTITY);
            $table->float(OrderDetailInterface::PRICE);
            $table->float(OrderDetailInterface::TOTAL_PRICE);
            $table->string(OrderDetailInterface::CURRENCY);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(OrderDetailInterface::TABLE_NAME);
    }
};
