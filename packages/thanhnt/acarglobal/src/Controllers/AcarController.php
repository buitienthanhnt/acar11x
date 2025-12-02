<?php

namespace Thanhnt\Acarglobal\Controllers;

use App\Http\Controllers\Controller;

final class AcarController extends Controller {

	public function __invoke()
	{
		return('this is message from AcarController __invoke function');
		throw new \Exception('Not implemented');
	}
}