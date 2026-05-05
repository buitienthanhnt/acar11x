<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(CarFixInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->char(CarFixInterface::STATUS)->default(CarFixInterface::STATUS_WAIT);
			$table->integer(CarFixInterface::CAR_ID);
			$table->bigInteger(CarFixInterface::KM)->nullable();
			$table->float(CarFixInterface::VAT)->default(0);
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(CarFixInterface::TABLE_NAME);
	}
};
