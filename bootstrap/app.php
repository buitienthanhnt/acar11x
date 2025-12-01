<?php

use App\Exceptions\ApiException;
use App\Exceptions\PassException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            base_path('routes/admin.php'),
            base_path('routes/add.php'),
            base_path('routes/auth.php'),
            base_path('routes/test.php'),
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        /**
         * một thenclosure cho withRoutingphương thức. 
         * Trong closure này, bạn có thể đăng ký bất kỳ tuyến đường bổ sung nào cần thiết cho ứng dụng của mình:
         */
        then: function () {
            Route::middleware('api')
                ->prefix('webhooks')
                ->name('webhooks.')
                ->group(base_path('routes/webhooks.php'));
        },
        // Add your custom route file here(Nếu đã dùng: using thì không định nghĩa được api theo cách trên mà phải định nghĩa lại trong using.)
        // Khi đối số này được truyền, không có tuyến đường HTTP nào được khung đăng ký và bạn có trách nhiệm đăng ký thủ công tất cả các tuyến đường:
        // using: function (Illuminate\Routing\Router $router) {
        //     // https://laravel.com/docs/12.x/routing#routing-customization
        //     // Route::middleware('api')
        //     //     ->prefix('api')
        //     //     ->group(base_path('routes/api.php'));

        //     $router->middleware('web')
        //         ->group(base_path('routes/admin.php')) // Example for admin routes
        //         ->group(base_path('routes/test.php')) // Example for test routes
        //         ->group(base_path('routes/auth.php')) // Example for auth routes
        //         ->group(base_path('routes/add.php')); // Example for add routes
        // },
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
        //Theo mặc định, trình xử lý ngoại lệ Laravel sẽ chuyển đổi ngoại lệ thành phản hồi HTTP cho bạn
        /**
         * Bỏ qua các ngoại lệ theo loại(not work now)
         * https://laravel.com/docs/12.x/errors#ignoring-exceptions-by-type
         */
        $exceptions->dontReport([
            PassException::class,
        ]);

        /**
         * Tùy chỉnh phản hồi ngoại lệ dựa trên loại ngoại lệ và các điều kiện khác
         * return json object khi request api/*
         */
        $exceptions->render(function (ApiException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getCode() ?: 400);
            }
        });
    })->create();
