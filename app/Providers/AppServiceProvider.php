<?php

namespace App\Providers;

use App\Events\CategorySaved;
use App\Events\PageSaved;
use App\Events\ViewCount;
use App\Events\WriterSaved;
use App\Listeners\CategorySavedListen;
use App\Listeners\PageContentListen;
use App\Listeners\PageSavedListen;
use App\Listeners\PageTagListen;
use App\Listeners\ViewCountListen;
use App\Listeners\WriterSavedListen;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->defineEventListener();

        Vite::prefetch(concurrency: 3);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Định nghĩa sự kiện và trình nghe tương ứng của chúng.
     * @return void
     */
    protected function defineEventListener(): void
    {
        /**
         * define for page view number count.
         */
        Event::listen(
            ViewCount::class,
            ViewCountListen::class,
        );

        /**
         * define for after category saved event.
         */
        Event::listen(
            CategorySaved::class,
            CategorySavedListen::class,
        );

        /**
         * define for after writer saved event.
         */
        Event::listen(
            WriterSaved::class,
            WriterSavedListen::class,
        );

        /**
         * define for after page saved event.
         */
        array_map(function ($item) {
            Event::listen(
                PageSaved::class,
                $item,
            );
        }, [
            PageSavedListen::class,
            PageTagListen::class,
            PageContentListen::class,
        ]);
    }
}
