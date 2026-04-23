<?php

namespace Thanhnt\Acarglobal\Request;

use Illuminate\Foundation\Http\FormRequest;
use Thanhnt\Acarglobal\Models\Types\CarInterface;

final class ImportCarRequest extends FormRequest
{

	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			CarInterface::KEY => ['required', 'string', 'max:25'],
		];
	}
}
