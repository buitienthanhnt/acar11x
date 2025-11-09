<?php

use App\Models\Types\PageCategoriesInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(PageCategoriesInterface::TABLE_NAME, function (Blueprint $table) {
            $table->integer(PageCategoriesInterface::PAGE_ID);
            $table->integer(PageCategoriesInterface::CATEGORY_ID);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PageCategoriesInterface::TABLE_NAME);
    }
};
