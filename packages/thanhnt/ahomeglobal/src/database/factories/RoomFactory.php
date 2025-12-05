<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Models\Home;

class RoomFactory extends Factory implements RoomInterface
{
	/**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
	protected $model = Home::class;

	public function definition() {

		return [
			self::TITLE => $this->faker->name(),
			self::DESCRIPTION => $this->faker->words(10),
			self::HOME_ID => 1,
		];
	}
}
