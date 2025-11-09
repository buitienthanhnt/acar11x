<?php

use App\Models\Types\TagInterface;
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
        Schema::create(TagInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(TagInterface::KEY)->index(TagInterface::KEY);
            $table->char(TagInterface::VALUE);
            $table->char(TagInterface::TYPE);
            $table->integer(TagInterface::TARGET_ID);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(TagInterface::TABLE_NAME);
    }
};
