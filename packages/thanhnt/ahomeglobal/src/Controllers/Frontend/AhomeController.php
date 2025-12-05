<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

final class AhomeController extends Controller
{
	function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	public function default() : string {
		return 'default for test home of ahome router';
	}

	public function home() {
		return Inertia::render('Ahomeglobal/Screens/Ahome', [
			"data" => 123,
		]);
	}
}
