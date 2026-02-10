
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
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Leaves <span id="year"></span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leaves</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Apply Leave</a>
                    </div>
                </div>
            </div>
            
            <!-- Leave Statistics -->
            <div class="row">
                @foreach ($leaveBalances as $leaveType => $balance)
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>{{ $leaveType }}</h6>
                        <h4>{{ $balance }}</h4>
                    </div>
                </div>
                @endforeach
                <div class="col-md-3">
                    <div class="stats-info">
                        <h6>Pending Request</h6>
                        <h4>{{ $pendingRequests->count() }}</h4>
                    </div>
                </div>
            </div>
            <!-- /Leave Statistics -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No of Days</th>
                                    <th>Reason</th>
                                    <th class="text-center">Status</th>
                                    <!-- <th>Approved by</th> -->
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @if(!empty($leaves))
                                    @foreach ($leaves as $items )  
                                        <tr>
                                            <td hidden class="id">{{ $items->id }}</td>
                                            <td class="leave_type" data-id="{{ $items->leave_type->id }}">{{ $items->leave_type->name }}</td>
                                            <td class="from_date">{{ $items->from_date ? \Carbon\Carbon::parse($items->from_date)->format('d-m-Y') : '' }}</td>
                                            <td class="to_date">{{ $items->to_date ? \Carbon\Carbon::parse($items->to_date)->format('d-m-Y') : '' }}</td>
                                            <td class="day">{{ $items->day }}</td>
                                            <td class="reason">{{ $items->reason }}</td>
                                            <td class="text-center">
                                                <div class="action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        @switch($items->status)
                                                            @case('pending')
                                                                <i class="fa fa-dot-circle-o text-info"></i> Pending
                                                                @break

                                                            @case('approved')
                                                                <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                                @break

                                                            @case('declined')
                                                                <i class="fa fa-dot-circle-o text-danger"></i> Declined
                                                                @break

                                                            @default
                                                                <i class="fa fa-dot-circle-o text-purple"></i> New
                                                        @endswitch
                                                    </a>
                                                </div>
                                            </td>
                                            <!-- <td>
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-xs"><img src="{{URL::to('assets/img/profiles/avatar-09.jpg')}}" alt=""></a>
                                                    <a href="#">{{ $items->approver->name ?? 'N/A' }}</a>
                                                </h2>
                                            </td> -->
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" {{ $items->status !== 'pending' ? 'disabled' : '' }} data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit_leave" href="#" {{ $items->status !== 'pending' ? 'disabled' : '' }} data-toggle="modal" data-id="{{ $items->id }}" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item delete_leave" href="#" data-toggle="modal" data-id="{{ $items->id }}" data-target="#delete_leave"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                 @endif
                                <!-- <tr>
                                    <td>Casual Leave</td>
                                    <td>10 Jan 2019</td>
                                    <td>10 Jan 2019</td>
                                    <td>First Half</td>
                                    <td>Going to Hospital</td>
                                    <td class="text-center">
                                        <div class="action-label">
                                            <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                <i class="fa fa-dot-circle-o text-danger"></i> Declined
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="profile.html" class="avatar avatar-xs"><img src="{{URL::to('assets/img/profiles/avatar-09.jpg')}}" alt=""></a>
                                            <a href="#">Richard Miles</a>
                                        </h2>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
              
        </div>
        <!-- /Page Content -->
       
		<!-- Add Leave Modal -->
        <div id="add_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="add_leave_form" method="POST" action="{{ route('form/leaves/save') }}">
                            @csrf
                            <input class="form-control" name="employee_id" type="hidden" value="{{ $id }}">
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select class="select" name="leave_type">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leave_types as $type)
                                    <option value="{{ $type->id }}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" name="from_date" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>To <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input class="form-control datetimepicker" name="to_date" type="text">
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="number_of_days" type="text">
                            </div>
                            <div class="form-group">
                                <label>Remaining Leaves <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="remaining_leaves" value="12" type="text">
                            </div> -->
                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="4" class="form-control" name="leave_reason"></textarea>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Leave Modal -->
        
        <!-- Edit Leave Modal -->
        <div id="edit_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit_leave_form"  method="POST" action="{{ route('form/leaves/update') }}">
                            @csrf
                            <input type="hidden" class="form-control" id="e_id" name="id" value="" >
                            <div class="form-group">
                                <label>Leave Type <span class="text-danger">*</span></label>
                                <select id="leave_type" class="select leave_type" name="leave_type">
                                    <option value="">Select Leave Type</option>
                                    @foreach ($leave_types as $type)
                                    <option value="{{ $type->id }}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input id="from_date" class="form-control datetimepicker" name="from_date" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>To <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input id="to_date" class="form-control datetimepicker" name="to_date" value="01-01-2019" type="text">
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="number_of_days" type="text" value="2">
                            </div>
                            <div class="form-group">
                                <label>Remaining Leaves <span class="text-danger">*</span></label>
                                <input class="form-control" readonly name="remaining_leaves" value="12" type="text">
                            </div> -->
                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea id="leave_reason" rows="4" class="form-control" name="leave_reason">Going to hospital</textarea>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Modal -->
        
        <!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_leave" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to Cancel this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <!-- <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a> -->
                                    <form action="{{ route('form/leaves/delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="d_id" value="">
                                        <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Modal -->

    </div>
    <!-- /Page Wrapper -->
    @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.edit_leave',function()
        {
            var _this = $(this).parents('tr');            
            $('#e_id').val(_this.find('.id').text());
            $('#edit_name').val(_this.find('.name').text());
            $('#leave_reason').val(_this.find('.reason').text());  
            $('#from_date').val(_this.find('.from_date').text());  
            $('#to_date').val(_this.find('.to_date').text());  
            // $('#leave_type').val(_this.find('.leave_type').text());  
            let leaveTypeName = _this.find('.leave_type').text().trim();
            let leaveTypeId = _this.find('.leave_type').data('id');

            $('#leave_type option').each(function() {
                if ($(this).text().trim() === leaveTypeName) {
                    $('#leave_type').val(leaveTypeId);
                    $('#select2-leave_type-container').text($(this).text().trim());
                    $('#select2-leave_type-container').attr('title', $(this).text().trim());
                }
            });

        });
        $(document).on('click','.delete_leave',function()
        {
            var _this = $(this).parents('tr');            
            $('#d_id').val(_this.find('.id').text());
        });
    </script>
    @endsection
@endsection

