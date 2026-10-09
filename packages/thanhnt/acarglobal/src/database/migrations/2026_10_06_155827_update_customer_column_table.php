<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Models\Types\CustomerInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		if (!Schema::hasColumn(CarInterface::TABLE_NAME, CarInterface::CUSTOMER_ID)) {
			/**
			 * setup link customer_id to customer table,
			 */
			Schema::table(CarInterface::TABLE_NAME, function (Blueprint $table) {
				$table->unsignedBigInteger(CarInterface::CUSTOMER_ID)->nullable()->after(CarInterface::ADDRESS);
				// $table->foreign(CarInterface::CUSTOMER_ID)
				// 	->references('id')
				// 	->on(CustomerInterface::TABLE_NAME)
				// 	->onDelete('set null');
			});

			/**
			 * drop column customer if exists, 
			 * because we have changed the column name from customer to customer_id
			 */
			if (Schema::hasColumn(CarInterface::TABLE_NAME, CarInterface::CUSTOMER)) {
				Schema::table(CarInterface::TABLE_NAME, function (Blueprint $table) {
					$table->dropColumn(CarInterface::CUSTOMER);
				});
			}
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		if (Schema::hasColumn(CarInterface::TABLE_NAME, CarInterface::CUSTOMER_ID)) {
			Schema::table(CarInterface::TABLE_NAME, function (Blueprint $table) {
				$table->dropColumn(CarInterface::CUSTOMER_ID);
			});
		}
	}
};
