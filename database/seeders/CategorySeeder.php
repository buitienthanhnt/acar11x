<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCategory();
    }

    protected function createCategory(): bool
    {
        try {
            Category::factory()->create();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }
}
