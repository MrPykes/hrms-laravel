<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        $users = Employee::all();
        $expenses = Expense::with('purchaser')->get();
        return view('reports.expensereport', compact('users', 'expenses'));
    }

    public function store(Request $request)
    {
        try {
            Expense::create([
                'item' => $request->item,
                'purchase_from' => $request->purchase_from,
                'purchase_date' => $request->purchase_date,
                'purchased_by' => $request->purchased_by,
                'amount' => $request->amount,
                'paid_by' => $request->paid_by,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->back()->with('success', 'Expense added successfully.');
    }
    public function edit($id)
    {
        $expense = Expense::with('purchaser')->findOrFail($id);

        return response()->json([
            'id' => $expense->id,
            'item' => $expense->item,
            'purchase_from' => $expense->purchase_from,
            'purchase_date' => $expense->purchase_date,
            'purchased_by' => $expense->purchased_by,
            'amount' => $expense->amount,
            'paid_by' => $expense->paid_by,
            'status' => $expense->status,
            'remarks' => $expense->remarks,
        ]);
    }


    public function update(Request $request)
    {
        
        try {
            $validated = $request->validate([
                    'id' => ['required', 'exists:expenses,id'],
    
                    // Expense info
                    'item' => ['required', 'string', 'max:255'],
                    'purchase_from' => ['required', 'string', 'max:255'],
                    'purchase_date' => ['required', 'date'],
    
                    // Foreign key
                    'purchased_by' => ['required', 'integer', 'exists:employees,id'],
    
                    // Financial
                    'amount' => ['required', 'numeric', 'min:0'],
    
                    // Enums
                    'paid_by' => ['required', Rule::in(['cash', 'cheque', 'bank', 'other'])],
                    'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
    
                    // Optional
                    'remarks' => ['nullable', 'string'],
                ]);
            Expense::where('id', $validated['id'])->update($validated);
        } catch (\Throwable $th) {
            report($th);
            throw $th;
        }

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required', 'exists:expenses,id'],
            ]);
            Expense::where('id', $request->id)->delete();
        } catch (\Throwable $th) {
            report($th);
            throw $th;
        }

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }
    // view page
    public function invoiceReports()
    {
        return view('reports.invoicereports');
    }

    // invoice view detail
    public function invoiceView()
    {
        return view('reports.invoiceview');
    }

    // daily report page
    public function dailyReport()
    {
        return view('reports.dailyreports');
    }

    // leave reports page
    public function leaveReport()
    {
        // $leaves = DB::table('leaves_admins')
        //             ->join('users', 'users.rec_id', '=', 'leaves_admins.rec_id')
        //             ->select('leaves_admins.*', 'users.*')
        //             ->get();
        $leaves = LeaveRequest::with('employee.department','leave_type','approver')->get();
        return view('reports.leavereports',compact('leaves'));
    }
}
