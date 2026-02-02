<?php

namespace Thanhnt\Abookglobal\Listeners;

use Thanhnt\Abookglobal\Events\BookOrderSave;
use Thanhnt\Abookglobal\Models\BookOrder;
use Thanhnt\Abookglobal\Models\BookOrderTime;
use Thanhnt\Abookglobal\Models\Types\BookOrderInterface;
use Thanhnt\Abookglobal\Models\Types\BookOrderTimeInterface;

use function Illuminate\Log\log;

final class BookOrderSaveListen
{
	public function handle(BookOrderSave $event)
	{
		/**
		 * @var \Thanhnt\Abookglobal\Models\BookOrder $bookOrder
		 */
		$bookOrder = $event->bookOrder;
		$this->saveBookOrderTime($bookOrder);

		log('===> BookOrderSaveListen event fired: ' . $bookOrder->id);
	}

	protected function saveBookOrderTime(BookOrder $bookOrder)
	{
		$selectedDates = $bookOrder->{BookOrderInterface::SELECTED_TIME};
		foreach ($selectedDates as $date) {
			$orderTime = BookOrderTime::where(BookOrderTimeInterface::DATE, $date)->first();
			if ($orderTime) {
				$orderTime->{BookOrderTimeInterface::ORDER_IDS} = array_unique([...$orderTime->{BookOrderTimeInterface::ORDER_IDS}, $bookOrder->id]);
				$orderTime->{BookOrderTimeInterface::BOOK_ID} = array_unique([...$orderTime->{BookOrderTimeInterface::BOOK_ID}, $bookOrder->book_id]);
				$orderTime->{BookOrderTimeInterface::DATE} = $date;
				$orderTime->save();
				continue;;
			}

			$newBookOrderTime = new BookOrderTime();
			$newBookOrderTime->{BookOrderTimeInterface::ORDER_IDS} = [$bookOrder->id];
			$newBookOrderTime->{BookOrderTimeInterface::BOOK_ID} = [$bookOrder->book_id];
			$newBookOrderTime->{BookOrderTimeInterface::DATE} = $date;
			$newBookOrderTime->save();
		}
	}
}
