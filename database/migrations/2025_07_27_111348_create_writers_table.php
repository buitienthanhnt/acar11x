<?php

use App\Models\Types\WriterInterface;
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
        Schema::create(WriterInterface::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(WriterInterface::NAME);
            $table->char(WriterInterface::EMAIL)->unique();
            $table->boolean(WriterInterface::ACTIVE)->default(false);

            $table->char(WriterInterface::ALIAS)->nullable();
            $table->char(WriterInterface::PHONE)->unique();
            $table->text(WriterInterface::ADDRESS)->nullable();
            $table->char(WriterInterface::IMAGE_PATH)->nullable();
            $table->text(WriterInterface::DESCRIPTION)->nullable();
            $table->date(WriterInterface::DATE_OF_BIRTH);
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(WriterInterface::TABLE_NAME);
    }
};
