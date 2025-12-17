<?php

namespace Thanhnt\Ahomeglobal\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class MergeInertiaConfig
{
	/**
	 * Handle an incoming request
	 * merge ahomeglobal config mode.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		Inertia::share('mode', config('ahomeglobal.mode'));
		return $next($request);
	}
}
