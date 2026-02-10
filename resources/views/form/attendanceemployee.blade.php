
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')

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
                     <h2>{{ $employee->name }}</h2>
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
                                    <input type="hidden" name="employeeId" value="{{ $employee->employee_id }}">
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
                                @foreach ($attendance as $row)
                                    @php
                                        $production_hours = floor($row->production_hours);
                                        $production_minutes = round(($row->production_hours - $production_hours) * 60);

                                        $ot_hours = floor($row->overtime_hours);
                                        $ot_minutes = round(($row->overtime_hours - $ot_hours) * 60);

                                        $statusColor = [
                                                        'overtime'  => 'text-info',
                                                        'on_time' => 'text-success',
                                                        'late' => 'text-danger',
                                                    ];
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $row->attendance_date }}</td>
                                        <td>{{ $row->punch_in ?? 'N/A' }}</td>
                                        <td>{{ $row->punch_out ?? 'N/A' }}</td>
                                        <!-- <td>{{ $row->production_hours ?? 'N/A' }}</td> -->
                                        <td>{{ sprintf('%02d:%02d', $production_hours, $production_minutes) ?? 'N/A' }}</td>
                                        <td>{{ $row->break_hours ?? 'N/A' }}</td>
                                        <!-- <td>{{ $row->overtime_hours ?? 'N/A' }}</td> -->
                                        <td>{{ sprintf('%02d:%02d', $ot_hours, $ot_minutes) ?? 'N/A' }}</td>
                                        <td>
                                            <a class="dropdown-item approve" href="javascript:void(0);">
                                                <i class="fa fa-dot-circle-o {{$statusColor[$row->status]}}"></i> {{ucwords(str_replace('_', ' ', $row->status))}}
                                            </a>

                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_attendance_{{ $row->id }}" title="Edit">
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
        @foreach ($attendance as $row)
        <div class="modal custom-modal fade" id="edit_attendance_{{ $row->id }}" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('attendance/update') }}" method="POST" id="edit_attendance_form_{{ $row->id }}">
                            @csrf
                            <input type="hidden" name="attendance_id" value="{{ $row->id }}">
                            <input type="hidden" name="employee_id" value="{{ $employee->employeeId }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="attendance_date" value="{{ $row->attendance_date }}" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Punch In <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="punch_in" value="{{ $row->punch_in && strlen($row->punch_in) >= 5 ? substr($row->punch_in, 0, 5) : '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Punch Out</label>
                                        <input type="time" class="form-control" name="punch_out" value="{{ $row->punch_out && strlen($row->punch_out) >= 5 ? substr($row->punch_out, 0, 5) : '' }}">
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
