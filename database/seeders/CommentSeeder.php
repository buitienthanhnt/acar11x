<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createComment();
    }

    /**
     * create new comment demo.
     */
    protected function createComment() : bool {
        Comment::factory()->create();
        return true;
    }
}
