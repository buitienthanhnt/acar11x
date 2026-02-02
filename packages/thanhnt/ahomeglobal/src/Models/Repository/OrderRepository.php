<?php

namespace Thanhnt\Ahomeglobal\Models\Repository;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class OrderRepository
{
	public function __construct(
		protected Order $order,
		protected OrderTime $orderTime,
		protected Room $room,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * define function for create new order.
	 * @param string[] $dateValues
	 * @param int $home
	 * @param int $room
	 * @return array{expect_order: array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: string[], total_price: float}, expect_room: mixed}|null
	 * @throws Exception
	 */
	public function getExpectOrder($dateValues, $home = null, $room = null)
	{
		/**
		 * sort list input date from request 
		 */
		$dateValues = $this->dateTimeHelper->sortArrayDateString($dateValues);

		/**
		 * get active room by list date, if home exist then get active room by home
		 * has 2 option: 1 dateRange, 2 date list
		 */
		$activeRoom = $this->getActiveRoom($dateValues, $home)->get()->makeHidden([RoomInterface::BOOKED_DATE, RoomInterface::PRICE]);
		if (!$activeRoom->count()) {
			return null;
			throw new Exception('the input date or home not active');
		}

		/**
		 * check room exist
		 */
		if ($room && !in_array($room, $activeRoom->pluck(RoomInterface::ID)->toArray())) {
			return null;
			throw new Exception('the input room not active');
		}

		/**
		 * setup selected room and order detail.
		 */
		$selectedRoom = $room ? Room::find($room) : $activeRoom->random();
		return [
			'expect_order' => [
				OrderInterface::HOME_ID => $home,
				OrderInterface::ROOM_ID => $selectedRoom->id,
				OrderInterface::DATE_FROM => min($dateValues) ?? Carbon::now(),
				OrderInterface::DATE_TO => max($dateValues) ?? Carbon::now(),
				OrderInterface::SELECTED_TIME => $dateValues,
				OrderInterface::TOTAL_PRICE => $this->dateCount($dateValues) * $selectedRoom->price * 1000, // kvnd to vnd
			],
			'expect_room' => $selectedRoom,
		];
	}

	/**
	 * get count of selected date.
	 * @param string[]
	 * @return int
	 */
	public function dateCount(array $dates)
	{
		if (!config('ahomeglobal.mode') === 'list_date') {
			return count($dates);
		} else {
			$startDate = Carbon::parse(min($dates));
			$endDate = Carbon::parse(max($dates));
			/**
			 * Calculate the difference in days
			 * Đếm số ngày giữa 2 giá trị đầu cuối
			 * Cách Tính ngày bao gồm:
			 * Tính theo đêm ngày đầu tiên và đêm ngày cuối cùng(tính theo đêm tất cả các ngày chọn)
			 * Ví dụ: [2025-12-18, 2025-12-19]: Bao gồm đêm ngày 18(12h-18 -> 12h-19) và đêm ngày 19(12h-19 -> 12h-20)
			 * Ví dụ: [2025-12-18]: Chỉ chọn 1 ngày thì là đêm ngày 18(12h-18 -> 12h-19) 
			 */
			return abs($startDate->diffInDays($endDate)) + 1;
		}
		return;
	}

	/**
	 * save cart data to session
	 * @param array $cart
	 */
	public function addToCart(array $cart)
	{
		if (empty($cart)) {
			return false;
		}
		Session::put('cart', $cart);
		return true;
	}

	/**
	 * get cart data
	 * @return array
	 */
	public function getCart()
	{
		return Session::get('cart');
	}

