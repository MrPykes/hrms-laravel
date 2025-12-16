
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">
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
            
            <!-- Search Filter -->
            <form action="{{ route('attendance/page') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" name="name" class="form-control floating" value="{{ $request['name'] ?? '' }}">
                            <label class="focus-label">Employee Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus select-focus">
                           <select id="month" name="month" class="select floating">
                                <option value="">-</option>
                                @foreach([
                                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                    5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                    9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                                ] as $num => $name)
                                    <option value="{{ $num }}" {{ ($request->month == $num) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>

                            <label class="focus-label">Select Month</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus select-focus">
                            <select id="year" name="year" class="select floating" value="{{ $request->year }}"> 
                                <option value="">-</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ ($request->year == $year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Select Year</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <button type="submit" class="btn btn-success btn-block"> Search </button>  
                    </div>     
                </div>
            </form>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    @foreach ($dates as $date)
                                        <th>{{ $date }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key =>$value)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a class="avatar avatar-xs" href="{{ route('attendance/employee/admin', $value['employee']->id) }}"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-09.jpg') }}"></a>
                                                <a href="{{ route('attendance/employee/admin', $value['employee']->id) }}">{{ $value['employee']->name }}</a>
                                            </h2>
                                        </td>
                                        @foreach ($value['attendance'] as $attendance)
                                         {{-- now show based on status --}}
                                            @if ($attendance)
                                                @if ($attendance == 'Vacation Leave' || $attendance == 'Sick Leave' || $attendance == 'Emergency Leave')
                                                    <td>
                                                        <a href="javascript:void(0);">
                                                            <i class="fa-solid fa-person-walking"></i>  
                                                        </a>
                                                    </td>
                                                @elseif ($attendance == 'Work From Home')
                                                    <td>
                                                        <a href="javascript:void(0);">
                                                            <i class="fa-solid fa-house"></i>  
                                                        </a>
                                                    </td>
                                                @elseif ($attendance == 'Holiday')
                                                    <td>
                                                        <a href="javascript:void(0);">
                                                            <i class="fa-solid fa-calendar-days"></i>  
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a href="javascript:void(0);" 
                                                            data-toggle="modal" 
                                                            data-target="#attendance_info"
                                                            data-details='@json($attendance)'>
                                                            <i class="fa fa-check text-success"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            <!-- @elseif ($attendance === 'half')
                                                <td>
                                                    <div class="half-day d-flex justify-content-center">
                                                        <span class="first-off me-1">
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info">
                                                                <i class="fa fa-check text-success"></i>
                                                            </a>
                                                        </span>
                                                        <span class="second-off">
                                                            <i class="fa fa-close text-danger"></i>
                                                        </span>
                                                    </div>
                                                </td> -->
                                            @else
                                                <td>
                                                    <i class="fa fa-close text-danger"></i>
                                                </td>
                                            @endif
                                            <!-- <td>
                                                <div class="half-day">
                                                    <span class="first-off"><i class="fa fa-close text-danger"></i></span> 
                                                    <span class="first-off"><a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info"><i class="fa fa-check text-success"></i></a></span>
                                                </div>
                                            </td> -->
                                            <!-- <td><i class="fa fa-close text-danger"></i> </td>
                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#attendance_info"><i class="fa fa-check text-success"></i></a></td> -->
                                     @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        
        <!-- Attendance Modal -->
        <!-- <div class="modal custom-modal fade" id="attendance_info" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attendance Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                    </div>
                </div>
            </div>
        </div> -->
        <div class="modal custom-modal fade" id="attendance_info" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attendance Info</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <!-- LEFT SIDE -->
                            <div class="col-md-6">
                                <div class="card punch-status">
                                    <div class="card-body">

                                        <h5 class="card-title">Timesheet <small class="timesheet-date text-muted"></small></h5>
                                        <div class="punch-det">
                                            <h6>Punch In at</h6>
                                            <p class="punch-in-text"></p>
                                        </div>
                                        <div class="punch-info" style="--progress: 40%;">
                                            <div class="punch-hours">
                                                <span class="production-hours-text"></span>
                                            </div>
                                        </div>
                                        <!-- <div class="punch-btn-section">
                                            <form action="{{ route('attendance/punchInOut') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="employeeId" value="">
                                                <button type="submit" class="btn btn-primary punch-btn">
                                                    Punch In
                                                </button>
                                            </form>
                                        </div> -->
                                        <div class="statistics">
                                            <div class="row">

                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Break</p>
                                                        <h6 class="break-hours-text"></h6>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Overtime</p>
                                                        <h6 class="overtime-hours-text"></h6>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT SIDE -->
                            <div class="col-md-6">
                                <div class="card recent-activity">
                                    <div class="card-body">
                                        <h5 class="card-title">Activity</h5>

                                        <ul class="res-activity-list"></ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- /Attendance Modal -->
        
    </div>
    <!-- Page Wrapper -->


    @section('script')
    <script>
        $(document).ready(function() {
            $('#attendance_info').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let details = button.data('details'); // JSON object
                console.log('details', details);
                // Timesheet data
                $('.timesheet-date').text(details.attendance_date ? new Date(details.attendance_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '');
                $('.punch-in-text').text(details.punch_in ?? '—');
                $('.punch-out-text').text(details.punch_out ?? '—');
                $('.production-hours-text').text(details.production_hours ?? '0 hrs');
                $('.break-hours-text').text(details.break_hours ?? '0 hrs');    
                $('.overtime-hours-text').text(details.overtime_hours ?? '0 hrs');

                // Activity list
                let activityList = $('.res-activity-list');
                activityList.empty();

                if (details.logs && details.logs.length > 0) {
                    details.logs.forEach(log => {
                        activityList.append(`
                            <li>
                                <p class="mb-0">Punch In at</p>
                                <p class="res-activity-time">
                                    <i class="fa fa-clock-o"></i> ${log.punch_in}
                                </p>
                            </li>
                            <li>
                                <p class="mb-0">Punch Out at</p>
                                <p class="res-activity-time">
                                    <i class="fa fa-clock-o"></i> ${log.punch_out}
                                </p>
                            </li>
                        `);
                    });
                } else {
                    activityList.append(`<li>No activity logs.</li>`);
                }
            });
        });

    </script>
    @endsection
@endsection
