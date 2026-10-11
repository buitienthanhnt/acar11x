<?php

namespace Thanhnt\Acarglobal\Actions;

use Thanhnt\Acarglobal\Models\Car;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

final class CarAction
{
	public function __cosntruct() {}

	/**
	 * @return \Illuminate\Pagination\LengthAwarePaginator $carPaginate
	 */
	public function carPaginate()
	{
		/**
		 * Lấy danh sách xe và khách hàng liên quan, phân trang 12 bản ghi mỗi trang
		 * 
		 * @return \Illuminate\Pagination\LengthAwarePaginator $carPaginate
		 */
		$carPaginate = Car::with(CarInterface::R_CUSTOMER)->orderBy('created_at', 'desc')->paginate(12);

		return $carPaginate;
	}

	/**
	 * get car detail by key
	 * @return \Thanhnt\Acarglobal\Models\Car $carDetail
	 */
	public function carDetail(string $key)
	{
		/**
		 * Lấy thông tin chi tiết xe và khách hàng liên quan theo key
		 * 
		 * @return \Thanhnt\Acarglobal\Models\Car $carDetail
		 */
		$carDetail = Car::with(CarInterface::R_CUSTOMER)->with(CarInterface::R_CAR_FIX)->where(CarInterface::KEY, $key)->first();

		return $carDetail;
	}
}
