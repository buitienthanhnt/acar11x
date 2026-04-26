<?php

namespace Thanhnt\Amuaglobal\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
	public function __construct() {}

	/**
	 * Handle an incoming request
	 * @param \Illuminate\Http\Request $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		 $response = $next($request);

    // Ép trình duyệt không được lưu cache trang này
    $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
    $response->headers->set('Pragma', 'no-cache');

		// dd(123);
    return $response;

		return $next($request);
		// return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
		// 	->header('Pragma', 'no-cache')
		// 	->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
	}
}
