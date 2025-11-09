<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /**
         * pass if env is api or adminhtml.
         */
        if (isApiEnv() || isAdminEnv()) {
            return [];
        }

        $this->globalShare();

        /**
         * define for list element of top menu
         */
        $topMenu = [
            ['name' => 'page', 'icon' => '', 'url' => '/list'],
            ['name' => 'account', 'icon' => '', 'url' => '/account'],
            ['name' => 'Docs', 'icon' => '', 'url' => '/docs'],
            ['name' => 'About Us', 'icon' => '', 'url' => '/about'],
            ['name' => 'search', 'icon' => '', 'url' => '/search'],
        ];

        /**
         * return value for share parameter all view with InertiaRequests.
         */
        return [
            ...parent::share($request),
            'topMenu' => $topMenu,
            'responseData' => $request->session()->get('responseData'), // include for response data form.
        ];
    }

     /**
     * auto share for all pages.
     */
    protected function globalShare(): void {
        Inertia::share('foot_page', Inertia::optional(function(){
            return [
                'app_name' => 'adoc.dev',
                'dev' => 'thanh.nt',
                'email' => 'adoc@gmail.com',
            ];
        }));
    }
}
