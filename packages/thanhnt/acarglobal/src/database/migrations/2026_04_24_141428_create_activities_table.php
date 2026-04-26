<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(ActivityInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->integer(ActivityInterface::CAR_ID);
			$table->integer(ActivityInterface::CAR_FIX_ID);
			$table->string(ActivityInterface::TITLE);
			$table->char(ActivityInterface::STATUS);
			$table->string(ActivityInterface::NOTE)->nullable();
			$table->integer(ActivityInterface::QTY)->default(1);
			$table->float(ActivityInterface::PRICE)->default(0);
			$table->integer(ActivityInterface::PRODUCT_ID)->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(ActivityInterface::TABLE_NAME);
	}
};
