<?php

namespace Database\Factories;

use App\Models\Types\CommentInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            CommentInterface::TYPE => 'page',
            CommentInterface::ACTIVE => false,
            CommentInterface::CONTENT => $this->faker->paragraphs(14),
            CommentInterface::PARENT_ID => null,
            CommentInterface::TARGET_ID => 1,
            CommentInterface::USER_ID => 0,
        ];
    }
}