	/**
	 * get list orders has order time in input range(support for use datetime range now only support array date)
	 * @param string $dateFrom ex: 2025-12-20
	 * @param string $dateTo   ex: 2025-12-27
	 * @return \Illuminate\Database\Eloquent\Collection<Order>
	 */
	public function getDisableOrderByRange(string $dateFrom, string $dateTo)
	{
		/**
		 * mode: date_range and not support room qty, order_qty.
		 */
		if (config('ahomeglobal.qty_mode', false)) {
			return $this->order->where(function (Builder $query) use ($dateFrom, $dateTo) {
				$query->where(OrderInterface::DATE_FROM, '>=', $dateFrom)->where(OrderInterface::DATE_FROM, '<=', $dateTo);
			})
				->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
					$query->where(OrderInterface::DATE_TO, '>=', $dateFrom)->where(OrderInterface::DATE_TO, '<=', $dateTo);
				})->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
					$query->where(OrderInterface::DATE_FROM, '<=', $dateFrom)->where(OrderInterface::DATE_TO, '>=', $dateTo);
				})
				->get()
				->makeVisible([OrderInterface::ROOM_ID, OrderInterface::HOME_ID]);
		}
		/**
		 * mode: date_range and all room has count=1
		 * get all orders in date range
		 * count number of room booked in list search
		 * then filter if order sum qty of room >= room count (meaning room is full booked in this date range)
		 * so return the filter list
		 */
		return $this->order->where(function (Builder $query) use ($dateFrom, $dateTo) {
			$query->where(OrderInterface::DATE_FROM, '>=', $dateFrom)->where(OrderInterface::DATE_FROM, '<=', $dateTo);
		})
			->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
				$query->where(OrderInterface::DATE_TO, '>=', $dateFrom)->where(OrderInterface::DATE_TO, '<=', $dateTo);
			})->orWhere(function (Builder $query) use ($dateFrom, $dateTo) {
				$query->where(OrderInterface::DATE_FROM, '<=', $dateFrom)->where(OrderInterface::DATE_TO, '>=', $dateTo);
			})
			->with(OrderInterface::ROOM)
			->select('*', DB::raw("SUM(qty) as count"))
			->groupBy(OrderInterface::ROOM_ID)
			->get()
			->makeVisible([OrderInterface::ROOM_ID, OrderInterface::HOME_ID])
			->filter(function ($order) {
				return $order->count >=  $order->room->count;
			});
	}

	/**
	 * @param string[] $listDate
	 * @param int $homeId
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getDisableRoomByDates($listDate = [], ?int $homeId = null)
	{
		$inActiveRoom = [];
		if (config('ahomeglobal.qty_mode', false)) {
			/**
			 * mode: list_date and not support room qty, order_qty.
			 */
			$inActiveRoom = $this->orderTime->whereIn(OrderTimeInterface::DATE, $listDate)->where(
				fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
			)->get()
				->flatMap(function ($orderTime) {
					return $orderTime->{OrderTimeInterface::ROOM_IDS};
				})->unique()->toArray();
		} else {
			/**
			 * mode: list_date and support room qty, order_qty.
			 */
			foreach ($listDate as $date) {
				$orders = $this->order->where(
					fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
				)->whereJsonContains(OrderInterface::SELECTED_TIME, $date)
					->select('*', DB::raw("SUM(qty) as count"))
					->groupBy(OrderInterface::ROOM_ID)
					->with(OrderInterface::ROOM)
					->get()
					->makeVisible([OrderInterface::ROOM_ID, OrderInterface::HOME_ID])
					->filter(function ($order) {
						return $order->count >=  $order->room->count;
					});
				$inActiveRoom = [...$orders->pluck([OrderInterface::ROOM_ID])->toArray(), ...$inActiveRoom];
			}
		}
		return $this->room->whereIn(RoomInterface::ID, $inActiveRoom)->get()->makeHidden([RoomInterface::BOOKED_DATE]);
	}

	/**
	 * get array room id has pass for list input dates
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return array[int]
	 */
	public function getDisableArrayRoomByDates($listDate = [], ?int $homeId = null): array
	{
		return $this->getDisableRoomByDates(listDate: $listDate, homeId: $homeId)->pluck([RoomInterface::ID])->toArray();
	}

	/**
	 * get all rooms has pass for list input dates
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getActiveRoomByDates(array $listDate = [], ?int $homeId = null)
	{
		return $this->room->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates($listDate));
	}

	/**
	 * get active room by input date range(support for use datetime range now only support array date)
	 * @param string $dateFrom ex: 2025-12-20
	 * @param string $dateTo   ex: 2025-12-27
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getActiveRoomByRange($listDate)
	{
		return $this->room->whereNotIn(
			RoomInterface::ID,
			$this->getDisableOrderByRange($listDate[0], end($listDate))->pluck([OrderInterface::ROOM_ID])->toArray()
		);
	}

	public function getActiveHomeByRange($listDate)
	{
		return $this->room->whereIn(RoomInterface::ID, $this->getActiveRoomByRange($listDate)->get()->pluck([RoomInterface::ID])->toArray())
			->select(OrderTimeInterface::HOME_ID)
			->distinct()
			->get()
			->makeHidden([RoomInterface::BOOKED_DATE, RoomInterface::PRICE,])
			->pluck(OrderTimeInterface::HOME_ID);
	}

	function getActiveHomeIdByDates($listDate)
	{
		return $this->room->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates($listDate))
			->select(OrderTimeInterface::HOME_ID)
			->distinct()
			->get()
			->makeHidden([RoomInterface::BOOKED_DATE, RoomInterface::PRICE,])
			->pluck(OrderTimeInterface::HOME_ID);
	}

	/**
	 * getActive room with auto check mode
	 * @param string[] $dates  ex:[2025-12-06, 2025-12-11, ...]
	 * @param int|null $homeId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	function getActiveRoom(array $dates, ?int $homeId = null)
	{
		return config('ahomeglobal.mode', 'list_date' === 'list_date') ?
			$this->getActiveRoomByDates($dates, $homeId) : $this->getActiveRoomByRange(
				$dates
			);
	}

	/**
	 * @param string[] $listDate [2025-12-12, 2025-12-13, 2025-12-14,...]
	 * @return Collection
	 */
	public function getActiveHomeIdByDate(array $listDate = [])
	{
		return config('ahomeglobal.mode', 'list_date') !== 'list_date' ?
			$this->getActiveHomeByRange($listDate)
			:
			$this->getActiveHomeIdByDates($listDate);
	}

	/**
	 * @param array[string] $listDate
	 * @param int $homeId
	 * @return bool
	 */
	public function hasActiveRoomByDates(array $listDate = [], ?int $homeId = null)
	{
		return $this->room->where(
			fn($builder) =>  $homeId ? $builder->where(OrderTimeInterface::HOME_ID, $homeId) : $builder
		)->whereNotIn(RoomInterface::ID, $this->getDisableArrayRoomByDates(listDate: $listDate))->exists();
	}
}
