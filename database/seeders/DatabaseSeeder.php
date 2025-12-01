<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            PositionSeeder::class,
            EmployeeSeeder::class,
            UsersTableSeeder::class,
            AttendanceSeeder::class,
            LeaveTypeSeeder::class,
            LeaveRequestSeeder::class,
            HolidaySeeder::class,
        ]);
    }
}
