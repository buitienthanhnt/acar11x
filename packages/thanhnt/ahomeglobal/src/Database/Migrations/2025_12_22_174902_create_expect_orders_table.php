<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Ahomeglobal\Models\Types\ExpectOrderInterface;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ExpectOrderInterface::TABLE_NAME, function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->integer(ExpectOrderInterface::HOME_ID);
            $table->integer(ExpectOrderInterface::ROOM_ID);
            $table->char(ExpectOrderInterface::STATUS)->default('complete');
            $table->date(ExpectOrderInterface::DATE_FROM);
            $table->date(ExpectOrderInterface::DATE_TO)->nullable();
            $table->json(ExpectOrderInterface::SELECTED_TIME);
            $table->integer(ExpectOrderInterface::QTY)->default(1)->after(ExpectOrderInterface::SELECTED_TIME);
            $table->integer(ExpectOrderInterface::TOTAL_PRICE,)->default(0)->after(ExpectOrderInterface::QTY);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ExpectOrderInterface::TABLE_NAME);
    }
};
