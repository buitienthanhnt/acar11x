<?php

namespace Thanhnt\Amuaglobal\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Thanhnt\Amuaglobal\Models\Order;

final class OrderSave
{

	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(
		public Order $order,
	) {
		// throw new \Exception('Not implemented');
	}
}
