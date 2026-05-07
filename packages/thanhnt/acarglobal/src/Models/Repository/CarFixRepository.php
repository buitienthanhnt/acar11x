<?php

namespace Thanhnt\Acarglobal\Models\Repository;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Thanhnt\Acarglobal\Models\AcarConfig;
use Thanhnt\Acarglobal\Models\CarFix as ModelsCarFix;
use Thanhnt\Acarglobal\Models\Types\AcarConfigInterface;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;
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
		$status = $this->request->get(CarFixInterface::STATUS);

		return $this->carFix->query()->where(function ($query) use ($status) {
			if ($status) {
				$query->where(CarFixInterface::STATUS, '=', $status);
			}
		})->whereHas('car', function ($query) {
			if ($this->request->get('search')) {
				$query->whereAny(
					[CarInterface::KEY, CarInterface::VIN, CarInterface::YEAR, CarInterface::SUSPENSION, CarInterface::TYPE],
					'LIKE',
					"%" . $this->request->get('search') . "%"
				);
			}
		})->when($this->request->get('from'), function ($query) use ($status) { // filter by date
			if (!$this->request->get('to')) {
				/**
				 * nếu chỉ có from mà không có to nghĩa là người dùng đang xem theo ngày.
				 * Cái này lưu ý cho trạng thái processing bời vì:
				 * Nó cần lấy cả các xe đang có trạng thái processing đã cập nhập trước đó(nghĩa là bao gồm các xe đang sửa từ trước nhưng chưa xong)
				 */
				if (!$status || $status === 'done') {
					$query->whereDate(!$status ? 'created_at' : 'updated_at', '=', $this->request->get('from'));
				}
				// if ($status) {
				// 	$query->whereDate('updated_at', '=', $this->request->get('from'));
				// } else {
				// 	$query->whereDate('updated_at', '=', $this->request->get('from'))
				// 		->orWhereDate('created_at', '=', $this->request->get('from'));
				// }
			}
		})
			->when($this->request->get('to'), function ($query) use ($status) {   // filter by date
				/**
				 * Nếu có cả from và to thì sẽ lấy các xe có thời gian theo upated_at trong khoảng thời gian đó.
				 * Cho nên cả trạng thái done hay process đều sẽ hoạt động được
				 */
				if (!$status || $status === 'done') {
					$query->whereDate(!$status ? 'created_at' : 'updated_at', '>=', $this->request->get('from'))
						->whereDate(!$status ? 'created_at' : 'updated_at', '<=', $this->request->get('to'));
				}
				// if ($status) {
				// 	$query->whereDate('updated_at', '>=', $this->request->get('from'))
				// 		->whereDate('updated_at', '<=', $this->request->get('to'));
				// } else {
				// 	$query->where(function ($query) {
				// 		$query->whereDate('created_at', '>=', $this->request->get('from'))
				// 			->whereDate('created_at', '<=', $this->request->get('to'));
				// 	})->orWhere(function ($query) {
				// 		$query->whereDate('updated_at', '>=', $this->request->get('from'))
				// 			->whereDate('updated_at', '<=', $this->request->get('to'));
				// 	});
				// }

				// $query->whereDate($status === 'done' ? 'updated_at' : 'created_at', [$this->request->get('from'), $this->request->get('to')]);
			})
			->orderBy('created_at', 'desc')->with('car')->with('activities')->paginate(8);
	}

	/**
	 * get all carFix done
	 * @param string $from
	 * @param string $to
	 * @param $month '2024-05'
	 * @param $year '2024'
	 * @return mixed
	 */
	public function carFixDone(string $from = '', string $to = '', $month = null, $year = null)
	{
		if ($year) {
			$carFixDones = $this->carFix->where(CarFixInterface::STATUS, '=', CarFixInterface::STATUS_DONE)
				->whereYear('updated_at', $year)
				->with('car')
				->with('activities')
				->get();
		} else if ($month) {
			$date = Carbon::createFromFormat('Y-m', $month);
			$carFixDones = $this->carFix->where(CarFixInterface::STATUS, '=', CarFixInterface::STATUS_DONE)
				->whereMonth('updated_at', $date->month)
				->whereYear('updated_at', $date->year)
				->with('car')
				->with('activities')
				->get();
		} else {
			if ($from || $to) {
				$carFixDones = $this->carFix->where(CarFixInterface::STATUS, '=', CarFixInterface::STATUS_DONE)
					->when($from, function ($query) use ($from, $to) {
						if ($to) {
							$query->whereDate('updated_at', '>=', $from);
						} else {
							$query->whereDate('updated_at', '=', $from);
						}
					})->when($to, function ($query) use ($to) {
						$query->whereDate('updated_at', '<=', $to);
					})->with('car')
					->with('activities')
					->get();
			} else {
				/**
				 * Theo mặc định sẽ lấy các xe đã hoàn thành trong tuần hiện tại.
				 */
				$carFixDones = $this->carFix->where(CarFixInterface::STATUS, '=', CarFixInterface::STATUS_DONE)
					->whereBetween('updated_at', [
						Carbon::now()->startOfWeek(),
						Carbon::now()->endOfWeek(),
					])->with('car')
					->with('activities')
					->get();
			}
		}

		$carFixDones->each(function ($item) {
			$item->totalCost = $this->cafixTotalCost($item);
		});

		$grouped = $carFixDones->groupBy(function ($item) use ($year) {
			if ($year) {
				// Nhóm theo định dạng theo tháng Y-m (Năm-Tháng)
				return $item->updated_at->format('Y-m');
			}
			// Nhóm theo định dạng theo ngày Y-m-d (Năm-Tháng-Ngày)
			return $item->updated_at->format('Y-m-d');
		});
		return $grouped;
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

	public function applyVat(int $carFixId)
	{
		$carFix = $this->carFix->find($carFixId);
		$vatConfig = AcarConfig::where(AcarConfig::KEY, '=', CarFixInterface::VAT)->first();
		$carFix->{CarFixInterface::VAT} = $carFix->{CarFixInterface::VAT} ? 0 : ($vatConfig->{AcarConfigInterface::VALUE} ?: 8);
		$carFix->save();
		return $carFix;
	}

	/**
	 * cafix total cost
	 * @param \Thanhnt\Acarglobal\Models\CarFix $carFix
	 */
	public function cafixTotalCost($carFix)
	{
		$totalCost = 0;
		foreach ($carFix->activities as $activity) {
			$totalCost += $activity->{ActivityInterface::PRICE} * $activity->{ActivityInterface::QTY};
		}
		return round($totalCost  + $totalCost * $carFix->{CarFixInterface::VAT} / 100, 2);
	}
}
