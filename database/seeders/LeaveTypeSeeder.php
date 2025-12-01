<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('leave_types')->insert([
            [
                'name' => 'Sick Leave',
                'description' => 'Leave when employee is sick',
                'number_of_leave' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vacation Leave',
                'description' => 'Leave for personal vacation',
                'number_of_leave' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency Leave',
                'description' => 'Emergency situations',
                'number_of_leave' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
