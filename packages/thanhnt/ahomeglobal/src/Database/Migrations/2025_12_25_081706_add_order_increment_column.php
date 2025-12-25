<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn(OrderInterface::TABLE_NAME, OrderInterface::INCREMENT_ID)) {
            Schema::table(OrderInterface::TABLE_NAME, function (Blueprint $table) {
                $table->addColumn('char', OrderInterface::INCREMENT_ID)->nullable()->after(OrderInterface::STATUS);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(OrderInterface::TABLE_NAME, OrderInterface::INCREMENT_ID)) {
            Schema::table(OrderInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(OrderInterface::INCREMENT_ID);
            });
        }
    }
};
