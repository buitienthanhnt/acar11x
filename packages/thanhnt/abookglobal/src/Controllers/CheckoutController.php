<?php

namespace Thanhnt\Abookglobal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

final class CheckoutController extends Controller
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}

	public function addCart() {}

	public function checkout(Request $request)
	{
		dd($request->all());
	}
}
