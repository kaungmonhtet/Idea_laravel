<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'code' => "C-001",
                'description' => "Category One",
            ],
            [
                'code' => "C-002",
                'description' => "Category Two",
            ],
            [
                'code' => "C-003",
                'description' => "Category Three",
            ]
    
        ]);
    }
}
