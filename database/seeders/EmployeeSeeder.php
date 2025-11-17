<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;
class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = [
            ['name' => 'Paul John Marinas', 'email' => 'pauljohn@example.com','position_id' => 3],
            ['name' => 'Ed Francis Calimlim', 'email' => 'edfrancis@example.com','position_id' => 3],
            ['name' => 'Jacob Ashlley Ocampo', 'email' => 'jacob@example.com','position_id' => 1],
            ['name' => 'Ivan Chris Ablian', 'email' => 'ivan@example.com','position_id' => 2],
            ['name' => 'Ronald Joseph Apostol', 'email' => 'ronald@example.com','position_id' => 1],
            ['name' => 'Jeiel Ponilas', 'email' => 'jeiel@example.com','position_id' => 1],
            ['name' => 'Mikhaela Phoenix Marticion', 'email' => 'mikhaela@example.com','position_id' => 1],
            ['name' => 'Mark Russel Trapsi', 'email' => 'markrussel@example.com','position_id' => 1],
            ['name' => 'Lovelle Jao', 'email' => 'lovelle@example.com','position_id' => 5],
            ['name' => 'Lyzell Martin', 'email' => 'lyzell@example.com','position_id' => 4],
            ['name' => 'Justin Karl Baluyot', 'email' => 'justin@example.com','position_id' => 2],
            ['name' => 'Maui Gonzales', 'email' => 'maui@example.com','position_id' => 1],
            ['name' => 'Jamie Lynne Fullecido', 'email' => 'jamie@example.com','position_id' => 1],
        ];

        foreach ($employees as $index => $emp) {
            Employee::create([
                'employee_id'    => 'EMP-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name'           => $emp['name'],
                'email'          => $emp['email'],
                'department_id'  => 1, // change if needed
                'position_id'    => $emp['position_id'], // change if needed
                'gender'         => 'Not specified',
                'birth_date'     => Carbon::parse('1995-01-01')->addYears(rand(0,10))->format('Y-m-d'),
                'join_date'      => Carbon::now()->subYears(rand(0,5))->format('Y-m-d'),
                'salary'         => rand(20000, 40000),
            ]);
        }
    }
}
