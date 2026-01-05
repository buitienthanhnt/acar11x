<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn(RoomInterface::TABLE_NAME, RoomInterface::PRICE)) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->integer(RoomInterface::PRICE)->default(100)->after(RoomInterface::DESCRIPTION);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(RoomInterface::TABLE_NAME, RoomInterface::PRICE)) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(RoomInterface::PRICE);
            });
        }
    }
};
