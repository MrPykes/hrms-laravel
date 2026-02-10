
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')

    @php
        use Carbon\Carbon;
        $today_date = Carbon::today()->format('d-m-Y');
        $currentYear = Carbon::today()->format('Y');
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
    @endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="timesheet-container">

            <div class="header-bar mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Biweekly Timesheet</h3>
                    </div>
                    <div class="col-md-4 text-end">
                        <strong>Period:</strong> {{ $months[(int)$month] }} {{ $period == '1-15' ? '1-15' : '16-' . Carbon::create($year, $month)->daysInMonth }}, {{ $year }}
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4 filter-row">
                <form method="GET" action="{{ route('timesheets') }}" class="row w-100">
                    @if($isAdmin)
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="employee_id">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $employee->id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Employee</label>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="month">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ (int)$month == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Month</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus">
                            <input type="number" class="form-control floating" name="year" value="{{ $year }}" min="2020" max="2030">
                            <label class="focus-label">Year</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="period">
                                <option value="1-15" {{ $period == '1-15' ? 'selected' : '' }}>1st - 15th</option>
                                <option value="16-31" {{ $period == '16-31' ? 'selected' : '' }}>16th - End</option>
                            </select>
                            <label class="focus-label">Period</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <button type="submit" class="btn btn-success btn-block">View</button>
                    </div>
                </form>
            </div>
            <!-- /Filter Section -->

            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Employee name:</strong> {{ $employee->name }}</p>
                    <p><strong>Role:</strong> {{ $employee->position->name ?? 'N/A' }}</p>
                    <p><strong>Team:</strong> {{ $employee->department->name ?? 'N/A' }}</p>
                    <p><strong>Notes:</strong></p>
                    <p><strong>Signature:</strong> Jane Doe</p>
                    <p><strong>Date:</strong> 07/13/24</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Manager name:</strong> Percy Stilwell</p>
                    <p><strong>Role:</strong> CMO</p>
                    <p><strong>Team:</strong> Marketing</p>
                    <p><strong>Notes:</strong> Keep an eye on Jane’s overtime.</p>
                    <p><strong>Signature:</strong> Percy Stilwell</p>
                    <p><strong>Date:</strong> 06/11/24</p>
                </div>
            </div>

            @php
                $totalProductionHours = 0;
                $totalOvertimeHours = 0;
                $totalHolidayHours = 0;
                $totalBreakHours = 0;
                $totalLeaveHours = 0;
                $totalHours = 0;
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Start</th>
                        <th>Finish</th>
                        <th>Breaks (Hours)</th>
                        <th>Production Hours</th>
                        <th>Overtime (Hours)</th>
                        <th>Holiday(Hours)</th>
                        <th>Leave (Hours)</th>
                        <th>Total hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $date => $item)
                        <tr>
                            {{-- Date column --}}
                            <td>{{ $date }}</td>

                            {{-- Day (D) --}}
                            <td>{{ Carbon::parse($date)->format('D') }}</td>

                            {{-- Other columns --}}
                            {{-- If $item->attendance is an Attendance model --}}
                            @if ($item['attendance'] instanceof \App\Models\Attendance)
                                @php
                                    $punchIn = $item['attendance']->punch_in;
                                    $punchOut = $item['attendance']->punch_out;
                                    $breakHours = (float) ($item['attendance']->break_hours ?? 0);
                                    
                                    // Compute production hours from punch_in and punch_out
                                    $prodHours = 0;
                                    $overtimeHours = 0;
                                    $standardWorkHours = 8; // Standard 8-hour work day
                                    
                                    if ($punchIn && $punchOut) {
                                        $inTime = Carbon::parse($date . ' ' . $punchIn);
                                        $outTime = Carbon::parse($date . ' ' . $punchOut);
                                        
                                        // If out time is before in time, assume next day
                                        if ($outTime->lt($inTime)) {
                                            $outTime->addDay();
                                        }
                                        
                                        // Total worked hours minus breaks
                                        $totalWorked = $outTime->diffInMinutes($inTime) / 60;
                                        $prodHours = max(0, $totalWorked - $breakHours);
                                        
                                        // Calculate overtime (hours beyond standard work hours)
                                        if ($prodHours > $standardWorkHours) {
                                            $overtimeHours = $prodHours - $standardWorkHours;
                                            $prodHours = $standardWorkHours;
                                        }
                                    }
                                    
                                    $holidayHours = ($item['is_holiday'] && $item['attendance']) ? ($prodHours + $overtimeHours) : 0;
                                    
                                    if (!$item['is_holiday']) {
                                        $totalProductionHours = $totalProductionHours + $prodHours;
                                    }
                                    $totalOvertimeHours = $totalOvertimeHours + $overtimeHours;
                                    $totalBreakHours = $totalBreakHours + $breakHours;
                                    $totalHolidayHours = $totalHolidayHours + $holidayHours;
                                    $totalHours = $totalHours + $prodHours + $overtimeHours;
                                @endphp
                                <td>{{ $punchIn }}</td>
                                <td>{{ $punchOut }}</td>
                                <td>{{ number_format($breakHours, 2) }}</td>
                                <td class="highlight-green {{ $item['is_holiday'] ? 'text-primary' : '' }}">{{ $item['is_holiday'] ? 'Holiday' : number_format($prodHours, 2) }}</td>
                                <td>{{ number_format($overtimeHours, 2) }}</td>
                                <td>{{ number_format($holidayHours, 2) }}</td>
                                <td>0.00</td>
                                <td class="fw-bold">{{ number_format($prodHours + $overtimeHours, 2) }}</td>

                            {{-- If holiday --}}
                            @elseif ($item['is_holiday'] && $item['attendance'] === null && !Carbon::parse($date)->isWeekend())
                                <td colspan="8" class="text-center text-primary">Holiday</td>

                            {{-- If leave --}}
                            @elseif ($item['is_leave'])
                                @php
                                    $leaveHours = 8; // Assuming 8 hours per leave day
                                    $totalLeaveHours = $totalLeaveHours + $leaveHours;
                                @endphp
                                <td colspan="7" class="text-center text-warning">{{ $item['is_leave']->leave_type->name }}</td>
                                <td>{{ number_format($leaveHours, 2) }}</td>

                            {{-- If no attendance --}}
                            @else
                                <td colspan="8" class="text-center text-muted">Absent</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table table-bordered">
                <tr class="footer-total">
                    <td>Total Hours</td>
                    <td>{{ number_format($totalProductionHours, 2) }}</td>
                    <td>{{ number_format($totalOvertimeHours, 2) }}</td>
                    <td>{{ number_format($totalHolidayHours, 2) }}</td>
                    <td>{{ number_format($totalLeaveHours, 2) }}</td>
                    <td>{{ number_format($totalHours + $totalOvertimeHours, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-muted small">
                        <strong>Summary:</strong> Production: {{ number_format($totalProductionHours, 2) }}h | 
                        Overtime: {{ number_format($totalOvertimeHours, 2) }}h | 
                        Holiday: {{ number_format($totalHolidayHours, 2) }}h | 
                        Leave: {{ number_format($totalLeaveHours, 2) }}h | 
                        Total Breaks: {{ number_format($totalBreakHours, 2) }}h
                    </td>
                </tr>
            </table>
                <!-- /Page Content -->

        </div>
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        // document.getElementById("year").innerHTML = new Date().getFullYear();
    </script>
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#holidayName_edit').val(_this.find('.holidayName').text());
            $('#holidayDate_edit').val(_this.find('.holidayDate').text());  
        });
    </script>
    @endsection

@endsection
