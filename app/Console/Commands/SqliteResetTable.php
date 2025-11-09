<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SqliteResetTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sqlite-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for run slite command query';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->resetTables(['pages', 'categories', 'comments', 'page_contents', 'tags', 'view_sources', 'writers', 'page_categories']);
    }

    /**
     * reset sqlite table and reset primary id.
     * @param string $table
     * @return void
     */
    protected function resetTables(array $values = []): void
    {
        $this->info('start for run sqlite |--->');
        $tables = Schema::connection('mysql')->getTables();
        foreach ($tables as $table) {
            if (!!$values && in_array($table['name'], $values)) {
                DB::table($table['name'])->delete();
            }else {
                # code...
            }
        }

        $this->info('end of sqlite <--------------|');
    }
}
