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
        if (!Schema::hasColumn(RoomInterface::TABLE_NAME, RoomInterface::ALIAS)) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->string(RoomInterface::ALIAS, 191)->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(RoomInterface::TABLE_NAME, RoomInterface::ALIAS)) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(RoomInterface::ALIAS);
            });
        }
    }
};
