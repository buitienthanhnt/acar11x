<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		if (!Schema::hasColumn(ProductInterface::TABLE_NAME, ProductInterface::UNIT)) {
			Schema::table(ProductInterface::TABLE_NAME, function (Blueprint $table) {
				$table->char(ProductInterface::UNIT, 50)->nullable();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		if (Schema::hasColumn(ProductInterface::TABLE_NAME, ProductInterface::UNIT)) {
			Schema::table(ProductInterface::TABLE_NAME, function (Blueprint $table) {
				$table->dropColumn(ProductInterface::UNIT);
			});
		}
	}
};
