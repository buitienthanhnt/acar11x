<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(CategoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(CategoryInterface::NAME);
            $table->boolean(CategoryInterface::ACTIVE)->default(true);
            $table->char(CategoryInterface::ALIAS)->unique();
            $table->text(CategoryInterface::DESCRIPTION)->nullable();
            $table->text(CategoryInterface::IMAGE)->nullable();
            $table->integer(CategoryInterface::PARENT_ID)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(CategoryInterface::TABLE_NAME);
    }
};
