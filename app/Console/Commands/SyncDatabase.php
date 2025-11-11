<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Scopes\ActiveScope;
use App\Models\Types\PageInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDatabase extends Command
{
    /**
     * The name and signature of the console command.
     * ex: php artisan app:sync-database pages,writers
     * sync storage image run: 
     * sudo cp -rf /var/www/html/10xreact/storage/app/public/ /var/www/html/acar11x/storage/app/
     *
     * @var string
     */
    protected $signature = 'app:sync-database {table?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for coppy value from mysql database(adoc) to mysqlite database(database/database.sqlite)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * sync for: 'page' model
         */
        // $this->syncModelDatabase('page');

        /**
         * đồng bộ cơ sở dữ liệu bằng: query builder(Illuminate\Support\Facades\DB) 
         */
        $this->syncAllTables();
    }

    /**
     * chuyển đổi cơ sở dữ liệu từ [mysql] sang [sqlite]
     * @return void
     */
    private function syncModelDatabase(string $modelType): void
    {
        $this->info($this->description);
        /**
         * get pages of mysql database adoc.
         */
        // $pages = Page::on('mysql')->take(2)->get()->makeHidden([PageInterface::ID]);
        switch ($modelType) {
            case 'page':
                Page::on("mysql")
                    ->withoutGlobalScope(ActiveScope::class)
                    ->withoutGlobalScope(SoftDeletingScope::class)
                    ->chunk(10, function (Collection $items): void {
                        /**
                         * format list data with all column attribute
                         */
                        $data = $items->makeVisible(['created_at', 'deleted_at']);
                        foreach ($data->toArray() as $item) {
                            /**
                             * insert data row into database
                             */
                            $this->insetLocalDatabase('page', $item);
                            sleep(1);
                        }
                    });
                break;

            default:
                # code...
                break;
        }

        /**
         * get pages of default database sqlite 
         */
    }


    /**
     * check if exists model column value in all data
     * @param string $modelType
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    private function checkModelExist(string $modelType, string $column, mixed $value): bool
    {
        switch ($modelType) {
            case 'page':
                return Page::where($column, $value)
                    ->withoutGlobalScope(ActiveScope::class)
                    ->withoutGlobalScope(SoftDeletingScope::class)
                    ->exists();
                break;
            default:
                # code...
                break;
        }
        return false;
    }

    /**
     * insert database from old database driver to local driver
     * @param string $type
     * @param mixed|array $data
     * @return void
     * @throws Exception
     */
    private function insetLocalDatabase(string $type, mixed $data)
    {
        switch ($type) {
            case 'page':
                if (!$this->checkModelExist('page', PageInterface::ALIAS, $data[PageInterface::ALIAS])) {
                    try {
                        /**
                         * create new row database.
                         */
                        Page::factory()->create($data);
                        $this->info('insert for page has alias: [' . $data[PageInterface::ALIAS] . "]");
                    } catch (\Throwable $th) {
                        $this->error($th->getMessage());
                    }
                } else {
                    $this->warn("the page has alias: [" . $data[PageInterface::ALIAS] . "] exist");
                }
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * https://laravel.com/docs/12.x/queries#streaming-results-lazily
     */
    private function insertAutoById(string $table, ?string $unique = null)
    {
        /**
         * lấy danh sách các bản ghi hiện có,
         * chia theo nhóm gồm 10 bản ghi một nhóm: chunkById
         * lưu ý dùng: chunkById để tránh các lỗi trùng lặp
         * truy vấn từ MYSQL và chuyển sang SQLITE
         */
        DB::connection('mysql')->table($table)->orderBy('id')->chunkById(10, function (\Illuminate\Support\Collection $items) use ($table, $unique) {
            foreach ($items->toArray() as $item) {
                try {
                    /**
                     * kiểm tra tính tồn tại theo id, nếu có thì bỏ qua.
                     */
                    if ($unique && DB::table($table)->where($unique, $item->{$unique})->exists()) {
                        $this->warn("pass for $unique is exist value in table: [$table]: " . $item->{$unique} . " !");
                        continue;
                    }
                    /**
                     * thực hiện chèn bản ghi mới vào CSDL
                     */
                    DB::table($table)->insert((array) $item);
                    $this->info("insert for table: [$table] by id: " . $item->id);
                } catch (\Throwable $th) {
                    $this->error($th->getMessage());
                    //throw $th;
                }
            }
            sleep(1);
        });
    }

    private function insertWithoutId(string $table, string $orderBy)
    {
        // delete for table not use primary column(không cần xóa vì có sử dụng kiểm tra trùng lặp giá trị)
        // DB::table($table)->delete();
        /**
         * truy vấn từ MYSQL và chuyển sang SQLITE
         */
        DB::connection('mysql')->table($table)->orderBy($orderBy)->lazy()->each(function (object $item) use ($table) {
            try {
                /**
                 * kiểm tra giá trị trùng lặp, nếu có thì bỏ qua
                 */
                if (DB::table($table)->where((array) $item)->exists()) {
                    $this->warn("pass for exist value: " . json_encode((array) $item));
                } else {
                    /**
                     * thêm bản ghi mới vào CSDL
                     */
                    DB::table($table)->insert((array) $item);
                    $this->info("insert table: [$table] for value: " . json_encode((array) $item));
                }
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        });
    }

    /**
     * https://viblo.asia/p/mot-so-cach-su-dung-raw-db-query-trong-laravel-3P0lPq145ox
     * https://viblo.asia/p/lam-sao-de-chay-raw-queries-an-toan-trong-laravel-YWOZrQjYKQ0
     * coppy file image source: thanhnt@thanhnt-M4700:/var/www/html/10xreact/storage$    sudo cp -r app /var/www/html/acar11/storage/
     */
    private function syncAllTables()
    {
        $inputTable = $this->argument('table');
        /**
         * lấy danh sách các bảng trong CSDL
         */
        $tables = $inputTable ? explode(',', $inputTable) : array_column(Schema::connection('mysql')->getTables(), 'name');
        foreach ($tables as $table) {
            switch ($table) {
                case 'pages':
                case 'categories':
                case 'writers':
                    if (Schema::hasColumn($table, 'id')) {
                        $this->insertAutoById($table, 'alias');
                    }
                    break;
                case 'comments':
                case 'page_contents':
                case 'tags':
                case 'users':
                case 'view_sources':
                    if (Schema::hasColumn($table, 'id')) {
                        $this->insertAutoById($table, 'id');
                    }
                    break;
                case 'page_categories':
                    $this->insertWithoutId($table, 'page_id');
                    break;
                default:
                    $this->info("pass for table: " . $table);
            }
        }
    }
}
