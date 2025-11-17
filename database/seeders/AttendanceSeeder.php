<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employees
        $employees = Employee::all();

        // Define date range for October 2025
        $startDate = Carbon::create(2025, 10, 1);
        $endDate = Carbon::create(2025, 10, 31);

        // Loop through each day in October
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip weekends (optional)
            if ($date->isWeekend()) {
                continue;
            }

            foreach ($employees as $employee) {
                $attendance = Attendance::create([
                    'employee_id' => $employee->id,
                    'attendance_date' => $date->format('Y-m-d'),
                    'punch_in' => '09:00:00',
                    'punch_out' => '18:00:00',
                    'late_in' => 0.0,
                    'break_hours' => 1.0,
                    'overtime_hours' => 0.0,
                    'status' => 'Present',
                ]);

                // Create sample punch logs
                $logs = [
                    ['punch_in' => '09:00:00', 'punch_out' => '12:00:00'],
                    ['punch_in' => '13:00:00', 'punch_out' => '18:00:00'],
                ];

                foreach ($logs as $log) {
                    AttendanceLog::create([
                        'attendance_id' => $attendance->id,
                        'punch_in' => $log['punch_in'],
                        'punch_out' => $log['punch_out'],
                    ]);
                }
            }
        }
    }
}
