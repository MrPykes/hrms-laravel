
@extends('layouts.master')
@section('sidebar')
    @include('sidebar.index')
@endsection
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Expense Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Expense Report</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_expense"><i class="fa fa-plus"></i> Add Expense</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Search Filter -->
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3"> 
                    <div class="form-group form-focus select-focus">
                        <select class="select floating"> 
                            <option>Select buyer</option>
                            <option>Loren Gatlin</option>
                            <option>Tarah Shropshire</option>
                        </select>
                        <label class="focus-label">Purchased By</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">  
                    <a href="#" class="btn btn-success btn-block"> Search </a>  
                </div>     
            </div>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Purchase From</th>
                                    <th>Purchase Date</th>
                                    <th>Purchased By</th>
                                    <th>Amount</th>
                                    <th>Paid By</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $item )
                                
                                <tr>
                                    <td>
                                        <strong>{{ $item->item }}</strong>
                                    </td>
                                    <td>{{ $item->purchase_from }}</td>
                                    <td>{{ $item->purchase_date }}</td>
                                    <td>
                                        <a href="profile.html" class="avatar avatar-xs">
                                            <img src="{{URL::to('assets/img/profiles/avatar-04.jpg')}}" alt="">
                                        </a>
                                        <h2><a href="profile.html">{{$item->purchaser->name}}</a></h2>
                                    </td>
                                    <td>{{ $item->amount }}</td>
                                    <td>{{ $item->paid_by }}</td>
                                    <td class="text-center">
                                        <div class="dropdown action-label">
                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-dot-circle-o text-danger"></i> Pending
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-danger"></i> Pending</a>
                                                <a class="dropdown-item" href="#"><i class="fa fa-dot-circle-o text-success"></i> Approved</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item edit-expense" href="#" data-id="{{ $item->id }}" data-toggle="modal" data-target="#edit_expense"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item delete-expense" href="#" data-id="{{ $item->id }}" data-toggle="modal" data-target="#delete_expense"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <!-- /Page Content -->

        <!-- Add Expense Modal -->
        <div class="modal custom-modal fade" id="add_expense" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('expense/store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Item <span class="text-danger">*</span></label>
                                <input type="text" name="item" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase From <span class="text-danger">*</span></label>
                                <input type="text" name="purchase_from" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase Date <span class="text-danger">*</span></label>
                                <input type="date" name="purchase_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Purchased By <span class="text-danger">*</span></label>
                                <select name="purchased_by" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Paid By <span class="text-danger">*</span></label>
                                <select name="paid_by" class="form-control" required>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="bank">Bank</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Remaks</label>
                                <textarea class="form-control" name="remarks" id=""></textarea>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Expense Modal -->


        <!-- Edit Expense Modal -->
        <div class="modal custom-modal fade" id="edit_expense" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Expense</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('expense/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id">

                            <div class="form-group">
                                <label>Item</label>
                                <input type="text" name="item" id="e_item" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Purchase From</label>
                                <input type="text" name="purchase_from" id="e_purchase_from" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="date" name="purchase_date" id="e_purchase_date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Purchased By</label>
                                <select name="purchased_by" id="e_purchased_by" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount" id="e_amount" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Paid By</label>
                                <select name="paid_by" id="e_paid_by" class="form-control">
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="bank">Bank</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="e_status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" id="e_remarks"></textarea>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">
                                    Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Expense Modal -->


        <!-- Delete Expense Modal -->
        <div class="modal custom-modal fade" id="delete_expense" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Expense</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <!-- <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a> -->
                                    <form action="{{ route('expense/delete') }}" method="POST">
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
        <!-- /Delete Department Modal -->
        
    </div>
    <!-- /Page Wrapper -->
      @section('script')
    {{-- update js --}}
    <script>
       $(document).on('click', '.edit-expense', function () {
            let expenseId = $(this).data('id');
            
            $.ajax({
                url: `/expense/edit/${expenseId}`,
                type: 'GET',
                success: function (data) {
                    console.log('success',data);
                    
                    $('#e_id').val(data.id);
                    $('#e_item').val(data.item);
                    $('#e_purchase_from').val(data.purchase_from);
                    $('#e_purchase_date').val(data.purchase_date);
                    $('#e_purchased_by').val(data.purchased_by);
                    $('#e_amount').val(data.amount);
                    $('#e_paid_by').val(data.paid_by);
                    $('#e_status').val(data.status);
                    $('#e_remarks').val(data.remarks);
                },
                error: function () {
                    alert('Unable to fetch expense data.');
                }
            });
        });

        $(document).on('click','.delete-expense',function()
        {
            let expenseId = $(this).data('id');          
            $('#d_id').val(expenseId);
        });
    </script>
    @endsection

@endsection
