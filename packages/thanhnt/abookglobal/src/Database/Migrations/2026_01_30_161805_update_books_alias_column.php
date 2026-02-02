<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\BookInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn(BookInterface::TABLE_NAME, BookInterface::ALIAS)) {
            Schema::table(BookInterface::TABLE_NAME, function (Blueprint $table) {
                $table->string(BookInterface::ALIAS, 256)->nullable()->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(BookInterface::TABLE_NAME, BookInterface::ALIAS)) {
            Schema::table(BookInterface::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(BookInterface::ALIAS);
            });
        }
    }
};
