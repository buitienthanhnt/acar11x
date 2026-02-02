<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\AttrInterface;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
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
		/**
		 * @var Home|null $home
		 */
		$home =  $this->home
			->with(HomeInterface::ROOMS)
			->with(HomeInterface::ORDER_TIMES, function ($query) { // get order times from today or after today
				$query->whereTodayOrAfter(OrderTimeInterface::DATE);
			})
			->with(HomeInterface::ATTR)
			->with(HomeInterface::GALLERY)
			->find($homeId);
		/**
		 * set hidden for: booked_dates attribute(not need in homeDetail)
		 * dùng ?-> để kiem tra null trên $home không bị lỗi.
		 */
		$home?->rooms->setHidden([RoomInterface::BOOKED_DATE, ...RoomInterface::HIDDEN_FIELDS]);
		return $home;
	}

	/**
	 * get home paginate without filter
	 * @param int $limit
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function homePaginate(int $limit = 12)
	{
		return $this->home->paginate($limit);
	}

	/**
	 * @param string $district
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getHomeByDistrict(string $district)
	{
		// $homeList =  Home::whereRaw('district LIKE ? COLLATE utf8mb4_unicode_ci', ['%'.$district.'%']); // mysql
		$homeList =  Home::whereLike(HomeInterface::DISTRICT, "%$district%");
		return $homeList;
	}

	/**
	 * get list home id filter by Room custom attribute.
	 * @param array{[string]: string}[] $filterParams
	 * @return int[]
	 */
	public function getHomeIdfilterByCustomAttr($filterParams): array
	{
		if (empty($filterParams)) {
			return [];
		}

		if ($district = $filterParams['district'] ?? null) {
			$homeIds = $this->getHomeByDistrict($district)->select('id')->get()->pluck(['id'])->toArray();
		}

		$listFilters = array_intersect_key($filterParams, RoomInterface::CUSTOM_ATTRS);
		$instance = Room::query()->with(RoomInterface::HOME);

		foreach ($listFilters as $key => $value) {
			switch ($key) {
				case 'price':
					$instance->whereHas(RoomInterface::ATTR, function ($query) use ($key, $value) {
						$query->where(AttrInterface::KEY, $key)->whereBetween(AttrInterface::VALUE, explode('-', $value));
					});
					break;
				default:
					$instance->whereHas(RoomInterface::ATTR, function ($query) use ($key, $value) {
						$query->where(AttrInterface::KEY, $key)->where(AttrInterface::VALUE, $value);
					});
					break;
			}
		}

		$default = $instance->whereHas(RoomInterface::HOME)->get()->pluck(RoomInterface::HOME_ID)->unique()->toArray();
		return isset($homeIds) ? array_intersect($homeIds, $default) : $default;
	}

	protected function filterHomeByRate() {}

	/**
	 * get list home by filter attribute room paginate
	 * @param array $filterParams
	 * @param int $limit
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function paginateHomeWithFilter($filterParams = [], $limit = 6)
	{
		if ($filterParams) {
			/**
			 * filter home by name
			 */
			if ($district = $filterParams['district'] ?? null) {
				$homeIds = $this->getHomeByDistrict($district)->select('id')->get()->pluck(['id'])->toArray();
			}

			/**
			 * filter home by date.
			 */
			if ($seletedDates = $filterParams['dates'] ?? null) {
				$homeIds = isset($homeIds) ? array_intersect($this->orderApi->getActiveHomeIdByDate($seletedDates)->toArray(), $homeIds) : $this->orderApi->getActiveHomeIdByDate($seletedDates)->toArray();
			}

			/**
			 * filter by custom attribute of room
			 */
			$homeIds = isset($homeIds) ? array_intersect($this->getHomeIdfilterByCustomAttr($filterParams), $homeIds) : $this->getHomeIdfilterByCustomAttr($filterParams);

			/**
			 * paginate home
			 */
			return Home::whereIn(HomeInterface::ID, $homeIds ?? [])->whereHas(HomeInterface::ROOMS)->with(HomeInterface::ATTR)->paginate($limit);
		}
		/**
		 * return default home list without filter
		 */
		return Home::whereHas(HomeInterface::ROOMS)->with(HomeInterface::ATTR)->paginate($limit);
	}

	/**
	 * get 8 district has most of hotels(homes)
	 */
	public function getMostViewed()
	{
		// SELECT *, count(district) as location FROM homes GROUP BY district ORDER BY location desc LIMIT 8
		return $this->home->select('*')->selectRaw("count(" . HomeInterface::DISTRICT . ") as location")->groupBy(HomeInterface::DISTRICT)->orderBy('location', 'desc')->limit(8)->get();
	}

	/**
	 * @param string $location
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getHomeLocation(string $location)
	{
		$home = $this->home->where(HomeInterface::DISTRICT, $location)->with(HomeInterface::ATTR);
		return $home;
	}

	public function resourceModel() {
		return $this->home;
	}
}
