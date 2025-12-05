<?php
namespace Thanhnt\Ahomeglobal\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;

class OrderFactory extends Factory implements OrderInterface
{
	/**
	 * @return array
	 */
	public function definition()
	{
		$carbon_time = new Carbon();
		return [
			self::HOME_ID => 1,
			self::ROOM_ID => 1,
			self::DATE_FROM => $carbon_time->date(),
			self::DATE_TO => $carbon_time->date(), 
			self::SELECTED_TIME => [$carbon_time->date()],
			self::STATUS => 'complete',
		];
	}
}
