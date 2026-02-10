
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
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Expenses</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Expenses</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_expense"><i class="fa fa-plus"></i> Add Expense</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->
            {{-- message --}}
            {!! Toastr::message() !!}

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item</th>
                                    <th>Purchase From</th>
                                    <th>Purchase Date</th>
                                    <th>Purchased By</th>
                                    <th>Amount</th>
                                    <th>Paid By</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $key => $item)
                                    <tr class="expense_data">
                                        <td hidden class="id">{{ $item->id }}</td>
                                        <td>{{ ++$key }}</td>
                                        <td class="item">{{ $item->item }}</td>
                                        <td class="purchase_from">{{ $item->purchase_from }}</td>
                                        <td class="purchase_date">{{ $item->purchase_date }}</td>
                                        <td class="purchased_by">{{ $item->purchaser ? $item->purchaser->name : 'N/A' }}</td>
                                        <td class="amount">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        <td class="paid_by">{{ $item->paid_by }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                    @if($item->status == 'Approved')
                                                        <i class="fa fa-dot-circle-o text-success"></i> Approved
                                                    @elseif($item->status == 'Pending')
                                                        <i class="fa fa-dot-circle-o text-info"></i> Pending
                                                    @else
                                                        <i class="fa fa-dot-circle-o text-danger"></i> Rejected
                                                    @endif
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit-expense" href="javascript:void(0)" data-toggle="modal" data-id="{{ $item->id }}" data-target="#edit_expense"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-expense" href="javascript:void(0)" data-toggle="modal" data-id="{{ $item->id }}" data-target="#delete_expense"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('expense/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Item Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="item" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase From <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="purchase_from" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="purchase_date" required>
                            </div>

                            <div class="form-group">
                                <label>Purchased By <span class="text-danger">*</span></label>
                                <select class="form-control" name="purchased_by" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" step="0.01" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label>Paid By <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="paid_by" required>
                            </div>

                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3"></textarea>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('expense/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">

                            <div class="form-group">
                                <label>Item Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="edit_item" name="item" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase From <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="edit_purchase_from" name="purchase_from" required>
                            </div>

                            <div class="form-group">
                                <label>Purchase Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="edit_purchase_date" name="purchase_date" required>
                            </div>

                            <div class="form-group">
                                <label>Purchased By <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_purchased_by" name="purchased_by" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" step="0.01" id="edit_amount" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label>Paid By <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="edit_paid_by" name="paid_by" required>
                            </div>

                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
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
                                    <form action="{{ route('expense/destroy') }}" method="POST">
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
        <!-- /Delete Expense Modal -->
       
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
                    $('#e_id').val(data.id);
                    $('#edit_item').val(data.item);
                    $('#edit_purchase_from').val(data.purchase_from);
                    $('#edit_purchase_date').val(data.purchase_date);
                    $('#edit_purchased_by').val(data.purchased_by);
                    $('#edit_amount').val(data.amount);
                    $('#edit_paid_by').val(data.paid_by);
                    $('#edit_status').val(data.status);
                    $('#edit_remarks').val(data.remarks);
                },
                error: function (xhr, status, error) {
                    alert('Unable to fetch expense data. Check the console for details.');
                }
            });
        });

        $(document).on('click', '.delete-expense', function () {
            var _this = $(this).parents('tr');            
            $('#d_id').val(_this.find('.id').text());
        });
    </script>
    @endsection

@endsection
