<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Page;
use App\Models\Story;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Story::factory()->create([
        //     Story::ATTR_ACTIVE => false,
        //     Story::ATTR_TITLE => 'demo for titile',
        // ]);
        $this->newPage();
    }

    /**
     * create new page by Page factory
     */
    protected function newPage() : void {
        Page::factory()->create([
            Page::DESCRIPTION => 'demo content'
        ]);
        return;
    }
}
