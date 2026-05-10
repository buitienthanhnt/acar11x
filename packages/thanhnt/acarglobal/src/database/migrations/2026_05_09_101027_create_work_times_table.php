<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\WorkTimeInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(WorkTimeInterface::TABLE_NAME, function (Blueprint $table) {
			$table->id();
			$table->date(WorkTimeInterface::DATE);
			$table->json(WorkTimeInterface::TIME_WORK)->nullable();
			$table->string(WorkTimeInterface::DESCRIPTION)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(WorkTimeInterface::TABLE_NAME);
	}
};
