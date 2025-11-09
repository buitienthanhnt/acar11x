<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Types\PageInterface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            PageInterface::TITLE => $this->faker->text(26),
            PageInterface::ALIAS => $this->faker->text(20),
            PageInterface::ACTIVE => true,
            PageInterface::IMAGE_PATH => $this->faker->text(12),
        ];
    }
}
