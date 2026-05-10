<?php

namespace Thanhnt\Acarglobal\Actions;

use Illuminate\Http\Request;
use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Types\WorkTimeInterface;
use Thanhnt\Acarglobal\Models\WorkTime;

final class WorkTimeAction
{
	use FormData;

	public function __construct(
		protected Request $request,
		protected WorkTime $workTime,
	) {
		// throw new \Exception('Not implemented');
	}

	public function saveWorkTime(array $data)
	{

		// if (empty($data[WorkTimeInterface::TIME_WORK])) {
		// 	return $this->workTime->where(WorkTimeInterface::DATE, $data[WorkTimeInterface::DATE])->delete();
		// }
		return $this->workTime->updateOrCreate(
			[
				WorkTimeInterface::DATE => $data[WorkTimeInterface::DATE],
			],
			$this->formData(WorkTimeInterface::FORM_FIELDS, $data)
		);
	}


	public function getWorkTimeByDate(string $date)
	{
		$currentWorkTime = $this->workTime->where(WorkTimeInterface::DATE, $date)->get();
		return $currentWorkTime->first() ?: [];
	}

	public function workHistory()
	{
		$workTime = $this->workTime
			->whereMonth(WorkTimeInterface::DATE, $this->request->get('m', date('m')))
			->whereYear(WorkTimeInterface::DATE, $this->request->get('y', date('Y')))
			->orderBy(WorkTimeInterface::DATE, 'asc')
			->pluck(WorkTimeInterface::TIME_WORK, WorkTimeInterface::DATE,);

		return $workTime->toArray();
	}
}
