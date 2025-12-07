<?php

namespace Thanhnt\Ahomeglobal\Api;

use Thanhnt\Ahomeglobal\Models\Room;

final class RoomApi
{
	public function __construct(
		protected Room $roomModel,
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
}
