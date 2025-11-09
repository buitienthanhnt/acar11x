<?php

use App\Models\Types\PageInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(PageInterface::TABLE_NAME, function (Blueprint $table) {
           $table->addColumn('integer', PageInterface::WRITER);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(PageInterface::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(PageInterface::WRITER);
         });
    }
};
