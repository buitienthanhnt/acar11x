<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

class HomeFactory extends Factory
{
	/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
	protected $model = Home::class;

	public function definition() {
		return [
			HomeInterface::NAME => $this->faker->name(),
			HomeInterface::DESCRIPTION => $this->faker->paragraph(1),
			HomeInterface::DISTRICT => $this->faker->paragraph(),
		];
	}
}
