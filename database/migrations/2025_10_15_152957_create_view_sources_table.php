<?php

use App\Models\Types\PageInterface;
use App\Models\Types\ViewSourceInterface;
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
        Schema::create('view_sources', function (Blueprint $table) {
            $table->id();
            $table->string(ViewSourceInterface::TYPE)->default(PageInterface::MODEL_TYPE);
            // https://viblo.asia/p/use-mysql-json-field-in-laravel-OeVKBqk05kW
            // https://qcode.in/use-mysql-json-field-in-laravel/
            $table->json(ViewSourceInterface::VALUE);  // bản chất là lưu json string. ex: {"fire": "21", "like": "5", "heart": "66"}
            $table->integer(ViewSourceInterface::TARGET_ID);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('view_sources');
    }
};
