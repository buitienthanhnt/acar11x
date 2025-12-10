<?php
namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class HomeApi
{
	public function __construct(
		protected Home $home
	)
	{
		// throw new \Exception('Not implemented');
	}

	/**
	 * get home detail for Inertia
	 * @param int $homeId
	 * @return \Thanhnt\Ahomeglobal\Models\Home
	 */
	public function getHomeDetail(int $homeId) {
		$home =  $this->home->with('rooms')->with('orderTimes')->find($homeId);
		/**
		 * set hidden for: booked_dates attribute(not need in homeDetail)
		 */
		$home->rooms->setHidden(['booked_dates', ...RoomInterface::HIDDEN_FIELDS]);
		return $home;
	}
}
