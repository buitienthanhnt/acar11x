<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Api\CartApi;
use Thanhnt\Ahomeglobal\Api\HomeApi;
use Thanhnt\Ahomeglobal\Api\RoomApi;
use Thanhnt\Ahomeglobal\Helper\DateTimeHelper;
use Thanhnt\Ahomeglobal\Models\Order;
use Thanhnt\Ahomeglobal\Models\Room;
use Thanhnt\Ahomeglobal\Models\Types\RoomInterface;
use Thanhnt\Ahomeglobal\Api\OrderApi;
use Thanhnt\Ahomeglobal\Mail\OrderEmail;
use Thanhnt\Ahomeglobal\Models\Types\HomeInterface;

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
		/**
		 *  Magento Solution Specialist, Magento Certified Developer có nhiều năm kinh nghiệm.
		 */
		$filters = $request->get('filters',);

		return Inertia::render('Ahomeglobal/Screens/HomePage', [
			"homes" => isset($filters[HomeInterface::DISTRICT]) ?
				$this->homeApi->getHomeByDistrict($filters[HomeInterface::DISTRICT])->whereHas(HomeInterface::ROOMS)->with(HomeInterface::ATTR)->paginate(6) :
				[],
			'rooms' => isset($filters[HomeInterface::DISTRICT]) ?
				$this->roomApi->paginateRoomWithFilter([...($filters ?: []), 'home_id' => $this->homeApi->getHomeIdfilterByCustomAttr($filters)]) :
				null,
			'allFilters' => $this->roomApi->allFilters(selected: $filters),
			"filters" => $filters,
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

	public function sendMailOrder()
	{
		$orderIncrement = '01kdzrw3e0jxsz1czkdn9vn7v6';
		$order = $this->orderApi->getOrderDetailByIncrement($orderIncrement);
		// dd($order->detail->email);
		// return view('emails.order-email', compact('order'));

		Mail::to($order->detail->email)->send(new OrderEmail($order));
		return 123;
	}

	public function streamText()
	{
		return response()->streamDownload(function () {
			$file = fopen('path/to/large-file.txt', 'r');
			while (!feof($file)) {
				echo fread($file, 1024 * 8); // Stream in 8KB chunks
				flush(); // Force output to browser
			}
			fclose($file);
		}, 'export.txt');
	}

	/**
	 * stream large file
	 * live for inertiaJs component
	 */
	public function stream()
	{
		$filePath = 'private/large-file.txt';

		return response()->stream(function () use ($filePath) {
			$handle = fopen(storage_path('app/' . $filePath), 'r');
			while (($line = fgets($handle)) !== false) {
				// Định dạng SSE: bắt đầu bằng "data: " và kết thúc bằng "\n\n"
				echo "data: " . $line . "\n\n";
				ob_flush();
				flush();
				// usleep(1000); // same as sleep; hovever 1000 = 1ms: https://www.php.net/manual/en/function.usleep.php
			}
			fclose($handle);
		}, 200, [
			'Content-Type' => 'text/event-stream',
			'Cache-Control' => 'no-cache',
			'Connection' => 'keep-alive',
			'X-Accel-Buffering' => 'no', // Cần thiết cho Nginx để tắt buffering
			// 'Content-Length' => Storage::size($filePath),
		]);
	}
}
