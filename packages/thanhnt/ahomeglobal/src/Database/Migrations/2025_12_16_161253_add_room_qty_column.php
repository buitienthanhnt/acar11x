<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     * add new column 'count' to rooms table for define quantity of rooms
     */
    public function up(): void
    {
        if (!Schema::hasColumn(RoomInterface::TABLE_NAME, 'count')) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->addColumn('integer', RoomInterface::COUNT, ['default' => 1, 'nullable' => false, 'after' => RoomInterface::DESCRIPTION]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(RoomInterface::TABLE_NAME, 'count')) {
            Schema::table(RoomInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn('count');
            });
        }
    }
};
