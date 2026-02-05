<?php

namespace Thanhnt\Ahomeglobal\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MergeInertiaConfig
{
	public function __construct()
	{
		// throw new \Exception('Not implemented');
	}
	/**
	 * Handle an incoming request
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		return $next($request);
	}
}
