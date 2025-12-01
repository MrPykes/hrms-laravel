
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')
    <!-- Sidebar -->
    <!-- <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li class="submenu">
                        <a href="#">
                            <i class="la la-dashboard"></i>
                            <span> Dashboard</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="{{ route('home') }}">Admin Dashboard</a></li>
                            <li><a href="{{ route('em/dashboard') }}">Employee Dashboard</a></li>
                        </ul>
                    </li>
                    @if (Auth::user()->role == 'Administrator' || Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                        <li class="menu-title"> <span>Authentication</span> </li>
                        <li class="submenu">
                            <a href="#">
                                <i class="la la-user-secret"></i> <span> User Controller</span> <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li><a href="{{ route('userManagement') }}">All User</a></li>
                                <li><a href="{{ route('activity/log') }}">Activity Log</a></li>
                                <li><a href="{{ route('activity/login/logout') }}">Activity User</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="menu-title">
                        <span>Employees</span>
                    </li>
                    <li class="submenu">
                        <a href="#" class="noti-dot">
                            <i class="la la-user"></i>
                            <span> Employees</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="{{ route('all/employee/card') }}">All Employees</a></li>
                            <li><a href="{{ route('form/holidays') }}">Holidays</a></li>
                            <li><a href="{{ route('form/leaves') }}">Leaves (Admin) 
                                <span class="badge badge-pill bg-primary float-right">1</span></a>
                            </li>
                            <li><a href="{{route('form/leavesemployee/new')}}">Leaves (Employee)</a></li>
                            <li><a href="{{ route('form/leavesettings/page') }}">Leave Settings</a></li>
                            <li><a href="{{ route('attendance/page') }}">Attendance (Admin)</a></li>
                            <li><a class="active" href="{{ route('attendance/employee/page') }}">Attendance (Employee)</a></li>
                            <li><a href="departments.html">Departments</a></li>
                            <li><a href="designations.html">Designations</a></li>
                            <li><a href="timesheet.html">Timesheet</a></li>
                            <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                            <li><a href="overtime.html">Overtime</a></li>
                        </ul>
                    </li>
                    <li class="menu-title"> <span>HR</span> </li>
                    <li class="submenu">
                        <a href="#">
                            <i class="la la-files-o"></i>
                            <span> Sales </span> 
                            <span class="menu-arrow"></span>
                        </a>
                        <ul style="display: none;">
                            <li><a href="estimates.html">Estimates</a></li>
                            <li><a href="{{ route('form/invoice/view/page') }}">Invoices</a></li>
                            <li><a href="payments.html">Payments</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="provident-fund.html">Provident Fund</a></li>
                            <li><a href="taxes.html">Taxes</a></li>
                        </ul>
                    </li>
                    <li class="submenu"> <a href="#"><i class="la la-money"></i>
                        <span> Payroll </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="{{ route('form/salary/page') }}"> Employee Salary </a></li>
                            <li><a href="{{ url('form/salary/view') }}"> Payslip </a></li>
                            <li><a href="{{ route('form/payroll/items') }}"> Payroll Items </a></li>
                        </ul>
                    </li>
                    <li class="submenu"> <a href="#"><i class="la la-pie-chart"></i>
                        <span> Reports </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="{{ route('form/expense/reports/page') }}"> Expense Report </a></li>
                            <li><a href="{{ route('form/invoice/reports/page') }}"> Invoice Report </a></li>
                            <li><a href="payments-reports.html"> Payments Report </a></li>
                            <li><a href="employee-reports.html"> Employee Report </a></li>
                            <li><a href="payslip-reports.html"> Payslip Report </a></li>
                            <li><a href="attendance-reports.html"> Attendance Report </a></li>
                            <li><a href="{{ route('form/leave/reports/page') }}"> Leave Report </a></li>
                            <li><a href="{{ route('form/daily/reports/page') }}"> Daily Report </a></li>
                        </ul>
                    </li>
                    <li class="menu-title"> <span>Performance</span> </li>
                    <li class="submenu"> <a href="#"><i class="la la-graduation-cap"></i>
                        <span> Performance </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="{{ route('form/performance/indicator/page') }}"> Performance Indicator </a></li>
                            <li><a href="{{ route('form/performance/page') }}"> Performance Review </a></li>
                            <li><a href="{{ route('form/performance/appraisal/page') }}"> Performance Appraisal </a></li>
                        </ul>
                    </li>
                    <li class="submenu"> <a href="#"><i class="la la-edit"></i>
                        <span> Training </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="{{ route('form/training/list/page') }}"> Training List </a></li>
                            <li><a href="{{ route('form/trainers/list/page') }}"> Trainers</a></li>
                            <li><a href="{{ route('form/training/type/list/page') }}"> Training Type </a></li>
                        </ul>
                    </li>
                    <li class="menu-title"> <span>Administration</span> </li>
                    <li> <a href="assets.html"><i class="la la-object-ungroup">
                        </i> <span>Assets</span></a>
                    </li>
                    <li class="submenu"> <a href="#"><i class="la la-briefcase"></i>
                        <span> Jobs </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="user-dashboard.html"> User Dasboard </a></li>
                            <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                            <li><a href="jobs.html"> Manage Jobs </a></li>
                            <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                            <li><a href="shortlist-candidates.html"> Shortlist Candidates </a></li>
                            <li><a href="interview-questions.html"> Interview Questions </a></li>
                            <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                            <li><a href="experiance-level.html"> Experience Level </a></li>
                            <li><a href="candidates.html"> Candidates List </a></li>
                            <li><a href="schedule-timing.html"> Schedule timing </a></li>
                            <li><a href="apptitude-result.html"> Aptitude Results </a></li>
                        </ul>
                    </li>
                    <li class="menu-title"> <span>Pages</span> </li>
                    <li class="submenu"> <a href="#"><i class="la la-user"></i>
                        <span> Profile </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a href="profile.html"> Employee Profile </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div> -->
    <!-- /Sidebar -->
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
             <div class="row">  

                 <div class="col">  
                     <h2>{{ $name }}</h2>
                </div>     
                 <!-- <div class="col-sm-6 col-md-3">  
                    <a href="javascript:void(0);" class="btn btn-success btn-block" data-toggle="modal" data-target="#add_attendance">Add Attendance</a>
                </div>      -->
             </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card punch-status">
                        <div class="card-body">
                            <!-- <h5 class="card-title">Timesheet <small class="text-muted">11 Mar 2019</small></h5>
                            <div class="punch-det">
                                <h6>Punch In at</h6>
                                <p>Wed, 11th Mar 2019 10.00 AM</p>
                            </div> -->
                            <h5 class="card-title">Timesheet <small class="text-muted">{{ date('D, jS M Y') }}</small></h5>
                            <div class="punch-det">
                                <h6>Punch In at</h6>
                                <p>{{ $today->punch_in ?? 'N/A' }}</p>
                            </div>
                            @php
                                $currentHours = 0;
                                $progress = 0;
                                
                                if ($today && $today->punch_in) {
                                    if ($today->punch_out) {
                                        // Use production_hours if punch_out exists
                                        $currentHours = $today->production_hours ?? 0;
                                    } else {
                                        // Calculate hours from punch_in to current time
                                        $punchInTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $today->attendance_date . ' ' . $today->punch_in);
                                        $currentTime = \Carbon\Carbon::now();
                                        $diffInSeconds = $currentTime->diffInSeconds($punchInTime);
                                        $currentHours = round($diffInSeconds / 3600, 2);
                                    }
                                    // Progress based on 8 hours standard work day (100% = 8 hours)
                                    $progress = min(100, ($currentHours / 8) * 100);
                                }
                                
                                $breakHours = $today->break_hours ?? 0;
                                $overtimeHours = $today->overtime_hours ?? 0;
                            @endphp
                            <div class="punch-info" style="--progress: {{ $progress }}%;">
                                <div class="punch-hours">
                                    <span>{{ number_format($currentHours, 2) }} hrs</span>
                                </div>
                            </div>
                            <div class="punch-btn-section">
                                <!-- <button type="button" class="btn btn-primary punch-btn">Punch In</button> -->
                                <form action="{{ route('attendance/punchInOut') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employeeId" value="{{ $employeeId }}">
                                    <button type="submit" class="btn btn-primary punch-btn">
                                        @if($today && $today->punch_in && !$today->punch_out)
                                            Punch Out
                                        @else
                                            Punch In
                                        @endif
                                    </button>
                                </form>
                            </div>
                            <div class="statistics">
                                <div class="row">
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box">
                                            <p>Break</p>
                                            <h6>{{ number_format($breakHours, 2) }} hrs</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box">
                                            <p>Overtime</p>
                                            <h6>{{ number_format($overtimeHours, 2) }} hrs</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card att-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Statistics</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>Today <strong>3.45 <small>/ 8 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Week <strong>28 <small>/ 40 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>This Month <strong>90 <small>/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Remaining <strong>90 <small>/ 160 hrs</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Overtime <strong>4</strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 22%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card recent-activity">
                        <div class="card-body">
                            <h5 class="card-title">Today Activity</h5>
                            <ul class="res-activity-list">
                               {{-- Show logs for this attendance --}}
                                @php
                                    $hasActivity = false;
                                @endphp
                                @if($today && $today->logs->count() > 0)
                                    {{-- Show logs for this attendance --}}
                                    @foreach ($today->logs as $key => $log)
                                        @if($log->punch_in)
                                            @php $hasActivity = true; @endphp
                                            <li>
                                                <p class="mb-0">Punch In at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ $log->punch_in }}
                                                </p>
                                            </li>
                                        @endif
                                        @if($log->punch_out)
                                            @php $hasActivity = true; @endphp
                                            <li>
                                                <p class="mb-0">Punch Out at</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ $log->punch_out }}
                                                </p>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                                @if(!$hasActivity)
                                    <li>
                                        <p class="text-muted mb-0">No activity recorded today.</p>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input type="text" class="form-control floating datetimepicker">
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>-</option>
                            <option>Jan</option>
                            <option>Feb</option>
                            <option>Mar</option>
                            <option>Apr</option>
                            <option>May</option>
                            <option>Jun</option>
                            <option>Jul</option>
                            <option>Aug</option>
                            <option>Sep</option>
                            <option>Oct</option>
                            <option>Nov</option>
                            <option>Dec</option>
                        </select>
                        <label class="focus-label">Select Month</label>
                    </div>
                </div>
                <div class="col-sm-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>-</option>
                            <option>2019</option>
                            <option>2018</option>
                            <option>2017</option>
                            <option>2016</option>
                            <option>2015</option>
                        </select>
                        <label class="focus-label">Select Year</label>
                    </div>
                </div>
                <div class="col-sm-3">  
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date </th>
                                    <th>Punch In</th>
                                    <th>Punch Out</th>
                                    <th>Production</th>
                                    <th>Late In</th>
                                    <th>Overtime</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    @php
                                        $production_hours = floor($attendance->production_hours);
                                        $production_minutes = round(($attendance->production_hours - $production_hours) * 60);

                                        $ot_hours = floor($attendance->overtime_hours);
                                        $ot_minutes = round(($attendance->overtime_hours - $ot_hours) * 60);

                                        $statusColor = [
                                                        'overtime'  => 'text-info',
                                                        'on_time' => 'text-success',
                                                        'late' => 'text-danger',
                                                    ];
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->attendance_date }}</td>
                                        <td>{{ $attendance->punch_in ?? 'N/A' }}</td>
                                        <td>{{ $attendance->punch_out ?? 'N/A' }}</td>
                                        <!-- <td>{{ $attendance->production_hours ?? 'N/A' }}</td> -->
                                        <td>{{ sprintf('%02d:%02d', $production_hours, $production_minutes) ?? 'N/A' }}</td>
                                        <td>{{ $attendance->break_hours ?? 'N/A' }}</td>
                                        <!-- <td>{{ $attendance->overtime_hours ?? 'N/A' }}</td> -->
                                        <td>{{ sprintf('%02d:%02d', $ot_hours, $ot_minutes) ?? 'N/A' }}</td>
                                        <td>
                                            <a class="dropdown-item approve" href="javascript:void(0);">
                                                <i class="fa fa-dot-circle-o {{$statusColor[$attendance->status]}}"></i> {{ucwords(str_replace('_', ' ', $attendance->status))}}
                                            </a>

                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_attendance_{{ $attendance->id }}" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- <tr>
                                    <td>2</td>
                                    <td>20 Feb 2019</td>
                                    <td>10 AM</td>
                                    <td>7 PM</td>
                                    <td>9 hrs</td>
                                    <td>1 hrs</td>
                                    <td>0</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->

        </div>
        <!-- /Page Content -->
        <!-- Add Attendance Modal -->
        <div class="modal custom-modal fade" id="add_attendance" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('all/employee/save') }}" method="POST"></form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card punch-status">
                                        <div class="card-body">
                                            <h5 class="card-title">Timesheet <small class="text-muted">11 Mar 2019</small></h5>
                                            <div class="punch-det">
                                                <h6>Punch In at</h6>
                                                <p>Wed, 11th Mar 2019 10.00 AM</p>
                                            </div>
                                            <div class="punch-info">
                                                <div class="punch-hours">
                                                    <span>3.45 hrs</span>
                                                </div>
                                            </div>
                                            <div class="punch-det">
                                                <h6>Punch Out at</h6>
                                                <p>Wed, 20th Feb 2019 9.00 PM</p>
                                            </div>
                                            <div class="statistics">
                                                <div class="row">
                                                    <div class="col-md-6 col-6 text-center">
                                                        <div class="stats-box">
                                                            <p>Break</p>
                                                            <h6>1.21 hrs</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-6 text-center">
                                                        <div class="stats-box">
                                                            <p>Overtime</p>
                                                            <h6>3 hrs</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card recent-activity">
                                        <div class="card-body">
                                            <h5 class="card-title">Activity</h5>
                                            <ul class="res-activity-list">
                                                <li>
                                                    <p class="mb-0">Punch In at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        10.00 AM.
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="mb-0">Punch Out at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        11.00 AM.
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="mb-0">Punch In at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        11.15 AM.
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="mb-0">Punch Out at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        1.30 PM.
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="mb-0">Punch In at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        2.00 PM.
                                                    </p>
                                                </li>
                                                <li>
                                                    <p class="mb-0">Punch Out at</p>
                                                    <p class="res-activity-time">
                                                        <i class="fa fa-clock-o"></i>
                                                        7.30 PM.
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Attendance Modal -->

        <!-- Edit Attendance Modal -->
        @foreach ($attendances as $attendance)
        <div class="modal custom-modal fade" id="edit_attendance_{{ $attendance->id }}" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('attendance/update') }}" method="POST" id="edit_attendance_form_{{ $attendance->id }}">
                            @csrf
                            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                            <input type="hidden" name="employee_id" value="{{ $employeeId }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="attendance_date" value="{{ $attendance->attendance_date }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Punch In <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="punch_in" value="{{ $attendance->punch_in && strlen($attendance->punch_in) >= 5 ? substr($attendance->punch_in, 0, 5) : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Punch Out</label>
                                        <input type="time" class="form-control" name="punch_out" value="{{ $attendance->punch_out && strlen($attendance->punch_out) >= 5 ? substr($attendance->punch_out, 0, 5) : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update Attendance</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- /Edit Attendance Modal -->
   
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    @endsection
@endsection
