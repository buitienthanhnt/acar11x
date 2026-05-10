<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\SalaryInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(SalaryInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			/**
			 * 
			 */
			$table->char(SalaryInterface::NAME);
			$table->char(SalaryInterface::TYPE);
			$table->integer(SalaryInterface::EMPLOYEE_ID);
			$table->float(SalaryInterface::VALUE);
			$table->text(SalaryInterface::NOTE)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(SalaryInterface::TABLE_NAME);
	}
};
