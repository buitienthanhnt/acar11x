<?php

namespace Database\Seeders;

use App\Models\Writer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class WriterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->createWriter();
    }
    
    /**
     * create new writer 
     */
    protected function createWriter() : void {
        try {
            Writer::factory()->create();
            dd('----------------');
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
       
        return;
    }    
}
