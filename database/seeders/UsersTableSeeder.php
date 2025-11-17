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
                'name' => 'Administrator',
                'rec_id' => 'creo_0001',
                'email' => 'admin@creodigitals.com',
                'password' => Hash::make('password'),
                'role' => 'Administrator',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'hrmanager',
                'name' => 'Administrator',
                'rec_id' => 'creo_0002',
                'email' => 'hr@creodigitals.com',
                'password' => Hash::make('password'),
                'role' => 'HR',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
