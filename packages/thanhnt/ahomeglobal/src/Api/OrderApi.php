<?php

namespace Thanhnt\Ahomeglobal\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Thanhnt\Ahomeglobal\Models\OrderDetail;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Helper\ModelHelper;
use Thanhnt\Ahomeglobal\Models\ExpectOrder;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\ExpectOrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderDetailInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderInterface;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class OrderApi
{
	public function __construct(
		protected Order $order,
		protected ExpectOrder $expectOrder,
		protected OrderTime $orderTime,
		protected Room $room,
		protected DateTimeHelper $dateTimeHelper,
		protected ModelHelper $modelHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * create new order
	 * @param array $data ex input cart data
	 * @return \Illuminate\Database\Eloquent\Collection<int, TModel>|TModel
	 * return: Thanhnt\Ahomeglobal\Models\Order
	 */
	public function saveOrder($data)
	{
		/**
		 * save order detail
		 */

		/**
		 * create new order
		 */
		$newOrder = $this->order->factory()->create($this->modelHelper->massDataAttribute(OrderInterface::FILLED_FILEDS, $data));
		if ($newOrder) {
			$this->saveOrderDetail($newOrder->id, $data);
		}
		return $newOrder;
	}

	/**
	 * @param string $expectOrderId
	 */
	public function saveOrderByExpect(string $expectOrderId)
	{
		$expectOrder = ExpectOrder::find($expectOrderId);
		if ($expectOrder) {
			return $this->order->factory()->create([
				...$expectOrder->makeHidden([
					ExpectOrderInterface::ID,
					'created_at',
					'updated_at',
					'deleted_at'
				])->toArray(),
				OrderInterface::INCREMENT_ID => $expectOrder->id
			]);
		}
	}

	/**
	 * @param int $orderId
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order: string}|null $cartParams
	 */
	public function saveOrderDetail($orderId, array $data)
	{
		$orderDetailData = $this->formatCartToOrderDetail($data);
		return OrderDetail::create([
			OrderDetailInterface::ORDER_ID => $orderId,
			...$orderDetailData,
		]);
	}

	/**
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_payment_order: array{token: string, id: string,}, on_payment: string|null, expect_order: string}|null $cartParams
	 */
	public function formatCartToOrderDetail($cartParams)
	{
		return [
			OrderDetailInterface::EMAIL => $cartParams['customer_info']['email'],
			OrderDetailInterface::PHONE => $cartParams['customer_info']['phone'],
			OrderDetailInterface::NAME => $cartParams['customer_info']['name'], // info
			OrderDetailInterface::CURRENCY => $cartParams['currency_code'],
			OrderDetailInterface::TOTAL_PRICE => $cartParams['total_price'],
			OrderDetailInterface::QUANTITY => $cartParams['qty'] ?? 1,
			OrderDetailInterface::PRICE => $cartParams['item']['price'],
			OrderDetailInterface::PAYMENT_METHOD => $cartParams['on_payment'],
		];
	}

	/**
	 * @param array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: array[string], total_price: float, currency_code: string, item: array{name: string, description: string, price: float, quantity: string, category: string, image_url: string, url: string, unit_amount: array{currency_code: string, value: float}}, customer_info: array{name: string, email: string, phone: string}, on_order: array{token: string, id: string,}, expect_order: string} $cartParams
	 * @return array{home_id: integer, room_id: integer, date_from: string, date_to: string, selected_time: string[], total_price: float, qty: integer}
	 */
	public function formatCartToOrder($cartParams)
	{
		return [
			OrderInterface::HOME_ID => $cartParams['home_id'],
			OrderInterface::ROOM_ID => $cartParams['room_id'],
			OrderInterface::DATE_FROM => $cartParams['date_from'],
			OrderInterface::DATE_TO => $cartParams['date_to'],
			OrderInterface::SELECTED_TIME => $cartParams['selected_time'],
			OrderInterface::TOTAL_PRICE => $cartParams['total_price'],
			OrderInterface::QTY => $cartParams['qty'] ?? 1,
		];
	}

	/**
	 * create new expect order(expect same as order however have different id)
	 * @param array $data
	 * @return ExpectOrder
	 */
	public function createExpectOrderByCart($cartData)
	{

		$expectData = $this->modelHelper->massDataAttribute(ExpectOrderInterface::FILLED_FILEDS, $cartData);
		$expectData[ExpectOrderInterface::STATUS] = 'created';
		return $this->expectOrder->factory()->create($expectData);
	}

	/**
	 * @param string $id
	 * @param array $cartData
	 * @return ExpectOrder
	 */
	public function updateExpectOrderByCart(string $id, $cartData)
	{
		return ExpectOrder::updateOrCreate(
			['id' => $id],
			$this->modelHelper->massDataAttribute(ExpectOrderInterface::FILLED_FILEDS, $cartData),
		);
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
			$startDate = Carbon::parse($dates[0]);
			$endDate = Carbon::parse(end($dates));
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

	/**
	 * get order detail by increment id
	 * @param string $incrementId
	 * @return \Illuminate\Database\Eloquent\Builder|null
	 */
	public function getOrderDetailByIncrement(string $incrementId)
	{
		return $this->order->where(OrderInterface::INCREMENT_ID, $incrementId)
			->with(OrderInterface::ROOM)
			->with(OrderInterface::HOME)
			->with(OrderInterface::DETAIL)
			->first();
	}
}
