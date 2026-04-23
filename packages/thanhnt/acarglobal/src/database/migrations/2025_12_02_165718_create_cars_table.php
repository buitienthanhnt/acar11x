<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

return new class extends Migration
{
	/**
	 * Make migration: php artisan make:migration create_cars_table --path=packages/thanhnt/acarglobal/src/database/migrations
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(CarInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->char(CarInterface::KEY);                            // bien so
			$table->char(CarInterface::SUSPENSION)->nullable();         // hang xe
			$table->char(CarInterface::TYPE)->nullable();               // dong xe
			$table->bigInteger(CarInterface::KM)->nullable();
			$table->dateTime(CarInterface::YEAR)->nullable(); //->useCurrent(); hien tai cho thoi gian
			$table->char(CarInterface::VIN)->nullable();
			$table->char(CarInterface::CUSTOMER)->nullable();
			$table->char(CarInterface::PHONE)->nullable();
			$table->char(CarInterface::ADDRESS)->nullable();

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(CarInterface::TABLE_NAME);
	}
};
