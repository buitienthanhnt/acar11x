<?php

use App\Models\Types\PageInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * doc: https://laravel.com/docs/12.x/migrations#creating-columns
     */
    public function up(): void
    {
        Schema::table(PageInterface::TABLE_NAME, function (Blueprint $table) {
           $table->addColumn('boolean', PageInterface::ABOVE, [
            'default' => false, // vendor/laravel/framework/src/Illuminate/Database/Schema/ColumnDefinition.php
           ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(PageInterface::TABLE_NAME, function(Blueprint $table){
            $table->removeColumn(PageInterface::ABOVE);
        });
    }
};
