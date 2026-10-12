<?php

namespace Thanhnt\Acarglobal\Actions;

use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\CarFix;
use Thanhnt\Acarglobal\Models\Customer;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;
use Thanhnt\Acarglobal\Models\Types\CustomerInterface;
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
	 * @param array{customer: array{name: string, phone: string, address: string},
	 * key: string, suspension: string, type: string, year: string, vin: string, km: integer
	 * } $requestInfo
	 * @return \Thanhnt\Acarglobal\Models\Car
	 */
	public function registerCarinfo($requestInfo)
	{
		/**
		 * array_flip: đảo ngược key và value của mảng,
		 * mục đích là để lấy ra các key cần thiết từ mảng $requestInfo,
		 * array_intersect_key: chỉ lấy những key có trong CustomerInterface::FILLED_FIELDS
		 */
		$carCustomer = array_intersect_key(
			$requestInfo[CarInterface::R_CUSTOMER],
			array_flip(CustomerInterface::FILLED_FIELDS)
		);

		/**
		 * find or create customer info, nếu không tìm thấy thì tạo mới
		 */
		$customer = Customer::firstOrCreate(
			[
				Customer::PHONE => $requestInfo[CarInterface::R_CUSTOMER][Customer::PHONE],
				CustomerInterface::NAME => $requestInfo[CarInterface::R_CUSTOMER][CustomerInterface::NAME]
			],
			$carCustomer,
		);

		$newCar = Car::updateOrCreate(
			[
				CarInterface::KEY => $requestInfo[CarInterface::KEY],
			],
			array_merge($this->formData(Car::FORM_FIELDS, $requestInfo), [
				CarInterface::CUSTOMER_ID => $customer->id,
			]),
		);
		return $newCar;
	}

	/**
	 * Register car fix
	 * @param \Thanhnt\Acarglobal\Models\Car $carInfo
	 * @param array{customer: array{name: string, phone: string, address: string},
	 * key: string, suspension: string, type: string, year: string, vin: string, km: integer
	 * } $requestInfo
	 * @return \Thanhnt\Acarglobal\Models\CarFix
	 */
	public function registerCarFix($carInfo, array $requestInfo)
	{
		$carFix = CarFix::create([
			CarFixInterface::STATUS => CarFixInterface::STATUS_WAIT,
			CarFixInterface::CAR_ID => $carInfo->id,
			CarFixInterface::KM => $requestInfo[CarFixInterface::KM] ?? null,
		]);
		return $carFix;
	}
}
