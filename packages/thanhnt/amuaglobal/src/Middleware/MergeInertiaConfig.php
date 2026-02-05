<?php

namespace Thanhnt\Amuaglobal\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Thanhnt\Amuaglobal\Api\CartApi;

class MergeInertiaConfig
{
	public function __construct(private CartApi $cartApi) {}

	/**
	 * Handle an incoming request
	 * @param \Illuminate\Http\Request $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		/**
		 * share date time mode config to inertia
		 * date_range or list_date 
		 */
		Inertia::share('mode', config('amuaglobal.mode'));
		/**
		 * share has cart active to inertia
		 */
		if ($this->cartApi->getCart()) {
			Inertia::share('activeCart', true);
		}
		return $next($request);
	}
}
