<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('leave_requests')->insert([
            [
                'employee_id'   => 1,
                'leave_type_id'    => 1,
                'from_date'     => '2025-01-10',
                'to_date'       => '2025-01-12',
                'day'           => '3',
                'reason'  => 'Family outing',
                'status'  => 'approved',
                'approve_by'    => 2,
                'is_paid'  => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employee_id'   => 1,
                'leave_type_id'    => 2,
                'from_date'     => '2025-02-01',
                'to_date'       => '2025-02-02',
                'day'           => '2',
                'reason'  => 'Flu',
                'status'  => 'approved',
                'approve_by'    => 3,
                'is_paid'  => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employee_id'   => 2,
                'leave_type_id'    => 3,
                'from_date'     => '2025-03-15',
                'to_date'       => '2025-03-15',
                'day'           => '1',
                'reason'  => 'Emergency at home',
                'status'  => 'pending',
                'approve_by'    => 1,
                'is_paid'  => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employee_id'   => 3,
                'leave_type_id'    => 1,
                'from_date'     => '2025-04-05',
                'to_date'       => '2025-04-07',
                'day'           => '3',
                'reason'  => 'Travel',
                'status'  => 'denied',
                'approve_by'    => 1,
                'is_paid'  => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
            [
                'employee_id'   => 4,
                'leave_type_id'    => 2,
                'from_date'     => '2025-05-20',
                'to_date'       => '2025-05-21',
                'day'           => '2',
                'reason'  => 'High fever',
                'status'  => 'approved',
                'approve_by'    => 2,
                'is_paid'  => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ],
        ]);
    }
}
