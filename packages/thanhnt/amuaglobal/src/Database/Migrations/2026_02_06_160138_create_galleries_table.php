<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\GalleryInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(GalleryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->integer(GalleryInterface::SOURCE_ID);
            $table->char(GalleryInterface::TYPE);
            $table->char(GalleryInterface::PATH);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(GalleryInterface::TABLE_NAME);
    }
};
