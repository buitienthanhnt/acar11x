<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\CustomerInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(CustomerInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->char(CustomerInterface::NAME, 255);
			$table->char(CustomerInterface::PHONE, 20);
			$table->char(CustomerInterface::ADDRESS, 255)->nullable();
			$table->char(CustomerInterface::COMPANY, 255)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(CustomerInterface::TABLE_NAME);
	}
};
