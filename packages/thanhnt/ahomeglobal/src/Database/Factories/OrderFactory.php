<?php

namespace Thanhnt\Ahomeglobal\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;

class OrderFactory extends Factory implements OrderInterface
{
	protected $model = Order::class;

	/**
	 * @return array
	 */
	public function definition()
	{
		$carbon_time = new Carbon();
		return [
			self::HOME_ID => 1,
			self::ROOM_ID => 1,
			self::DATE_FROM => $carbon_time->today(),
			self::DATE_TO => $carbon_time->today(),
			self::SELECTED_TIME => [$carbon_time->today()],
			self::STATUS => 'complete',
			self::INCREMENT_ID => Str::random(26),
		];
	}
}
