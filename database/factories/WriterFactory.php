<?php

namespace Database\Factories;

use App\Models\Types\WriterInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Writer>
 */
class WriterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            WriterInterface::NAME => $this->faker->text(12),
            WriterInterface::EMAIL => $this->faker->email(),
            WriterInterface::ACTIVE => $this->faker->boolean(),
            WriterInterface::ALIAS => $this->faker->text(16),
            WriterInterface::ADDRESS => $this->faker->address(),
            WriterInterface::DATE_OF_BIRTH => $this->faker->date(),
            WriterInterface::PHONE => $this->faker->phoneNumber()
        ];
    }
}
