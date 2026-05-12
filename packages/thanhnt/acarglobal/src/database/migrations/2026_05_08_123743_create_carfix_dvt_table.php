<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		if (!Schema::hasColumns(ActivityInterface::TABLE_NAME, [ActivityInterface::DVT])) {
			Schema::table(ActivityInterface::TABLE_NAME, function (Blueprint $table) {
				$table->addColumn('char', ActivityInterface::DVT, ['length' => 255])->nullable();
			});
		};
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		if (Schema::hasColumns(ActivityInterface::TABLE_NAME, [ActivityInterface::DVT])) {
			Schema::dropColumns(ActivityInterface::TABLE_NAME, [ActivityInterface::DVT]);
		}
	}
};
