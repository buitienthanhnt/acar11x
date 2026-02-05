<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Amuaglobal\Models\Order;
use Thanhnt\Amuaglobal\Helper\DateTimeHelper;
use Thanhnt\Amuaglobal\Models\ExpectOrder;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;

final class BookOrderApi
{
	public function __construct(
		protected Book $book,
		protected Order $order,
		protected ExpectOrder $expectOrder,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/** 
	 * @param int $bookId
	 * @param string[] $dateSelecteds
	 * @return \Illuminate\Database\Eloquent\Collection<\Thanhnt\Amuaglobal\Models\Order>
	 */
	public function getOrderByDateSelect(int $bookId, array $dateSelecteds)
	{
		$orders = $this->order->where(OrderInterface::ITEM_ID, $bookId)
			->where(function ($query) use ($dateSelecteds) {
				foreach ($dateSelecteds as $date) {
					$query->orWhereJsonContains(OrderInterface::SELECTED_TIME, $date);
				}
			})
			->get();
		return $orders;
	}

	/**
	 * @param int $bookId
	 * @param string[] $dateSelecteds
	 * @return array<string, int> // [ '2025-12-12' => 3, '2025-12-15' => 5, ...]
	 */
	public function getBookedTimeByDateSelect(int $bookId, array $dateSelecteds)
	{
		$orders = $this->getOrderByDateSelect($bookId, $dateSelecteds);
		$timeOrderBooked = [];
		foreach ($orders as $order) {
			foreach ($order->{OrderInterface::SELECTED_TIME} as $date) {
				if (isset($timeOrderBooked[$date])) {
					$timeOrderBooked[$date] += $order->{OrderInterface::QTY};
				} else {
					$timeOrderBooked[$date] = $order->{OrderInterface::QTY};
				}
			}
		}
		return $timeOrderBooked;
	}

	/**
	 * @param int $bookId
	 * @param string[] $dateSelecteds
	 * @param int $maxQtyPerDay
	 * @return bool
	 */
	public function checkAvailableTime(int $bookId, array $dateSelecteds, int $qty = 1): bool
	{
		$book = $this->book->findOrFail($bookId);
		/**
		 * validate for dateSelecteds
		 * luu y la dung dau: | chu khong dung dau ,
		 * @/throws \Illuminate\Validation\ValidationException
		 */
		// Validator::make(
		// 	[
		// 		'dateSelecteds' => $dateSelecteds,
		// 	],
		// 	[
		// 		'dateSelecteds' => 'required|array|min:2',
		// 		'dateSelecteds.*' => 'date_format:Y-m-d',
		// 	],
		// 	[
		// 		'dateSelecteds.required' => 'Please select a time slot.123',
		// 		'dateSelecteds.array' => 'Invalid time slot format.',
		// 		'dateSelecteds.min' => 'Please select at least one time slot.',
		// 		'dateSelecteds.*.date_format' => 'Invalid date format for selected time',
		// 	]
		// )->validate();

		/**
		 * format date selected from list date or range date to list date 
		 * @var string[] $dateSelecteds
		 */
		$dateSelecteds = $this->dateTimeHelper->getListDates($dateSelecteds, 'Y-m-d');
		$bookedTimes = $this->getBookedTimeByDateSelect($bookId, $dateSelecteds,);
		/**
		 * @var string[] $unavailableDates
		 */
		$unavailableDates = [];
		foreach ($dateSelecteds as $date) {
			if (isset($bookedTimes[$date]) && $bookedTimes[$date] >= $book->{Book::QTY}) {
				$unavailableDates[] = $date;
			}
		}
		return empty($unavailableDates);
	}

	/**
	 * get expect order by id
	 * @param string $expectId
	 * @return \Thanhnt\Amuaglobal\Models\ExpectOrder
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function getExpectOrderById(string $expectId)
	{
		$expectOrder = $this->expectOrder->with('item')->findOrFail($expectId);
		/**
		 * associate book(gán lại item detail cho order hoặc expectorder do mặc định nó trả về Model của product)
		 * tùy vào từng loại sản phẩm mà có sự chuyển đổi phù hợp.
		 */
		$expectOrder->item()->associate(Book::findOrFail($expectOrder->item_id));
		return $expectOrder;
	}
}
