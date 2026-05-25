<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Thanhnt\Acarglobal\Actions\ActivityAction;
use Thanhnt\Acarglobal\Actions\CarImport;
use Thanhnt\Acarglobal\Actions\EmployeeAction;
use Thanhnt\Acarglobal\Actions\WorkTimeAction;
use Thanhnt\Acarglobal\Models\AcarConfig;
use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\Employee;
use Thanhnt\Acarglobal\Models\Repository\CarFixRepository;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Models\Types\WorkTimeInterface;
use Thanhnt\Acarglobal\Request\ImportCarRequest;
use Thanhnt\Amuaglobal\Models\Product;

final class AcarController extends Controller
{

	public function __construct(
		protected CarImport $carImport,
		protected CarFixRepository $carFixRepository,
		protected ActivityAction $activityAction,
		protected EmployeeAction $employeeAction,
		protected WorkTimeAction $workTimeAction,
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

	public function setting()
	{
		$configs = AcarConfig::all()->keyBy(AcarConfig::KEY)->map(function ($config) {
			return $config->{AcarConfig::VALUE};
		});
		return Inertia::render('Acarglobal/Setting', [
			'configs' => $configs,
		]);
	}

	public function settingStore(Request $request)
	{
		$data = $request->all();
		$formatData = [];
		foreach ($data as $key => $value) {
			$formatData[] = [
				AcarConfig::KEY => $key,
				AcarConfig::VALUE => $value,
			];
		}
		AcarConfig::upsert($formatData, [AcarConfig::KEY], [AcarConfig::VALUE]);
		Cache::forget('acar_config');
		return redirect()->to('acar/setting')->setStatusCode(303);
	}

	public function applyVat(int $id)
	{
		$this->carFixRepository->applyVat($id);
		return redirect()->to(url()->previous())->setStatusCode(303);
	}

	public function carList()
	{
		return Inertia::render('Acarglobal/Car/CarList', [
			'cars' => Car::orderBy('created_at', 'desc')->paginate(12),
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
			'products' => Inertia::optional(fn() => Product::when(
				request('k_search'),
				function ($query, $search) {
					$query->whereAny(['name', 'sku'], 'like', '%' . $search . '%');
				}
			)->paginate(6, ['*'], 'product_page')),
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
		// return redirect()->route('car.fix.detail', ['id' => $act?->car_fix_id])->setStatusCode(303);
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

	public function thongke(Request $request)
	{
		$car_fix_dones = $this->carFixRepository->carFixDone(
			from: $request->get('from', ''),
			to: $request->get('to', ''),
			month: $request->get('m', ''),
			year: $request->get('y', '')
		);

		return Inertia::render("Acarglobal/Car/ThongKe", [
			'car_fix_dones' => $car_fix_dones,
		]);
	}

	public function chamCong(Request $request)
	{
		$date = $request->get(WorkTimeInterface::DATE, date('Y-m-d'));

		$employess = Employee::all();
		return Inertia::render('Acarglobal/Car/ChamCong', [
			'employess' => $employess,
			'date' => $date,
			'workTimes' => $this->workTimeAction->getWorkTimeByDate($date),
		]);
	}

	public function addEmployee()
	{
		$this->employeeAction->registerEmployee();
		return redirect()->to('/acar/cham-cong')->setStatusCode(303);
	}

	public function saveWorkTime(Request $request)
	{
		$this->validate($request, [
			WorkTimeInterface::DATE => ['required'],
		]);

		$this->workTimeAction->saveWorkTime($request->all());
		return redirect()->to(url('/acar/work-history',), 303);

		return redirect()->to(url('/acar/cham-cong', [
			WorkTimeInterface::DATE => $request->get(WorkTimeInterface::DATE),
		]), 303);
	}

	public function workHistory()
	{
		return Inertia::render('Acarglobal/Car/WorkHistory', [
			'workTimes' => $this->workTimeAction->workHistory(),
			'employees' => $employees = Employee::all()->keyBy('id'),
		]);
	}

	public function removeCarFix(int $id)
	{
		$this->carFixRepository->removeCarFix($id);
		return redirect()->to(route('acar.home'));
	}

	public function phuTung()
	{
		return Inertia::render('Acarglobal/Car/PhuTung');
	}

	public function addPhuTung(Request $request) {}


	// public function __invoke()
	// {
	// 	return ('this is message from AcarController __invoke function');
	// 	throw new \Exception('Not implemented');
	// }
}
