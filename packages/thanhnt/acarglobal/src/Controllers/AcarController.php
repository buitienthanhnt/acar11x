<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Thanhnt\Acarglobal\Actions\CarImport;
use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Request\ImportCarRequest;

final class AcarController extends Controller
{

	public function __construct(
		protected CarImport $carImport
	) {
		// throw new \Exception('Not implemented');
	}

	function index()
	{
		return Inertia::render('Acarglobal/Home');
	}

	public function import(Request $request)
	{
		$selected = null;
		if ($request->get('search') && strlen($request->get('search')) > 2) {
			$cars = Car::whereAny(
				[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
				'LIKE',
				"%" . $request->get('search') . "%"
			)->get();
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

	public function register(ImportCarRequest $request)
	{
		/**
		 * @var \Thanhnt\Acarglobal\Models\CarFix $carFix
		 */
		$carFix = $this->carImport->execute($request);
		
		return Inertia::render('Acarglobal/Car/CarFix', [
			'carFix' => $carFix->query()->with('car')->first(),
		]);
	}

	public function addCar()
	{
		Car::insert([
			CarInterface::KEY => '30k-33333',
			CarInterface::SUSPENSION => 'kia',
			CarInterface::TYPE => 'morning',
			CarInterface::KM => 12000,
			CarInterface::YEAR => Carbon::create(2020),
			CarInterface::VIN => 'klasnbdjbuabsdipp',
		]);

		return Car::all();
	}


	// public function __invoke()
	// {
	// 	return ('this is message from AcarController __invoke function');
	// 	throw new \Exception('Not implemented');
	// }
}
