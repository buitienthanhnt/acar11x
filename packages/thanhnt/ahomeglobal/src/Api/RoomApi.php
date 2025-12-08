<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\OrderTime;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\OrderTimeInterface;

final class RoomApi
{
	public function __construct(
		protected Room $roomModel,
		protected OrderTime $orderTime,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * @param int $roomId
	 * @return \Thanhnt\Ahomeglobal\Models\Room|null
	 */
	public function getRoomDetail(int $roomId)
	{
		return $this->roomModel->with('orders')->find($roomId);
	}

	/**
	 * @param int $roomId
	 * @return \Thanhnt\Ahomeglobal\Models\Room|null
	 */
	public function getRoomDetailNoOrders(int $roomId)
	{
		return $this->roomModel->find($roomId);
	}

	public function orderTimes(Room $room) {
        $orderTime = OrderTime::whereNowOrFuture(OrderTime::DATE)
								->whereJsonContains(OrderTimeInterface::ROOM_IDS, $room->id)
								->select(OrderTimeInterface::DATE)
								->get()
								->pluck(OrderTimeInterface::DATE);
		return $orderTime->toArray();
    }
}
