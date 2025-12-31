<?php

namespace Thanhnt\Ahomeglobal\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Thanhnt\Ahomeglobal\Api\CartApi;

class MergeInertiaConfig
{
	public function __construct(private CartApi $cartApi)
	{
		// throw new \Exception('Not implemented');
	}
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
		if ($this->cartApi->getCart()) {
			Inertia::share('activeCart', true);
		}
		return $next($request);
	}
}
