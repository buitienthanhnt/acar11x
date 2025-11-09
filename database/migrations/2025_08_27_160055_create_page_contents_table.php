<?php

use App\Models\Types\PageContentInterface;
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
        Schema::create(PageContentInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(PageContentInterface::TYPE)->default('text');
            $table->char(PageContentInterface::KEY);
            $table->longText(PageContentInterface::VALUE)->nullable();
            $table->json(PageContentInterface::EXTEND_VALUE)->nullable();
            $table->integer(PageContentInterface::PAGE_ID);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PageContentInterface::TABLE_NAME);
    }
};
