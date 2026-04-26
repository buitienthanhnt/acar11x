<?php

namespace Thanhnt\Acarglobal\Models\Repository;

use Illuminate\Http\Request;
use Thanhnt\Acarglobal\Models\CarFix as ModelsCarFix;
use Thanhnt\Acarglobal\Models\Types\CarFixInterface;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

final class CarFixRepository
{
	public function __construct(
		protected Request $request,
		protected ModelsCarFix $carFix,
	) {
		// throw new \Exception('Not implemented');
	}

	/**
	 * get all carFix
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function all()
	{
		return $this->carFix->query()->where(function ($query) {
			if ($this->request->get(CarFixInterface::STATUS)) {
				$query->where(CarFixInterface::STATUS, '=', $this->request->get(CarFixInterface::STATUS));
			}
		})->whereHas('car', function ($query) {
			if ($this->request->get('search')) {
				$query->whereAny(
					[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
					'LIKE',
					"%" . $this->request->get('search') . "%"
				);
			}
		})->orderBy('created_at', 'desc')->with('car')->with('activities')->paginate(12);
	}


	/**
	 * get carFix detail
	 */
	public function carFixDetail(int $id)
	{
		return $this->carFix->query()->with('car')->with('activities')->find($id);
	}

	/**
	 * update status
	 * @param int $id
	 * @param string $status
	 * @return \Thanhnt\Acarglobal\Models\CarFix
	 */
	public function updateStatus(int $id, string $status)
	{
		$carFix = $this->carFix->find($id);
		$carFix->status = $status;
		$carFix->save();
		return $carFix;
	}
}
