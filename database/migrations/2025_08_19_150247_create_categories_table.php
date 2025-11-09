<?php

use App\Models\Types\CategoryInterface;
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
        Schema::create(CategoryInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(CategoryInterface::NAME);
            $table->boolean(CategoryInterface::ACTIVE)->default(true);
            $table->string(CategoryInterface::ALIAS);
            $table->text(CategoryInterface::DESCIPTION)->nullable();
            $table->text(CategoryInterface::IMAGE_PATH)->nullable();
            $table->integer(CategoryInterface::PARENT)->default(0);
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
