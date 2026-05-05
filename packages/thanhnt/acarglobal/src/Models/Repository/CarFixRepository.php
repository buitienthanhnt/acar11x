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
                    ->when($from, function ($query) use ($from,) {
                        $query->whereDate('updated_at', '>=', $from);
                    })->when($to, function ($query) use ($to) {
                        $query->whereDate('updated_at', '<=', $to);
                    })->with('car')
                    ->with('activities')
                    ->get();
            } else {
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
