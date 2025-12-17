<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Log;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $incomes = Income::all();
        return view('form.income', compact('incomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        // dd($request->all()  );
         // Validate the request
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'payroll_start_date' => 'required|date',
            'payroll_end_date' => 'required|date|after_or_equal:payroll_start_date',
            'account' => 'required|string|max:255',
        ]);

        try {
            // Create a new income record safely using mass assignment
            Income::create($validated);

            return redirect()->back()->with('success', 'Income record added successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Income Store Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to add income record. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $income = Income::findOrFail($id);

         return response()->json([
            'id' => $income->id,
            'client_name' => $income->client_name,
            'amount' => $income->amount,
            'status' => $income->status,
            'payroll_start_date' => $income->payroll_start_date,
            'payroll_end_date' => $income->payroll_end_date,
            'account' => $income->account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        // Validate the request
        $validated = $request->validate([
            'id' => 'required|exists:incomes,id',
            'client_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
            'payroll_start_date' => 'required|date',
            'payroll_end_date' => 'required|date|after_or_equal:payroll_start_date',
            'account' => 'required|string|max:255',
        ]);

        try {
            $income = Income::findOrFail($validated['id']);

            // Update the income record safely
            $income->update($validated);

            return redirect()->back()->with('success', 'Income record updated successfully.');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Income Update Error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to update income record. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
