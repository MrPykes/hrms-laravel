
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')

    @php
        use Carbon\Carbon;
        $today_date = Carbon::today()->format('d-m-Y');
        $year = Carbon::today()->format('Y');
    @endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="timesheet-container">

            <div class="header-bar mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Biweekly timesheet (sample)</h3>
                    </div>
                    <div class="col-md-4 text-end">
                        <strong>Week beginning:</strong> 07-01-2024
                    </div>
                </div>
            </div>


            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Employee name:</strong> {{ $employee->name }}</p>
                    <p><strong>Role:</strong> {{$employee->position->name}}</p>
                    <p><strong>Team:</strong> {{$employee->department->name}}</p>
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
                                <td>{{ $item['attendance']->punch_in }}</td>
                                <td>{{ $item['attendance']->punch_out }}</td>
                                <td>{{ $item['attendance']->break_hours }}</td>
                                <td class="highlight-green {{ $item['is_holiday'] ? 'text-primary' : '' }}">{{ $item['is_holiday'] ? 'Holiday' : ($item['attendance']->production_hours ?? 0) }}</td>
                                <td>{{ $item['attendance']->payable_overtime_hours }}</td>
                                <td>{{ $item['is_holiday'] && $item['attendance']  ? $item['attendance']->production_hours : 0}}</td>
                                <td></td>
                                <td class="fw-bold">{{ $item['attendance']->production_hours }}</td>

                            {{-- If holiday --}}
                            @elseif ($item['is_holiday'] && $item['attendance'] === null && !Carbon::parse($date)->isWeekend())
                                <td colspan="8" class="text-center text-primary">Holiday</td>

                            {{-- If leave --}}
                            @elseif ($item['is_leave'])
                                <td colspan="8" class="text-center text-warning">{{ $item['is_leave']->leave_type->name }}</td>

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
                    <td>80.00</td>
                    <td>5.00</td>
                    <td>12.00</td>
                    <td>16.00</td>
                    <td>57.00</td>
                </tr>
                <tr>
                    <td>Rate</td>
                    <td>$0.00</td>
                    <td>$50.00</td>
                    <td>$50.00</td>
                    <td>-</td>
                    <td></td>
                </tr>
                <tr class="fw-bold">
                    <td>Total Pay:</td>
                    <td colspan="4"></td>
                    <td>$4,250.00</td>
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
