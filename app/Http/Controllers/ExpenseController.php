<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Http\Request;
use Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::with('purchaser')->get();
        $employees = Employee::all();
        return view('form.expenses', compact('expenses', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'purchase_from' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'paid_by' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        try {
            // Create a new expense record safely using mass assignment
            Expense::create($validated);

            return redirect()->back()->with('success', 'Expense record added successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Expense Store Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to add expense record. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $expense = Expense::findOrFail($id);

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id' => 'required|exists:expenses,id',
            'item' => 'required|string|max:255',
            'purchase_from' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'purchased_by' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'paid_by' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        try {
            $expense = Expense::findOrFail($validated['id']);

            // Update the expense record safely
            $expense->update($validated);

            return redirect()->back()->with('success', 'Expense record updated successfully.');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Expense Update Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to update expense record. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
        ]);

        try {
            $expense = Expense::findOrFail($request->id);
            $expense->delete();

            return redirect()->back()->with('success', 'Expense record deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Expense Delete Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete expense record. Please try again.');
        }
    }
}
