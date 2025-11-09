<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Types\PageInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(PageInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->text(PageInterface::TITLE);
            $table->char(PageInterface::ALIAS)->unique();
            $table->boolean(PageInterface::ACTIVE)->default(false);
            $table->char(PageInterface::DESCRIPTION)->nullable();
            $table->string(PageInterface::IMAGE_PATH)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PageInterface::TABLE_NAME);
    }
};
