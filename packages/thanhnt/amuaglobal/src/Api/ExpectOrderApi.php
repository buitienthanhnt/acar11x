<?php

namespace Thanhnt\Amuaglobal\Api;

use Thanhnt\Amuaglobal\Models\ExpectOrder;

class ExpectOrderApi
{
	public function __construct(
		protected ExpectOrder $expectOrder
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * get expect order by id
	 * @param string $expectId
	 * @return \Thanhnt\Amuaglobal\Models\ExpectOrder
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function getExpectOrderById(string $expectId)
	{
		return $this->expectOrder->with('item')->findOrFail($expectId);
	}
}
