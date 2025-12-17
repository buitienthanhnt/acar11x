<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     * add new columns 'qty' and 'total_price' to orders table
     */
    public function up(): void
    {
        if (!Schema::hasColumn(OrderInterface::TABLE_NAME, OrderInterface::TOTAL_PRICE)) {
            Schema::table(OrderInterface::TABLE_NAME, function (Blueprint $table) {
                $table->integer(OrderInterface::TOTAL_PRICE,)->default(0)->after(OrderInterface::QTY);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(OrderInterface::TABLE_NAME, OrderInterface::TOTAL_PRICE)) {
            Schema::table(OrderInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(OrderInterface::TOTAL_PRICE);
            });
        }
    }
};
