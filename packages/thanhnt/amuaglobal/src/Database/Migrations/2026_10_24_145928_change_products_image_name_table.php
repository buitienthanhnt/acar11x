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
		if (Schema::hasColumn(ProductInterface::TABLE_NAME, 'image_path')) {
			Schema::table(ProductInterface::TABLE_NAME, function (Blueprint $table) {
				/**
				 * rename column in schema
				 */
				$table->renameColumn('image_path', ProductInterface::IMAGE);
			});
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		//
	}
};
