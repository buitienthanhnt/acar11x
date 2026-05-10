<?php

namespace Thanhnt\Acarglobal\Actions;

use Illuminate\Http\Request;
use Thanhnt\Acarglobal\Helper\FormData;
use Thanhnt\Acarglobal\Models\Employee;
use Thanhnt\Acarglobal\Models\Types\EmployeeInerface;

final class EmployeeAction
{

	use FormData;

	public function __construct(
		protected Request $request,
		protected Employee $employee,
	) {
		// throw new \Exception('Not implemented');
	}

	public function registerEmployee()
	{
		return $this->employee->updateOrCreate(
			[
				EmployeeInerface::EMAIL => $this->request->get(EmployeeInerface::EMAIL),
			],
			$this->formData(Employee::FORM_FIELDS, $this->request->all())
		);
	}
}
