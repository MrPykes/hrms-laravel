
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
                        <h3 class="page-title">Income</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Income</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_income"><i class="fa fa-plus"></i> Add Income</a>
                    </div>
                </div>
            </div>
			<!-- /Page Header -->
            {{-- message --}}
            {!! Toastr::message() !!}

            @php
                use Carbon\Carbon;
                $today_date = Carbon::today()->format('d-m-Y');
            @endphp
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Client Name </th>
                                    <th>Amount </th>
                                    <th>Status </th>
                                    <th>Month </th>
                                    <th>Start Date </th>
                                    <th>End Date</th>
                                    <th>Account </th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incomes as $key=>$items )
                                        <tr class="income_data">
                                            <td hidden class="id">{{ $items->id }}</td>
                                            <td>{{ ++$key }}</td>
                                            <td class="name">{{ $items->client_name }}</td>
                                            <td class="amount">{{ $items->amount }}</td>
                                            <td class="_status">{{ $items->status }}</td>
                                            <td class="month">{{ $items->month }}</td>
                                            <td class="start_date">{{ $items->payroll_start_date }}</td>
                                            <td class="end_date">{{ $items->payroll_end_date }}</td>
                                            <td class="account">{{ $items->account }}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item edit-income" href="javascript:void(0)" data-toggle="modal" data-id="{{ $items->id }}" data-target="#edit_income"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item delete-income" href="javascript:void(0)" data-toggle="modal" data-id="{{ $items->id }}" data-target="#delete_income"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <!-- Add Income Modal -->
        <div class="modal custom-modal fade" id="add_income" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Income</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       <form action="{{ route('income/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Client Name <span class="text-danger">*</span></label>
                                <select class="form-control" name="client_name" required>
                                    <option value="Ben">Ben</option>
                                    <option value="Kelly">Kelly</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" step="0.01" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Payroll Start Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="payroll_start_date" required>
                            </div>

                            <div class="form-group">
                                <label>Payroll End Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="payroll_end_date" required>
                            </div>

                            <div class="form-group">
                                <label>Account <span class="text-danger">*</span></label>
                                 <select class="form-control" name="account" required>
                                    <option value="Paul">Paul</option>
                                    <option value="Ed">Ed</option>
                                </select>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Income Modal -->

        <!-- Edit Income Modal -->
        <div class="modal custom-modal fade" id="edit_income" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Income</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('income/update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="e_id" value="">

                            <div class="form-group">
                                <label>Client Name <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_client_name" name="client_name" required>
                                    <option value="Ben">Ben</option>
                                    <option value="Kelly">Kelly</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" value="">
                            </div>

                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_status" name="status" value="">
                            </div>

                            <div class="form-group">
                                <label>Payroll Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_payroll_start_date" name="payroll_start_date" value="">
                            </div>

                            <div class="form-group">
                                <label>Payroll End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_payroll_end_date" name="payroll_end_date" value="">
                            </div>

                            <div class="form-group">
                                <label>Account <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_account" name="account" required>
                                    <option value="Paul">Paul</option>
                                    <option value="Ed">Ed</option>
                                </select>
                            </div>

                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Income Modal -->

        <!-- Delete Income Modal -->
        <div class="modal custom-modal fade" id="delete_income" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Income</h3>
                            <p>Are you sure want to delete?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <div class="row">
                                <div class="col-6">
                                    <!-- <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a> -->
                                    <form action="{{ route('income/destroy') }}" method="POST">
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
       $(document).on('click', '.edit-income', function () {
            let incomeId = $(this).data('id');
            console.log('id',incomeId);
            
            $.ajax({
                url: `/income/edit/${incomeId}`, // Make sure this route returns JSON of the income
                type: 'GET',
                success: function (data) {
                    console.log('success', data);

                    $('#e_id').val(data.id);
                    $('#edit_client_name').val(data.client_name);
                    $('#edit_amount').val(data.amount);
                    $('#edit_status').val(data.status);
                    $('#edit_payroll_start_date').val(data.payroll_start_date);
                    $('#edit_payroll_end_date').val(data.payroll_end_date);
                    $('#edit_account').val(data.account);
                },
                error: function (xhr, status, error) {
                    alert('Unable to fetch income data. Check the console for details.');
                }
            });
        });

        $(document).on('click','.delete_income',function()
        {
            var _this = $(this).parents('tr');            
            $('#d_id').val(_this.find('.id').text());
        });
    </script>
    @endsection

@endsection
