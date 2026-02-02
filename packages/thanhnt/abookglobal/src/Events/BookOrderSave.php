<?php

namespace Thanhnt\Abookglobal\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Thanhnt\Abookglobal\Models\BookOrder;

final class BookOrderSave
{

	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(
		public BookOrder $bookOrder,
	) {
		// throw new \Exception('Not implemented');
	}
}
