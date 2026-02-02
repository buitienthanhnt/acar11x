<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn(HomeInterface::TABLE_NAME, HomeInterface::ALIAS)) {
            Schema::table(HomeInterface::TABLE_NAME, function (Blueprint $table) {
                $table->string(HomeInterface::ALIAS, 191)->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(HomeInterface::TABLE_NAME, HomeInterface::ALIAS)) {
            Schema::table(HomeInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(HomeInterface::ALIAS);
            });
        }
    }
};
