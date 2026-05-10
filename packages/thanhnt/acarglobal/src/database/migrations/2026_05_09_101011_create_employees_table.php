<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\EmployeeInerface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(EmployeeInerface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->char(EmployeeInerface::NAME);
			$table->char(EmployeeInerface::EMAIL)->unique();
			$table->char(EmployeeInerface::PHONE)->nullable();
			$table->char(EmployeeInerface::ADDRESS)->nullable();
			$table->char(EmployeeInerface::STATUS)->nullable();
			$table->float(EmployeeInerface::DATE_SALARY)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(EmployeeInerface::TABLE_NAME);
	}
};
