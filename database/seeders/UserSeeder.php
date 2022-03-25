<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'department_id' => 1,
                'role' => 4,
                'first_name' => "System",
                'last_name' => 'Admin',
                'email' => 'sysadmin@gmail.com',
                'date_of_birth' => '2000-03-03',
                'gender' => 1,
                'mobile_no' =>"09111111",
                'staff_type' => null,
                'password' => bcrypt('password'),
            ],
    
        ]);
    }
}
