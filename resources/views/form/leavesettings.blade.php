
@extends('layouts.master')

@section('sidebar')
    @include('sidebar.index')
@endsection

@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Leave Settings</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leave Settings</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <!-- Annual Leave -->
                    <div class="card leave-box" id="leave_annual">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                Annual 											
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_annual" checked>
                                    <label class="onoffswitch-label" for="switch_annual">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="leave-item">
                            
                                <!-- Annual Days Leave -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">Edit</button>
                                    </div>
                                </div>
                                <!-- /Annual Days Leave -->
                                
                                <!-- Carry Forward -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <label class="d-block">Carry forward</label>
                                            <div class="leave-inline-form">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="carry_no" value="option1" disabled>
                                                    <label class="form-check-label" for="carry_no">No</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="carry_yes" value="option2" disabled>
                                                    <label class="form-check-label" for="carry_yes">Yes</label>
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Max</span>
                                                    </div>
                                                    <input type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <!-- /Carry Forward -->
                                
                                <!-- Earned Leave -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <label class="d-block">Earned leave</label>
                                            <div class="leave-inline-form">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="earned_no" value="option1" disabled>
                                                    <label class="form-check-label" for="earned_no">No</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="earned_yes" value="option2" disabled>
                                                    <label class="form-check-label" for="earned_yes">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <!-- /Earned Leave -->
                            </div>
                            
                            <!-- Custom Policy -->
                            <div class="custom-policy">
                                <div class="leave-header">
                                    <div class="title">Custom policy</div>
                                    <div class="leave-action">
                                        <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#add_custom_policy"><i class="fa fa-plus"></i> Add custom policy</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap leave-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="l-name">Name</th>
                                                <th class="l-days">Days</th>
                                                <th class="l-assignee">Assignee</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5 Year Service </td>
                                                <td>5</td>
                                                <td>
                                                    <a href="#" class="avatar"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}"></a>
                                                    <a href="#">John Doe</a>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="material-icons">more_vert</i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#edit_custom_policy"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_custom_policy"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /Custom Policy -->
                        </div>
                    </div>
                    <!-- /Annual Leave -->
                    
                    <!-- Sick Leave -->
                    <div class="card leave-box" id="leave_sick">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                Sick 											
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_sick" checked>
                                    <label class="onoffswitch-label" for="switch_sick">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="leave-item">
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Sick Leave -->
                    
                    <!-- Hospitalisation Leave -->
                    <div class="card leave-box" id="leave_hospitalisation">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                Hospitalisation 											
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_hospitalisation">
                                    <label class="onoffswitch-label" for="switch_hospitalisation">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="leave-item">
                            
                                <!-- Annual Days Leave -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <!-- /Annual Days Leave -->
                            </div>
                            
                            <!-- Custom Policy -->
                            <div class="custom-policy">
                                <div class="leave-header">
                                    <div class="title">Custom policy</div>
                                    <div class="leave-action">
                                        <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#add_custom_policy"><i class="fa fa-plus"></i> Add custom policy</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap leave-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="l-name">Name</th>
                                                <th class="l-days">Days</th>
                                                <th class="l-assignee">Assignee</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5 Year Service </td>
                                                <td>5</td>
                                                <td>
                                                    <a href="#" class="avatar"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}"></a>
                                                    <a href="#">John Doe</a>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="material-icons">more_vert</i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /Custom Policy -->
                            
                        </div>
                    </div>
                    <!-- /Hospitalisation Leave -->
                    
                    <!-- Maternity Leave -->
                    <div class="card leave-box" id="leave_maternity">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                Maternity  <span class="subtitle">Assigned to female only</span>
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_maternity" checked>
                                    <label class="onoffswitch-label" for="switch_maternity">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="leave-item">
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Maternity Leave -->
                    
                    <!-- Paternity Leave -->
                    <div class="card leave-box" id="leave_paternity">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                Paternity  <span class="subtitle">Assigned to male only</span>
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_paternity">
                                    <label class="onoffswitch-label" for="switch_paternity">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="leave-item">
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Paternity Leave -->
                    
                    <!-- Custom Create Leave -->
                    <div class="card leave-box mb-0" id="leave_custom01">
                        <div class="card-body">
                            <div class="h3 card-title with-switch">
                                LOP 											
                                <div class="onoffswitch">
                                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_custom01" checked>
                                    <label class="onoffswitch-label" for="switch_custom01">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                                <button class="btn btn-danger leave-delete-btn" type="button">Delete</button>
                            </div>
                            <div class="leave-item">
                            
                                <!-- Annual Days Leave -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <div class="form-group">
                                                <label>Days</label>
                                                <input type="text" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">Edit</button>
                                    </div>
                                </div>
                                <!-- /Annual Days Leave -->
                                
                                <!-- Carry Forward -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <label class="d-block">Carry forward</label>
                                            <div class="leave-inline-form">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="carryForward" id="carry_no_01" value="option1" disabled>
                                                    <label class="form-check-label" for="carry_no_01">No</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="carryForward" id="carry_yes_01" value="option2" disabled>
                                                    <label class="form-check-label" for="carry_yes_01">Yes</label>
                                                </div>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Max</span>
                                                    </div>
                                                    <input type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <!-- /Carry Forward -->
                                
                                <!-- Earned Leave -->
                                <div class="leave-row">
                                    <div class="leave-left">
                                        <div class="input-box">
                                            <label class="d-block">Earned leave</label>
                                            <div class="leave-inline-form">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" disabled>
                                                    <label class="form-check-label" for="inlineRadio1">No</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" disabled>
                                                    <label class="form-check-label" for="inlineRadio2">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="leave-right">
                                        <button class="leave-edit-btn">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                                <!-- /Earned Leave -->
                            </div>
                            
                            <!-- Custom Policy -->
                            <div class="custom-policy">
                                <div class="leave-header">
                                    <div class="title">Custom policy</div>
                                    <div class="leave-action">
                                        <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#add_custom_policy"><i class="fa fa-plus"></i> Add custom policy</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap leave-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="l-name">Name</th>
                                                <th class="l-days">Days</th>
                                                <th class="l-assignee">Assignee</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5 Year Service </td>
                                                <td>5</td>
                                                <td>
                                                    <a href="#" class="avatar"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-02.jpg') }}"></a>
                                                    <a href="#">John Doe</a>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a aria-expanded="false" data-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="material-icons">more_vert</i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#edit_custom_policy"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#delete_custom_policy"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /Custom Policy -->
                        </div>
                    </div>
                    <!-- /Custom Create Leave -->
                </div>
            </div>

            <!-- Leave Types Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Leave Types</h4>
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave_type"><i class="fa fa-plus"></i> Add Leave Type</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table mb-0 datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Leave Type</th>
                                            <th>Days Per Year</th>
                                            <th>Description</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveTypes as $index => $type)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $type->number_of_leave }}</td>
                                            <td>{{ $type->description ?? '-' }}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-leave-type" href="#" data-toggle="modal" data-target="#edit_leave_type"
                                                            data-id="{{ $type->id }}"
                                                            data-name="{{ $type->name }}"
                                                            data-days="{{ $type->number_of_leave }}"
                                                            data-description="{{ $type->description }}">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>
                                                        <a class="dropdown-item delete-leave-type" href="#" data-toggle="modal" data-target="#delete_leave_type"
                                                            data-id="{{ $type->id }}">
                                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Leave Types Section -->

        </div>
        <!-- /Page Content -->

        <!-- Add Custom Policy Modal -->
        <div id="add_custom_policy" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Custom Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Policy Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Days <span class="text-danger">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group leave-duallist">
                                <label>Add employee</label>
                                <div class="row">
                                    <div class="col-lg-5 col-sm-5">
                                        <select name="customleave_from" id="customleave_select" class="form-control" size="5" multiple="multiple">
                                            <option value="1">Bernardo Galaviz </option>
                                            <option value="2">Jeffrey Warden</option>
                                            <option value="2">John Doe</option>
                                            <option value="2">John Smith</option>
                                            <option value="3">Mike Litorus</option>
                                        </select>
                                    </div>
                                    <div class="multiselect-controls col-lg-2 col-sm-2">
                                        <button type="button" id="customleave_select_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
                                        <button type="button" id="customleave_select_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
                                        <button type="button" id="customleave_select_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
                                        <button type="button" id="customleave_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
                                    </div>
                                    <div class="col-lg-5 col-sm-5">
                                        <select name="customleave_to" id="customleave_select_to" class="form-control" size="8" multiple="multiple"></select>
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
        <!-- /Add Custom Policy Modal -->
        
        <!-- Edit Custom Policy Modal -->
        <div id="edit_custom_policy" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Custom Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label>Policy Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="LOP">
                            </div>
                            <div class="form-group">
                                <label>Days <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="4">
                            </div>
                            <div class="form-group leave-duallist">
                                <label>Add employee</label>
                                <div class="row">
                                    <div class="col-lg-5 col-sm-5">
                                        <select name="edit_customleave_from" id="edit_customleave_select" class="form-control" size="5" multiple="multiple">
                                            <option value="1">Bernardo Galaviz </option>
                                            <option value="2">Jeffrey Warden</option>
                                            <option value="2">John Doe</option>
                                            <option value="2">John Smith</option>
                                            <option value="3">Mike Litorus</option>
                                        </select>
                                    </div>
                                    <div class="multiselect-controls col-lg-2 col-sm-2">
                                        <button type="button" id="edit_customleave_select_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
                                        <button type="button" id="edit_customleave_select_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
                                        <button type="button" id="edit_customleave_select_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
                                        <button type="button" id="edit_customleave_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
                                    </div>
                                    <div class="col-lg-5 col-sm-5">
                                        <select name="customleave_to" id="edit_customleave_select_to" class="form-control" size="8" multiple="multiple"></select>
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
        <!-- /Edit Custom Policy Modal -->
        
        <!-- Delete Custom Policy Modal -->
        <div class="modal custom-modal fade" id="delete_custom_policy" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Custom Policy</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
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
        <!-- /Delete Custom Policy Modal -->

        <!-- Add Leave Type Modal -->
        <div id="add_leave_type" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/leavetype/save') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Leave Type Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required placeholder="e.g. Sick Leave">
                            </div>
                            <div class="form-group">
                                <label>Number of Days <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="number_of_leave" required min="0" placeholder="e.g. 10">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Optional description"></textarea>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Leave Type Modal -->

        <!-- Edit Leave Type Modal -->
        <div id="edit_leave_type" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/leavetype/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="edit_leave_type_id">
                            <div class="form-group">
                                <label>Leave Type Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="edit_leave_type_name" required>
                            </div>
                            <div class="form-group">
                                <label>Number of Days <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="number_of_leave" id="edit_leave_type_days" required min="0">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" id="edit_leave_type_description" rows="3"></textarea>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Type Modal -->

        <!-- Delete Leave Type Modal -->
        <div id="delete_leave_type" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave Type</h3>
                            <p>Are you sure you want to delete this leave type?</p>
                        </div>
                        <form action="{{ route('form/leavetype/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="delete_leave_type_id">
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn btn-block">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Type Modal -->
       
    </div>
    <!-- /Page Wrapper -->
    @section('script')
    <script>
        // Edit Leave Type Modal - populate fields
        $(document).on('click', '.edit-leave-type', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var days = $(this).data('days');
            var description = $(this).data('description');
            
            $('#edit_leave_type_id').val(id);
            $('#edit_leave_type_name').val(name);
            $('#edit_leave_type_days').val(days);
            $('#edit_leave_type_description').val(description);
        });

        // Delete Leave Type Modal - set id
        $(document).on('click', '.delete-leave-type', function() {
            var id = $(this).data('id');
            $('#delete_leave_type_id').val(id);
        });
    </script>
    @endsection
@endsection
