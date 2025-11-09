<?php

use App\Enums\PageEnum;
use App\Enums\ShareEnum;
use App\Events\ViewCount;
use App\Http\Controllers\HomeController;
use App\Models\Api\PageApi;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (isTestEnv()) {
	Route::prefix('test')->group(function (): void {
		Route::get('knock', function () {
			return view('adminhtml.test.knock');
		});

		Route::get('translate', function () {
			// App::setLocale('vi');
			dd(__('auth.user.name'));

			// echo(action([HomeController::class, 'list'], ['id' => 1]));
			// return redirect($signutre);
			// return 123;
		});

		/**
		 * test redis cache.
		 */
		Route::get('redis', function () {
			Redis::set('test', 'true');
			$data = Redis::get('test');
			dd($data);
		});

		Route::get('event', function () {
			/**
			 * dispatch page view count action
			 */
			ViewCount::dispatch(Page::find(40));
			/**
			 * call add page info type.
			 */
			PageApi::pageInfoActionRedis(40, 'heart', 'dic');
			return true;
		});

		Route::get('enum', function () {
			dd(ShareEnum::processOrder(ShareEnum::Approved));
			dd(PageEnum::Fire->value);
		});

		Route::get('knock', function () {
			return view('adminhtml.test.knock');
		});

		Route::get('testUrl', function (Request $request) {
			// $link = \Linkeys\UrlSigner\Facade\UrlSigner::generate('https://www.example.com/invitation');
			// echo $link->getFullUrl(); // https://www.example.com/invitation?uuid=UUID

			$link = \Linkeys\UrlSigner\Facade\UrlSigner::generate(action([HomeController::class, 'list']), ['id' => 1], '+1 hours', 1);
			return $link->getFullUrl();

			// test url voi chu ky(neu co nguoi sua id sang=3 thi se bao loi)
			$signutre = URL::signedRoute('detail', ['user' => 2]);
			echo $signutre;
			return;

			// tao url co chu ky voi thoi gian song nhat dinh(2 phut).
			$urlOnceTime = URL::temporarySignedRoute('detail', now()->addMinutes(2), ['id' => 12]);
			echo ($urlOnceTime);
		});

		Route::get('json', function () {
			// abort(500, 'error by demo');
			return response()->json([
				'a' => 123,
				'b' => 'pppp',
			], 500);

			return [
				'name' => 'demo for test json',
				'value' => 123,
			];
		});

		Route::get('get-view-source/{page_id}', [\App\Http\Controllers\TestController::class, 'getViewSource']);

		Route::inertia("/", 'Screen/TestScreen/Test');
		/**
		 * test merge props inertiaJs
		 */
		Route::get('merge-prop', [\App\Http\Controllers\TestController::class, 'mergeProp']);

		Route::post('post-merge', [\App\Http\Controllers\TestController::class, 'postMerge']);

		Route::get('partial-reloads', [\App\Http\Controllers\TestController::class, 'partialReloads']);

		Route::get('remenber-search', [\App\Http\Controllers\TestController::class, 'remenberState']);

		Route::post('remenber-post', [\App\Http\Controllers\TestController::class, 'remenberPost']);

		Route::get('timeline', [\App\Http\Controllers\TestController::class, 'timeline']);

		Route::get('paginate', [\App\Http\Controllers\TestController::class, 'paginate']);
	});
}
