<?php

namespace Thanhnt\Acarglobal\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Thanhnt\Acarglobal\Models\AcarConfig;

class ShareConfig
{

    public function __construct() {}

    /**
     * Handle an incoming request
     * @param \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cacheKey = 'acar_config';
        $configs = Cache::rememberForever($cacheKey, function () {
            return AcarConfig::all()->keyBy(AcarConfig::KEY)->map(function ($config) {
                return $config->{AcarConfig::VALUE};
            })->toArray();
        });

        Inertia::share('acar_config', $configs);
				/**
				 * set date_range mode for filter carFix by time
				 */
				Inertia::share('mode', 'date_range');
        return $next($request);
    }
}
