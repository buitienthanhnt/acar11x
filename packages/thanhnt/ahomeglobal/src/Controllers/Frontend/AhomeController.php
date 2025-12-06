<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Models\Home;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;

final class AhomeController extends Controller
{
	function __construct(
		protected HomeApi $homeApi,
		protected RoomApi $roomApi,
	) {
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

	/**
	 * @return \Inertia\Response
	 */
	public function listHome()
	{
		$homes = Home::with('rooms')->get();
		return Inertia::render('Ahomeglobal/Screens/HomeList', [
			"homes" => $homes,
		]);
	}

	/**
	 * js library for calendar time
	 * https://www.npmjs.com/package/react-calendar
	 * https://www.npmjs.com/package/react-calendar-timeline
	 */
	public function homeDetail(Request $request, $home)
	{
		/**
		 * please get all order of the room then pass to Js page for disable the day selected.
		 */
		// dd($request->integer('room'), $home);

		return Inertia::render(
			'Ahomeglobal/Screens/HomeDetail',
			[
				'detail' => $this->homeApi->getHomeDetail($home),
				'room_selected' => Inertia::defer(function()use($request){
					if (!$request->integer('room')) {
						return null;
					}
					$room = $this->roomApi->getRoomDetail($request->integer('room'));
					return $room;
				}),
			],
		);
		return $home;
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
