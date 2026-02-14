<?php

namespace Thanhnt\Amuaglobal\Listeners;

use Thanhnt\Amuaglobal\Events\OrderSave;
use Thanhnt\Amuaglobal\Models\Order;
use Thanhnt\Amuaglobal\Models\OrderTime;
use Thanhnt\Amuaglobal\Models\Types\OrderInterface;
use Thanhnt\Amuaglobal\Models\Types\OrderTimeInterface;

use function Illuminate\Log\log;

final class OrderSaveListen
{
	public function handle(OrderSave $event)
	{
		/**
		 * @var \Thanhnt\Amuaglobal\Models\Order $order
		 */
		$order = $event->order;
		$this->saveOrderTime($order);
		// ghi log
		log('===> OrderSaveListen event fired: ' . $order->id);
	}

	/**
	 * update order time for selected date
	 * @param \Thanhnt\Amuaglobal\Models\Order $order
	 * @return void
	 */
	protected function saveOrderTime(Order $order)
	{
		$selectedDates = $order->{OrderInterface::SELECTED_TIME};
		/**
		 * loop selected date and sync order time
		 */
		foreach ($selectedDates as $date) {
			/**
			 * @var \Thanhnt\Amuaglobal\Models\OrderTime $orderTime
			 */
			$orderTime = OrderTime::where(OrderTimeInterface::DATE, $date)->first();
			if ($orderTime) {
				$orderTime->{OrderTimeInterface::ORDER_IDS} = array_unique([...$orderTime->{OrderTimeInterface::ORDER_IDS}, $order->id]);
				$orderTime->{OrderTimeInterface::ITEM_ID} = array_unique([...$orderTime->{OrderTimeInterface::ITEM_ID}, $order->item_id]);
			} else {
				$orderTime = new OrderTime();
				$orderTime->{OrderTimeInterface::ORDER_IDS} = [$order->id];
				$orderTime->{OrderTimeInterface::ITEM_ID} = [$order->item_id];
			}
			$orderTime->{OrderTimeInterface::DATE} = $date;
			$orderTime->save();
		}
	}
}
