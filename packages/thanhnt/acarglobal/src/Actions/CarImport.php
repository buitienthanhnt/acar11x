<?php

namespace Thanhnt\Acarglobal\Actions;

use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\CarFix;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Request\ImportCarRequest;

final class CarImport
{
	use FormData;


	public function execute(ImportCarRequest $request)
	{
		/**
		 * Register car info
		 */
		$carInfo = $this->registerCarinfo($request->all());

		/**
		 * Register car fix
		 */
		return $this->registerCarFix($carInfo, $request->all());
	}

	/**
	 * Register car info
	 * @param array $requestInfo
	 * @return \Thanhnt\Acarglobal\Models\Car
	 */
	public function registerCarinfo($requestInfo)
	{
		$newCar = Car::updateOrCreate(
			[
				CarInterface::KEY => $requestInfo[CarInterface::KEY],
			],
			$this->formData(Car::FORM_FIELDS, $requestInfo),
		);
		return $newCar;
	}

	/**
	 * Register car fix
	 * @param ImportCarRequest $request
	 * @param \Thanhnt\Acarglobal\Models\Car $carInfo
	 * @param array $requestInfo
	 * @return \Thanhnt\Acarglobal\Models\CarFix
	 */
	public function registerCarFix($carInfo, array $requestInfo)
	{
		$carFix = CarFix::create([
			CarFixInterface::STATUS => CarFixInterface::STATUS_WAIT,
			CarFixInterface::CAR_ID => $carInfo->id,
			CarFixInterface::KM => $requestInfo[CarFixInterface::KM],
		]);
		return $carFix;
	}
}
