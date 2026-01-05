<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Abookglobal\Models\Types\PublisherInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(PublisherInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(PublisherInterface::NAME);
            $table->string(PublisherInterface::DESCRIPTION)->nullable();
            $table->string(PublisherInterface::IMAGE_PATH)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PublisherInterface::TABLE_NAME);
    }
};
