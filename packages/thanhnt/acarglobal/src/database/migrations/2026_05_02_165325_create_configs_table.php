<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\AcarConfigInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create(AcarConfigInterface::TABLE_NAME, function (Blueprint $table) {
			$table->char(AcarConfigInterface::KEY)->unique();
			$table->string(AcarConfigInterface::VALUE)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists(AcarConfigInterface::TABLE_NAME);
	}
};
