<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class AhomeController extends Controller
{
	function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	public function default(): string
	{
		return 'default for test home of ahome router';
	}

	public function home()
	{
		return Inertia::render('Ahomeglobal/Screens/Ahome', [
			"data" => 123,
		]);
	}

	public function createHome()
	{
		return Home::factory()->create();
	}

	public function listHome()
	{
		$homes = Home::with('rooms')->get();
		return $homes;
	}

	public function listRoom()
	{
		/**
		 * visible attribute" home_id, created_at (auto hidden in model).
		 */
		$rooms = Room::with('home')->get()->makeVisible([RoomInterface::HOME_ID, 'created_at']);
		return $rooms;
	}

	public function listOrder()
	{
		$orders = Order::with('room', 'home')->get();
		return $orders;
	}
}
