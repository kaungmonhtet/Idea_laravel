<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
            'code' => "D-001",
            'description' => "Department One",
            ],
            [
                'code' => "D-002",
                'description' => "Department Two",
            ],
            [
                'code' => "D-003",
                'description' => "Department Three",
            ]
    
        ]);
    }
}
