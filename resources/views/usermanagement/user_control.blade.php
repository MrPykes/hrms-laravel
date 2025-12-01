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
                    @if (Auth::user()->role_name=='Admin')
                        <li class="menu-title"> <span>Authentication</span> </li>
                        <li class="submenu">
                            <a href="#" class="noti-dot">
                                <i class="la la-user-secret"></i> <span> User Controller</span> <span class="menu-arrow"></span>
                            </a>
                            <ul style="display: none;">
                                <li><a class="active" href="{{ route('userManagement') }}">All User</a></li>
                                <li><a href="{{ route('activity/log') }}">Activity Log</a></li>
                                <li><a href="{{ route('activity/login/logout') }}">Activity User</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="menu-title">
                        <span>Employees</span>
                    </li>
                    <li class="submenu">
                        <a href="#">
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
                            <li><a href="{{ route('attendance/employee/page') }}">Attendance (Employee)</a></li>
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

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">User Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add User</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('search/user/list') }}" method="POST">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="name" name="name">
                            <label class="focus-label">User Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="name" name="role_name">
                            <label class="focus-label">Role Name</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" id="name" name="status">
                            <label class="focus-label">Status</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>  
                    </div>
                </div>
            </form>     
            <!-- /Search Filter -->
            {{-- message --}}
            {!! Toastr::message() !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Departement</th>
                                    <!-- <th class="text-right">Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key=>$user )
                                <tr>
                                    <td hidden class="ids">{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <!-- <td class="id">{{ $user->employee->company_id }}</td> -->
                                    <td>
                                        <span hidden class="image">{{ $user->employee->avatar}}</span>
                                        <h2 class="table-avatar">
                                            <a href="{{ url('employee/profile/'.$user->employee->id) }}" class="avatar"><img src="{{ URL::to('/assets/images/'. ($user->employee->avatar ?? 'photo_defaults.jpg')) }}" alt="{{ $user->employee->avatar }}"></a>
                                            <a href="{{ url('employee/profile/'.$user->employee->id) }}" class="name">{{ $user->employee->name }}</span></a>
                                        </h2>
                                    </td>
                                    <td class="email">{{ $user->email }}</td>
                                    <td class="position">{{ $user->employee->position->name }}</td>
                                    <td>
                                        @if ($user->role->name=='Administrator')
                                            <span class="badge bg-inverse-danger role_name">{{ $user->role->name }}</span>
                                            @elseif ($user->role->name=='Super Admin')
                                            <span class="badge bg-inverse-warning role_name">{{ $user->role->name }}</span>
                                            @elseif ($user->role->name=='Normal User')
                                            <span class="badge bg-inverse-info role_name">{{ $user->role->name }}</span>
                                            @elseif ($user->role->name=='Client')
                                            <span class="badge bg-inverse-success role_name">{{ $user->role->name }}</span>
                                            @elseif ($user->role->name=='Employee')
                                            <span class="badge bg-inverse-dark role_name">{{ $user->role->name }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- <div class="dropdown action-label">
                                            @if ($user->status->type_name =='Active')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-success"></i>
                                                    <span class="statuss">{{ $user->status->type_name  }}</span>
                                                </a>
                                                @elseif ($user->status->type_name =='Inactive')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-info"></i>
                                                    <span class="statuss">{{ $user->status->type_name  }}</span>
                                                </a>
                                                @elseif ($user->status->type_name =='Disable')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-danger"></i>
                                                    <span class="statuss">{{ $user->status->type_name  }}</span>
                                                </a>
                                                @elseif ($user->status->type_name =='')
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-dot-circle-o text-dark"></i>
                                                    <span class="statuss">N/A</span>
                                                </a>
                                            @endif
                                            
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-dot-circle-o text-success"></i> Active
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-dot-circle-o text-warning"></i> Inactive
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <i class="fa fa-dot-circle-o text-danger"></i> Disable
                                                </a>
                                            </div>
                                        </div> -->
                                        @php
                                            // Map status to text and color
                                        $statusMap = [
                                            'active'   => 'text-success',
                                            'inactive' => 'text-info',
                                            'disable'  => 'text-danger',
                                        ];
                                        @endphp

                                        <div class="dropdown action-label">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-dot-circle-o {{ $statusMap[strtolower($user->status->type_name)] }}"></i> {{ $user->status->type_name }}
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @foreach ($status_user as $status)  
                                                    <a class="dropdown-item update_status" href="javascript:void(0);"
                                                        data-toggle="modal" 
                                                        data-target="#update_user_status"
                                                        data-id="{{ $user->id }}" 
                                                        data-status="{{$status->id}}">
                                                        <i class="fa fa-dot-circle-o {{$statusMap[strtolower($status->type_name)]}}"></i> {{$status->type_name}}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="department">{{ $user->employee->position->department->name }}</td>
                                    <!-- <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item userUpdate" data-toggle="modal" data-id="'.$user->id.'" data-target="#edit_user"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item userDelete" href="#" data-toggle="modal" ata-id="'.$user->id.'" data-target="#delete_user"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
        

        <!-- Add User Modal -->
        <div id="add_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user/add/save') }}" method="POST">
                            @csrf
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <!-- <label>Full Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="" name="name" value="{{ old('name') }}" placeholder="Enter Name"> -->
                                        <!-- <label for="mySearchableDropdown">Choose an option:</label>
                                        <input class="form-control" list="employeeList" id="mySearchableDropdown" name="mySearchableDropdown">

                                        <datalist id="employeeList">
                                            @foreach ($employees as $employee)
                                            <option value="{{ $employee->name }}">
                                            @endforeach
                                        </datalist> -->
                                        <label>Select Employee</label>
                                        <select class="form-control employee-select" name="employee_id" required>
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Role Name</label>
                                    <select class="select" name="role" id="role">
                                        <option selected disabled> --Select --</option>
                                        @foreach ($roles as $role )
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Repeat Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Choose Repeat Password">
                                </div>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add User Modal -->
				
        <!-- Edit User Modal -->
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <br>
                    <div class="modal-body">
                        <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="rec_id" id="e_id" value="">
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input class="form-control" type="text" name="name" id="e_name" value="" />
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Email</label>
                                    <input class="form-control" type="text" name="email" id="e_email" value=""/>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <label>Role Name</label>
                                    <select class="select" name="role" id="e_role">
                                        @foreach ($roles as $role )
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Status</label>
                                    <select class="select" name="status" id="e_status">
                                        @foreach ($status_user as $status )
                                        <option value="{{ $status->type_name }}">{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row"> 
                                 <div class="col-sm-6"> 
                                    <label>Position</label>
                                    <select disbaled class="select" name="position" id="e_position">
                                        @foreach ($position as $positions )
                                        <option value="{{ $positions->position }}">{{ $positions->position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Department</label>
                                    <select disbaled class="select" name="department" id="e_department">
                                        @foreach ($department as $departments )
                                        <option value="{{ $departments->name }}">{{ $departments->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-sm-6"> 
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input class="form-control" type="text" id="e_phone_number" name="phone" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-sm-6"> 
                                    <label>Photo</label>
                                    <input class="form-control" type="file" id="image" name="images">
                                    <input type="hidden" name="hidden_image" id="e_image" value="">
                                </div>
                            </div>
                            <br>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Salary Modal -->
				
        <!-- Delete User Modal -->
        <div class="modal custom-modal fade" id="delete_user" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete User</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('user/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" class="e_id" value="">
                                <input type="hidden" name="avatar" class="e_avatar" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete User Modal -->
        <!-- Update Status Modal -->
        <div class="modal custom-modal fade" id="update_user_status" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Update Status</h3>
                            <p>Are you sure want to update Status?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('user/status/update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" id="u_id" value="">
                                <input type="hidden" name="status_id" id="u_sid" value="">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" id="btn_update_status" class="btn btn-primary continue-btn submit-btn">Update</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete User Modal -->
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_name').val(_this.find('.name').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_image').val(_this.find('.image').text());

            var name_role = (_this.find(".role_name").text());
            var _option = '<option selected value="' + name_role+ '">' + _this.find('.role_name').text() + '</option>'
            $( _option).appendTo("#e_role_name");

            var position = (_this.find(".position").text());
            var _option = '<option selected value="' +position+ '">' + _this.find('.position').text() + '</option>'
            $( _option).appendTo("#e_position");

            var department = (_this.find(".department").text());
            var _option = '<option selected value="' +department+ '">' + _this.find('.department').text() + '</option>'
            $( _option).appendTo("#e_department");

            var statuss = (_this.find(".statuss").text());
            var _option = '<option selected value="' +statuss+ '">' + _this.find('.statuss').text() + '</option>'
            $( _option).appendTo("#e_status");
            
        });

        $(document).on('click','.update_status',function()
        {
            var _this = $(this).parents('tr');
            $('#u_id').val($(this).data('id'));
            $('#u_sid').val($(this).data('status'));
            $('#btn_update_status').text($(this).text());
        })
    </script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
            $('.e_avatar').val(_this.find('.image').text());
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.employee-select').select2({
                placeholder: "Search employee...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    @endsection

@endsection
