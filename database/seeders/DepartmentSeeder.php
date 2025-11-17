<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          $faker = Faker::create();

        DB::table('departments')->insert([
            [
                'name' => 'IT Department',
                'description' => $faker->sentence(5), // random short description
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Department',
                'description' => $faker->sentence(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Finance Department',
                'description' => $faker->sentence(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
