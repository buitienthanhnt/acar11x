<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Acarglobal\Actions\ActivityAction;
use Thanhnt\Acarglobal\Actions\CarImport;
use Thanhnt\Acarglobal\Models\AcarConfig;
use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\Repository\CarFixRepository;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Request\ImportCarRequest;

final class AcarController extends Controller
{

	public function __construct(
		protected CarImport $carImport,
		protected CarFixRepository $carFixRepository,
		protected ActivityAction $activityAction,
	) {
		// throw new \Exception('Not implemented');
	}

	function index()
	{
		return Inertia::render('Acarglobal/Home', [
			'car_fixs' => Inertia::optional(fn() => $this->carFixRepository->all()),
			// 'car_fixs' => $this->carFixRepository->all(),
		]);
	}

	public function setting() {
		return Inertia::render('Acarglobal/Setting', [
			'configs' => AcarConfig::all(),
		]);
	}

	public function carList()
	{
		return Inertia::render('Acarglobal/Car/CarList', [
			'cars' => Car::paginate(12),
		]);
	}

	public function carHistory(string $key)
	{
		return Inertia::render('Acarglobal/Car/CarHistory', [
			'car' => Car::where(CarInterface::KEY, $key)->with('carFix')->first(),
		]);
	}

	/**
	 * Khởi tạo hồ sơ thông tin xe vào
	 */
	public function import(Request $request)
	{
		$selected = null;
		if ($request->get('search') && strlen($request->get('search')) > 2) {
			$cars = Car::whereAny(
				[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
				'LIKE',
				"%" . $request->get('search') . "%"
			)->get()->unique(CarInterface::KEY);
		}

		if ($request->get('selected')) {
			$selected = Car::where(CarInterface::KEY, $request->get('selected'))->first();
		}

		$form_fields = Car::FORM_FIELDS;
		if ($selected) {
			foreach ($form_fields as &$value) {
				$value['value'] = $selected->{$value['name']};
			}
		}

		return Inertia::render('Acarglobal/Car/ImportCar', [
			'form_fields' => $form_fields,
			'cars' => $cars ?? [],
			'selected' => $selected,
		]);
	}

	/**
	 * Khởi tạo hồ sơ thông tin xe vào
	 */
	public function register(ImportCarRequest $request)
	{
		/**
		 * @var \Thanhnt\Acarglobal\Models\CarFix $carFix
		 */
		$carFix = $this->carImport->execute($request);
		return redirect()->route('car.fix.detail', ['id' => $carFix->{CarFixInterface::ID}]);

		return Inertia::render('Acarglobal/Car/CarFix', [
			'carFix' => $this->carFixRepository->carFixDetail($carFix->{CarFixInterface::ID}),
		]);
	}

	/**
	 * Chi tiết hồ sơ thông tin xe và báo giá
	 */
	public function carFixDetail(int $id)
	{
		/**
		 * @var \Thanhnt\Acarglobal\Models\CarFix $carFix
		 */
		return Inertia::render('Acarglobal/Car/CarFix', [
			'carFix' => $this->carFixRepository->carFixDetail($id),
		]);
	}

	/**
	 * Thêm nội dung công việc bao gồm khai báo chi phí
	 */
	public function addActivity(Request $request)
	{
		$carFixId = $request->get(ActivityInterface::CAR_FIX_ID);

		$this->activityAction->onAddActivity($request->all());
		return redirect()->route('car.fix.detail', ['id' => $carFixId]);
	}



	public function updateStatus($id, Request $request): \Illuminate\Http\RedirectResponse
	{
		/**
		 * update status of carFix
		 */
		$this->carFixRepository->updateStatus($id, $request->get(ActivityInterface::STATUS));
		/**
		 * redirect to previous page
		 * luu tru url truoc khi chuyen trang
		 */
		return redirect()->to(url()->previous())->setStatusCode(303);
	}

	public function deleteActivity(int $id)
	{
		$act = $this->activityAction->removeActivity($id);
		return redirect()->to(url()->previous())->setStatusCode(303);
		// return redirect()->route('car.fix.detail', ['id' => $car_fix])->setStatusCode(303);
	}

	public function xuatLenh(int $car_fix)
	{
		$car_fix = $this->carFixRepository->carFixDetail($car_fix);
		return Inertia::render("Acarglobal/Car/LenhSuaChua", [
			'car_fix' => $car_fix,
		]);
	}

	// xuatHoaDon
	public function xuatHoaDon(int $car_fix)
	{
		$car_fix = $this->carFixRepository->carFixDetail($car_fix);
		return Inertia::render("Acarglobal/Car/HoaDon", [
			'car_fix' => $car_fix,
		]);
	}


	// public function __invoke()
	// {
	// 	return ('this is message from AcarController __invoke function');
	// 	throw new \Exception('Not implemented');
	// }
}
