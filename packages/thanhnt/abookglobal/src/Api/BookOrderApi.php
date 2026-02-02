<?php

namespace Thanhnt\Abookglobal\Api;

use Thanhnt\Abookglobal\Events\BookOrderSave;
use Thanhnt\Abookglobal\Models\Book;
use Thanhnt\Abookglobal\Models\BookExpectOrder;
use Thanhnt\Abookglobal\Models\BookOrder;
use Thanhnt\Abookglobal\Models\BookOrderDetail;
use Thanhnt\Abookglobal\Models\BookOrderTime;
use Thanhnt\Abookglobal\Models\Types\BookOrderInterface;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;

final class BookOrderApi
{
	public function __construct(
		protected Book $book,
		protected BookOrder $bookOrder,
		protected BookExpectOrder $bookExpectOrder,
		protected BookOrderDetail $bookOrderDetail,
		protected BookOrderTime $bookOrderTime,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	public function placeOrder(int $bookId, array $dateSelected, int $qty = 1)
	{
		/**
		 * save order
		 */
		$order = $this->saveOrder(...func_get_args());
		return $order;
		/**
		 * save order time
		 * will save in event listener because has full data for generate time
		 */

		// $this->saveOrderTime($order);
		// $this->saveOrderDetail();
	}

	/**
	 * @param int $bookId
	 * @param string[] $dateSelected
	 * @param int $qty
	 * @return \Thanhnt\Abookglobal\Models\BookOrder
	 */
	protected function saveOrder(int $bookId, array $dateSelected, int $qty = 1)
	{
		$book = $this->book->findOrFail($bookId);
		/**
		 * @var \Thanhnt\Abookglobal\Models\BookOrder $bookOrder
		 */
		$bookOrder = $this->bookOrder->newInstance();
		$bookOrder->{BookOrderInterface::BOOK_ID} = $book->id;
		$bookOrder->{BookOrderInterface::TOTAL_PRICE} = $book->price * $qty;
		$bookOrder->{BookOrderInterface::QTY} = $qty;
		$bookOrder->{BookOrderInterface::STATUS} = 'pending';
		$bookOrder->{BookOrderInterface::DATE_FROM} = min($dateSelected);
		$bookOrder->{BookOrderInterface::DATE_TO} = max($dateSelected);
		$bookOrder->{BookOrderInterface::SELECTED_TIME} = $this->dateTimeHelper->getListDates($dateSelected, 'Y-m-d');
		$bookOrder->save();
		/**
		 * Dispatch event after save order
		 */
		BookOrderSave::dispatch($bookOrder);

		return $bookOrder;
	}

	/** 
	 * @param int $bookId
	 * @param string[] $dateSelecteds
	 * @return \Illuminate\Database\Eloquent\Collection<\Thanhnt\Abookglobal\Models\BookOrder>
	 */
	public function getOrderByDateSelect(int $bookId, array $dateSelecteds)
	{
		$orders = $this->bookOrder->where(BookOrderInterface::BOOK_ID, $bookId)
			->where(function ($query) use ($dateSelecteds) {
				foreach ($dateSelecteds as $date) {
					$query->orWhereJsonContains(BookOrderInterface::SELECTED_TIME, $date);
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
			foreach ($order->{BookOrderInterface::SELECTED_TIME} as $date) {
				if (isset($timeOrderBooked[$date])) {
					$timeOrderBooked[$date] += $order->{BookOrderInterface::QTY};
				} else {
					$timeOrderBooked[$date] = $order->{BookOrderInterface::QTY};
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
	 * @return \Thanhnt\Abookglobal\Models\BookExpectOrder
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function getExpectOrderById(string $expectId)
	{
		return $this->bookExpectOrder->findOrFail($expectId);
	}

	protected function saveOrderTime() {}

	protected function saveOrderDetail() {}
}
