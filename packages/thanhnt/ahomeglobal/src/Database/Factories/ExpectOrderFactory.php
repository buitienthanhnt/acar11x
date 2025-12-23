<?php
namespace Thanhnt\Ahomeglobal\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Thanhnt\Ahomeglobal\Models\ExpectOrder;
use Thanhnt\Ahomeglobal\Models\Types\ExpectOrderInterface;

final class ExpectOrderFactory extends Factory implements ExpectOrderInterface
{
	protected $model = ExpectOrder::class;

	public function definition()
	{
		$carbon_time = new Carbon();
		return [
			self::HOME_ID => 1,
			self::ROOM_ID => 1,
			self::DATE_FROM => $carbon_time->today()->format('Y-m-d'),
			self::DATE_TO => $carbon_time->today()->format('Y-m-d'), 
			self::SELECTED_TIME => [$carbon_time->today()->format('Y-m-d')],
			self::STATUS => 'complete',
			self::TOTAL_PRICE => 1,
			self::QTY => 1,
		];
	}
}
