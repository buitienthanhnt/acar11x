<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Attr;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class HomeApi
{
	public function __construct(
		protected Home $home,
		protected OrderApi $orderApi,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * get home detail for Inertia
	 * @param int $homeId
	 * @return \Thanhnt\Ahomeglobal\Models\Home
	 */
	public function getHomeDetail(int $homeId)
	{
		$home =  $this->home->with('rooms')->with('orderTimes')->with('attr')->find($homeId);
		/**
		 * set hidden for: booked_dates attribute(not need in homeDetail)
		 */
		$home->rooms->setHidden(['booked_dates', ...RoomInterface::HIDDEN_FIELDS]);
		return $home;
	}

	public function homePaginate(int $limit = 12)
	{
		return $this->home->paginate($limit);
	}

	/**
	 * get list home by filter attribute room
	 */
	public function paginateFilter($filterParams = [], $limit = 6)
	{
		if ($filterParams) {
			$seletedDates = $filterParams['dates'] ?? [];
			if ($seletedDates) {
				return $this->orderApi->getActiveHomeByDate($seletedDates, $limit);
			}
			return Home::withWhereHas('rooms')->paginate($limit);
		}
		return Home::withWhereHas('rooms')->paginate($limit);
	}

	protected function filterByCustomAttr() {}

	public function getFilters()
	{

		$roomFilterFields = RoomInterface::CUSTOM_ATTRS;
		$homeFilterFields = RoomInterface::CUSTOM_ATTRS;

		$roomFilterFieldValues = Attr::where(AttrInterface::KEY, 'room')->groupBy(AttrInterface::KEY)->get();
		// dd($roomFilterFieldValues->toArray());
	}
}
