<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
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
                'username' => 'admin',
                'email' => "admin@creodigitals.com",
                'password' => Hash::make('password'),
                'role_id' => 1,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
         DB::table('users')->insert([
            [
                'employee_id' => 2,
                'username' => 'edfrancisCalimlim',
                'email' => "ed@creodigitals.com",
                'password' => Hash::make('password'),
                'role_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
