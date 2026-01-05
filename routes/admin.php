<?php

use App\Http\Controllers\AdminHtml\CategoryController;
use App\Http\Controllers\AdminHtml\DashboardController;
use App\Http\Controllers\AdminHtml\DesignController;
use App\Http\Controllers\AdminHtml\PageController;
use App\Http\Controllers\AdminHtml\WriterController;
use App\Models\Types\CategoryInterface;
use App\Models\Types\PageInterface;
use App\Models\Types\WriterInterface;
use Database\Configs\AdminPermission;
use Illuminate\Support\Facades\Route;
use Thanhnt\Abookglobal\Controllers\Admin\BookAdminController;
use Thanhnt\Ahomeglobal\Controllers\Adminhtml\HomeAdminController;
use Thanhnt\Ahomeglobal\Controllers\Adminhtml\OrderAdminController;
use Thanhnt\Ahomeglobal\Controllers\Adminhtml\RoomAdminController;

if (isAdminEnv()) {
    Route::prefix(ADMIN_PREFIX)->middleware(['adminVerify', 'adminPermission'])->group(function () {

        Route::get('/', [DashboardController::class, 'home'])->withoutMiddleware(['adminPermission'])->name('admin.dashboard')->setBindingFields([
            'route_name' => 'dashboard',
            'route_icon' => 'dashboard'
        ]);

        Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web']], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();

            Route::get('/?type=Images', 'UniSharp\LaravelFilemanager\Controllers\LfmController@show')->setBindingFields([
                'route_name' => 'file manager',
                'route_icon' => 'image',
                'show' => true,
            ]);
        });

        Route::get('/login', [DashboardController::class, 'login'])->withoutMiddleware(['adminVerify', 'adminPermission']);

        Route::get('/sign-up', [DashboardController::class, 'signUp'])->withoutMiddleware(['adminVerify', 'adminPermission']);

        Route::post('/register-user', [DashboardController::class, 'register'])->withoutMiddleware(['adminVerify', 'adminPermission']);

        Route::get('/logout', [DashboardController::class, 'logout'])->withoutMiddleware(['adminPermission'])->name('admin-logout');

        Route::post('admin-login', [DashboardController::class, 'loginPost'])->withoutMiddleware(['adminVerify', 'adminPermission'])->name('admin-login');

        Route::prefix(PageInterface::PREFIX)->group(function () {
            Route::get('/', [PageController::class, 'list'])->setBindingFields([
                'route_name' => 'page manage',
                'route_icon' => 'assignment',
                'show' => true,
            ]);

            Route::get('create', [PageController::class, 'create'])->setBindingFields([
                'permission' => [AdminPermission::ACTION_CREATE]
            ]);

            Route::post('register', [PageController::class, 'store']);

            Route::get(PageInterface::ROUTE_ACTION['detail'], [PageController::class, 'detail']);

            Route::delete(PageInterface::ROUTE_ACTION['delete'], [PageController::class, 'deleteAction']);

            Route::get(PageInterface::ROUTE_ACTION['edit'], [PageController::class, 'edit']);

            Route::post(PageInterface::ROUTE_ACTION['update'], [PageController::class, 'updateAction']);
        });

        Route::prefix(WriterInterface::PREFIX)->group(function (): void {
            Route::get(WriterInterface::ROUTE_ACTION['list'], [WriterController::class, 'index'])->setBindingFields([
                'route_name' => 'writer manage',
                'route_icon' => 'groups', // https://fonts.google.com/icons
                'show' => true,
                'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get('create', [WriterController::class, 'create']);

            Route::post('register', [WriterController::class, 'store'])->name('admin_writer_create');

            Route::get(WriterInterface::ROUTE_ACTION['detail'], [WriterController::class, 'show']);

            Route::delete(WriterInterface::ROUTE_ACTION['delete'], [WriterController::class, 'deleteAction']);

            Route::get(WriterInterface::ROUTE_ACTION['edit'], [WriterController::class, 'edit']);

            Route::post(WriterInterface::ROUTE_ACTION['update'], [WriterController::class, 'update']);
        });

        Route::prefix(CategoryInterface::PREFIX)->group(function (): void {
            Route::get(CategoryInterface::ROUTE_ACTION['list'], [CategoryController::class, 'index'])->setBindingFields([
                'route_name' => 'category manage',
                'route_icon' => 'token', // https://fonts.google.com/icons
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get(CategoryInterface::ROUTE_ACTION['create'], [CategoryController::class, 'create']);

            Route::post(CategoryInterface::ROUTE_ACTION['register'], [CategoryController::class, 'registerAction']);

            Route::get(CategoryInterface::ROUTE_ACTION['edit'], [CategoryController::class, 'edit']);

            Route::post(CategoryInterface::ROUTE_ACTION['update'], [CategoryController::class, 'UpdateAction']);

            Route::delete(CategoryInterface::ROUTE_ACTION['delete'], [CategoryController::class, 'deleteAction']);
        });

        Route::prefix('design')->group(function (): void {
            Route::get('/', [DesignController::class, 'home'])->setBindingFields([
                'route_name' => 'design manage',
                'route_icon' => 'dataset_linked', // https://fonts.google.com/icons => [Icon name]
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get('/page-setup', [DesignController::class, 'pageSetup']);

            Route::post('/page-setup', [DesignController::class, 'pageStore']);

            Route::get('/home-setup', [DesignController::class, 'pageSetup']);

            Route::post('/home-setup', [DesignController::class, 'pageStore']);
        });

        /**
         * define admin route for ahomeglobal package
         */
        Route::prefix('ahome')->group(function (): void {
            Route::get('order-delete', [OrderAdminController::class, 'deleteOrders']);

            Route::get('ordertime-delete', [OrderAdminController::class, 'deleteOrderTime']);

            Route::get('home-list', [HomeAdminController::class, 'homes'])->setBindingFields([
                'route_name' => 'ahome manager',
                'route_icon' => 'dataset_linked', // https://fonts.google.com/icons => [Icon name]
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get('home-create', [HomeAdminController::class, 'create']);

            Route::post('home-register', [HomeAdminController::class, 'register']);

            Route::get('home-edit/{id}', [HomeAdminController::class, 'edit']);

            Route::post('home-update/{id}', [HomeAdminController::class, 'update']);

            Route::delete('home-delete/{id}', [HomeAdminController::class, 'delete']);

            Route::get('room-list', [RoomAdminController::class, 'list'])->setBindingFields([
                'route_name' => 'rooms manager',
                'route_icon' => 'dataset_linked', // https://fonts.google.com/icons => [Icon name]
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);;

            Route::get('room-create/{home_id}', [RoomAdminController::class, 'create']);

            Route::post('room-register/{home_id}', [RoomAdminController::class, 'register']);

            Route::delete('room-delete/{id}', [HomeAdminController::class, 'roomDelete']);
        });

        Route::prefix('abook')->group(function (): void {
            Route::get('book-list', [BookAdminController::class, 'index'])->setBindingFields([
                'route_name' => 'abook manager',
                'route_icon' => 'dataset_linked', // https://fonts.google.com/icons => [Icon name]
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get('create', [BookAdminController::class, 'createBook']);

            Route::post('register', [BookAdminController::class, 'registerBook']);

            Route::get('book-cate', [BookAdminController::class, 'listBookCates'])->setBindingFields([
                'route_name' => 'abook cate manager',
                'route_icon' => 'dataset_linked', // https://fonts.google.com/icons => [Icon name]
                'show' => true,
                // 'permission' => AdminPermission::ACTION_LIST
            ]);

            Route::get('bookcate-create', [BookAdminController::class, 'createBookCate']);

            Route::post('bookcate-register', [BookAdminController::class, 'registerBookCate']);
        });
    });
}
