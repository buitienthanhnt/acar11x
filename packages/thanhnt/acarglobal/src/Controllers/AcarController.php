<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Thanhnt\Acarglobal\Actions\{ActivityAction, CarAction, CarImport, EmployeeAction, WorkTimeAction};
use Thanhnt\Acarglobal\Models\{AcarConfig, Car, Customer, Employee};
use Thanhnt\Acarglobal\Models\Repository\CarFixRepository;
use Thanhnt\Acarglobal\Models\Types\{ActivityInterface, CarFixInterface, CarInterface, CustomerInterface, WorkTimeInterface};
use Thanhnt\Acarglobal\Request\ImportCarRequest;
use Thanhnt\Amuaglobal\Models\Product;
use Thanhnt\Amuaglobal\Models\Repository\CategoryRepository;
use Thanhnt\Amuaglobal\Models\Types\CategoryInterface;
use Thanhnt\Amuaglobal\Models\Types\ProductInterface;

final class AcarController extends Controller
{
	public function __construct(
		protected CarImport $carImport,
		protected CarFixRepository $carFixRepository,
		protected ActivityAction $activityAction,
		protected EmployeeAction $employeeAction,
		protected WorkTimeAction $workTimeAction,
		protected CarAction $carAction,
		protected CategoryRepository $categoryRepository,
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

	/**
	 * Danh sách xe
	 * @return \Inertia\Response
	 */
	public function carList()
	{
		return Inertia::render('Acarglobal/Car/CarList', [
			'cars' => $this->carAction->carPaginate(),
		]);
	}

	public function carHistory(string $key)
	{
		return Inertia::render('Acarglobal/Car/CarHistory', [
			'car' => $this->carAction->carDetail($key),
		]);
	}

	/**
	 * Khởi tạo hồ sơ thông tin xe vào
	 */
	public function import(Request $request)
	{
		$selected = null;
		// if ($request->get('search') && strlen($request->get('search')) > 2) {
		// 	$cars = Car::whereAny(
		// 		[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
		// 		'LIKE',
		// 		"%" . $request->get('search') . "%"
		// 	)->get()->unique(CarInterface::KEY)->values();

		// 	dd($cars->toArray());
		// }

		/**
		 * Tìm kiếm khách hàng theo tên, nếu có tham số 'customer' trong request
		 */
		if ($request->get('customer')) {
			$customers = Customer::where(CustomerInterface::NAME, 'LIKE', "%" . $request->get('customer') . "%")->get();
		}

		/**
		 * Tìm kiếm xe theo biển số xe, nếu có tham số 'selected' trong request
		 */
		if ($request->get('selected')) {
			$selected = Car::with('customer')->where(CarInterface::KEY, $request->get('selected'))->first();
		}

		$form_fields = Car::FORM_FIELDS;
		if ($selected) {
			foreach ($form_fields as &$value) {
				$value['value'] = $selected->{$value['name']};
			}
		}

		return Inertia::render('Acarglobal/Car/ImportCar', [
			'form_fields' => $form_fields,
			'cars' => strlen($request->get('search')) > 2 ? Car::whereAny(
				[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
				'LIKE',
				"%" . $request->get('search') . "%"
			)->get()->unique(CarInterface::KEY)->values() :  [],
			'customers' => $customers ?? [],
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
	 * @param int $id
	 */
	public function carFixDetail(int $id)
	{
		/**
		 * @var \Thanhnt\Acarglobal\Models\CarFix $carFix
		 */
		return Inertia::render('Acarglobal/Car/CarFix', [
			'carFix' => $this->carFixRepository->carFixDetail($id),
			'categories' => Inertia::optional(fn() => $this->categoryRepository->allCategory()),
			'products' => Inertia::optional(fn() => Product::when( // fillter product by search keyword if exist
				request('k_search'),
				function ($query, $search) {
					$query->whereAny([ProductInterface::NAME, ProductInterface::SKU], 'like', '%' . $search . '%');
				}
			)->when( // fillter product by category if exist
				request('cat'),
				function ($query, $cat) {
					$query->has(
						ProductInterface::R_CATEGORIES,
						callback: function ($query) use ($cat) {
							$query->where(CategoryInterface::ID, $cat);
						}
					);
				}
			)->paginate(6, ['*'], 'product_page')), // paginate products with 6 items per page
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
