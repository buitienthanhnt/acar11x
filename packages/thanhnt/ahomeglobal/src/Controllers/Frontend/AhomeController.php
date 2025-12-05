<?php

namespace Thanhnt\Ahomeglobal\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Thanhnt\Ahomeglobal\Models\Home;

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

	public function createHome() {
		return Home::factory()->create();
	}
}
