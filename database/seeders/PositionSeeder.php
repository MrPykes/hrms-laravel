<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $faker = Faker::create();
        // Get department IDs dynamically
        $itDept = DB::table('departments')->where('name', 'IT Department')->first();
        $hrDept = DB::table('departments')->where('name', 'HR Department')->first();
        $financeDept = DB::table('departments')->where('name', 'Finance Department')->first();

        DB::table('positions')->insert([
            [
                'department_id' => $itDept->id, 
                'name' => 'Web Developer', 
                'description' => $faker->sentence(10),
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'department_id' => $itDept->id, 
                'name' => 'Web Designer', 
                'description' => $faker->sentence(10),
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'department_id' => $itDept->id, 
                'name' => 'System Administrator', 
                'description' => $faker->sentence(10),
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'department_id' => $hrDept->id, 
                'name' => 'HR Officer', 
                'description' => $faker->sentence(10),
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'department_id' => $financeDept->id, 
                'name' => 'Marketing Manager', 
                'description' => $faker->sentence(10),
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}
