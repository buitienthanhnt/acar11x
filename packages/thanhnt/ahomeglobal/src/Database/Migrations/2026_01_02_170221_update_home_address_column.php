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
        if (!Schema::hasColumn(HomeInterface::TABLE_NAME, HomeInterface::ADDRESS)) {
            Schema::table(HomeInterface::TABLE_NAME, function (Blueprint $table) {
                $table->char(HomeInterface::ADDRESS)->nullable()->after(HomeInterface::DISTRICT);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn(HomeInterface::TABLE_NAME, HomeInterface::ADDRESS)) {
            Schema::table(HomeInterface::TABLE_NAME, function (Blueprint $table): void {
                $table->dropColumn(HomeInterface::ADDRESS);
            });
        }
    }
};
