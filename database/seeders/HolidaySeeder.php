<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidays = [

            // Regular Holidays
            [
                'name_holiday' => 'New Year\'s Day',
                'date_holiday' => '2025-01-01',
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'Araw ng Kagitingan',
                'date_holiday' => '2025-04-09',
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'Labor Day',
                'date_holiday' => '2025-05-01',
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'Independence Day',
                'date_holiday' => '2025-06-12',
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'National Heroes Day',
                'date_holiday' => '2025-08-25', // Last Monday of August (2025)
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'Bonifacio Day',
                'date_holiday' => '2025-11-30',
                'type' => 'regular',
            ],
            [
                'name_holiday' => 'Christmas Day',
                'date_holiday' => '2025-12-25',
                'type' => 'regular',
            ],

            // Special Non-Working Holidays
            [
                'name_holiday' => 'Ninoy Aquino Day',
                'date_holiday' => '2025-08-21',
                'type' => 'special',
            ],
            [
                'name_holiday' => 'All Saints’ Day',
                'date_holiday' => '2025-11-01',
                'type' => 'special',
            ],
            [
                'name_holiday' => 'Feast of the Immaculate Conception',
                'date_holiday' => '2025-12-08',
                'type' => 'special',
            ],

            // Add as needed
        ];

        foreach ($holidays as $holiday) {
            DB::table('holidays')->insert(array_merge($holiday, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
