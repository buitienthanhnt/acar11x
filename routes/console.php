<?php

use App\Models\Category;
use App\Models\Page;
use App\Models\Scopes\ActiveScope;
use App\Models\Types\PageInterface;
use App\Models\Types\WriterInterface;
use App\Models\Writer;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * run cmd: php artisan sync-storage
 */
Artisan::command('sync-storage', function () {
    $result = Process::run('sudo cp -rf /var/www/html/10xreact/storage/app/public/ /var/www/html/acar11x/storage/app/')->throw();
    $this->comment($result->output());
    // dd($result->output());
});

/**
 * khác biệt giữa query-builder và eloquent-builder là:
 * query-builder sẽ nhanh hơn với lượng dữ liệu lớn
 * eloquent-builder sẽ tiện dụng hơn trong việc sử dụng cú pháp và định dạng dữ liệu.
 * laravel eloquent-model and query-db info: https://viblo.asia/p/so-sanh-giua-eloquent-orm-va-querybuilder-trong-laravel-maGK7MG9lj2 
 */
Artisan::command('model-type', function () {
    /**
     * 1 DB sẽ trả về  Query\Builder
     * @var \Illuminate\Database\Query\Builder $dbModel
     * Phương thức này selectsẽ luôn trả về một arraysố kết quả. 
     * Mỗi kết quả trong mảng sẽ là một stdClassđối tượng PHP đại diện cho một bản ghi từ cơ sở dữ liệu:
     */
    $dbModel = DB::table('pages');
    $this->comment($dbModel instanceof Illuminate\Database\Query\Builder ? 'Illuminate\Database\Query\Builder' : 0);

    /**
     * 1 eloquent-model sẽ trả về 1: Eloquent\Builder
     * @var \Illuminate\Database\Eloquent\Buildder $model
     * ->get() -> Illuminate\Database\Eloquent\Collection use trait: Illuminate\Support\Traits\EnumeratesValues has toArray, toJson, toString function
     */
    $model = Page::query();
    $category = Category::query();
    $this->comment(get_class($model));
    $this->comment($model->get()->count());
    $this->comment($category->get()->__toString());
    $this->comment($model instanceof Illuminate\Database\Eloquent\Builder ? 'Illuminate\Database\Eloquent\Builder' : 'no');
    return;
});

Artisan::command('add-select', function () {
    /**
     * add new attribute for eloquent query.
     */
    // $writer = Writer::addSelect([
    //     'first_page' => Page::select(PageInterface::ALIAS)->whereColumn('writer', WriterInterface::TABLE_NAME . '.id')->latest(),
    // ])->first();
    // dd($writer->toArray());
    // dd($writer->toSql());

    /**
     * eloquent model join table(lỗi nếu không dùng: withoutGlobalScope do nó không phân biệt được biến globalscope active.)
     */
    $writerJoin = Writer::withoutGlobalScope(ActiveScope::class)
        ->join('pages', 'writers.id', '=', 'pages.writer')
        ->select('writers.*', 'pages.alias as p_alias')
        ->first();
    dd($writerJoin->toArray());

    //    $response = [
    //     "id" => 29
    //     "name" => "bảy đờn"
    //     "email" => "baydon@gmail.com"
    //     "alias" => "binden"
    //     "phone" => "07020322012"
    //     "address" => "dinh cong"
    //     "image_path" => "http://acar11x.dev/storage/images/writers/29/bay-don-edir.jpg"
    //     "description" => "da edit"
    //     "date_of_birth" => "2025-07-09"
    //     "first_page" => "Hé lộ những điều bất thường khiến FIFA nghi ngờ cầu thủ nhập tịch Malaysia"
    //    ];
});
