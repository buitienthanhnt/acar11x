<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use App\Helper\LogHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

class HomeFactory extends Factory
{
	protected $log;
	
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var class-string<\Illuminate\Database\Eloquent\Model>
	 */
	protected $model = Home::class;

	public function definition()
	{
		return [
			HomeInterface::NAME => $this->faker->name(),
			HomeInterface::DESCRIPTION => $this->faker->paragraph(1),
			HomeInterface::DISTRICT => $this->faker->paragraph(),
			HomeInterface::IMAGE_PATH => $this->faker->imageUrl(),
		];
	}

	/**
	 * Configure the model factory.
	 */
	public function configure(): static
	{
		$this->log = app(LogHelper::class);
		
		return $this->afterMaking(function (Home $home) {
			$this->log->logToDay('making home: ' . $home?->name);
		})->afterCreating(function (Home $home) {
			$this->log->logToDay('create home: ' . $home?->id);
		});
	}
}
