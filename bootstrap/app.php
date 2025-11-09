<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

const ADMIN_PREFIX = 'adminhtml';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // Add your custom route file here
        using: function (Illuminate\Routing\Router $router) {
            // https://laravel.com/docs/12.x/routing#routing-customization
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            $router->middleware('web')
                ->group(base_path('routes/admin.php')) // Example for admin routes
                ->group(base_path('routes/test.php')) // Example for test routes
                ->group(base_path('routes/auth.php')) // Example for auth routes
                ->group(base_path('routes/add.php')); // Example for add routes
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // https://laravel.com/docs/12.x/csrf
        $middleware->validateCsrfTokens(except: VerifyCsrfToken::EXCEPT);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'adminVerify' => \App\Http\Middleware\AdminVerify::class,
            'adminPermission' => \App\Http\Middleware\AdminPermission::class,
            // 'languageBoot' => \App\Http\Middleware\LanguageBoot::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
