<?php

namespace Thanhnt\Acarglobal\Actions;

use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Activity;
use Thanhnt\Acarglobal\Models\Types\ActivityInterface;

final class ActivityAction
{

	use FormData;

	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	public function onAddActivity(array $data)
	{
		$formData = $this->formData(ActivityInterface::FORM_FIELDS, $data);
		// $formData[ActivityInterface::STATUS] = ActivityInterface::STATUS_WAIT;
		Activity::create($formData);
	}

	/**
	 * Xoa cong viec
	 * @param int $id
	 * @return void
	 */
	public function removeActivity(int $id)
	{
		$activity = Activity::find($id);
		$activity?->delete();
	}
}
