<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Api\OrderApi;

final class AhomeController extends Controller
{
	function __construct(
		protected HomeApi $homeApi,
		protected RoomApi $roomApi,
		protected OrderApi $orderApi,
		protected CartApi $cartApi,
		protected DateTimeHelper $dateTimeHelper,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * show all of home are woking
	 * @return \Inertia\Response
	 */
	public function home(Request $request)
	{
		return Inertia::render('Ahomeglobal/Screens/HomePage', [
			"homes" => $this->homeApi->paginateHomeWithFilter($request->get('filters'), limit: 8),
			'rooms' => $this->roomApi->paginateRoomWithFilter($request->get('filters'), 8),
			'allFilters' => $this->roomApi->allFilters(selected: $request->get('filters')),
			"filters" => $request->get('filters'),
		]);
	}

	/**
	 * @param int $home
	 * @return \Inertia\Response
	 */
	public function homeDetail(\Illuminate\Http\Request $request, $home)
	{
		/**
		 * please get all order of the room then pass to Js page for disable the day selected.
		 */
		return Inertia::render(
			'Ahomeglobal/Screens/HomeDetail',
			[
				'homeDetail' => $this->homeApi->getHomeDetail($home),
				'roomSelected' => Inertia::defer(function () use ($request) {
					if (!$request->integer('room')) {
						return null;
					}
					$room = $this->roomApi->getRoomDetailNoOrders($request->integer('room'));
					return $room;
				}),
				'selectedDates' => $request->get('selectedDates', []), // pass selected dates from query string(can be from homelist filter or home detail selected dates)
			],
		);
		return $home;
	}

	/**
	 * get all room and link home to this
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function listRoom()
	{
		/**
		 * visible attribute" home_id, created_at (auto hidden in model).
		 */
		$rooms = Room::with('home')->get()->makeVisible([RoomInterface::HOME_ID, 'created_at']);
		return $rooms;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function listOrder()
	{
		$orders = Order::with(['room', 'home'])->get();
		return $orders;
	}

	/**
	 * get active room.
	 */
	public function activeRoom(Request $request)
	{
		/**
		 * list dates has sort min to max
		 * auto filter by mode date_range or list date
		 */
		$dates = ['2025-12-13', '2025-12-14', '2025-12-15'];
		$listActives = $this->orderApi->getActiveRoom($dates); // Room::whereNotIn('id', $conflicRooms)->get()->makeHidden(['booked_dates'])->toArray();
		dd($listActives->toArray());
	}
}
