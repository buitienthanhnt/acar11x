<?php

use App\Models\Types\CommentInterface;
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
        Schema::create(CommentInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(CommentInterface::TYPE)->default('page');
            $table->boolean(CommentInterface::ACTIVE)->default(true);
            $table->integer(CommentInterface::PARENT_ID)->nullable();
            $table->integer(CommentInterface::USER_ID);
            $table->text(CommentInterface::CONTENT);
            $table->integer(CommentInterface::TARGET_ID);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(CommentInterface::TABLE_NAME);
    }
};
