<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;
use DB;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = Position::with(['department'])->get();
        $departments = Department::all();
        return view('form.designation',compact('designations', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'department' => 'required|numeric',
        ]);
        
        DB::beginTransaction();
        try {
            $position = new Position;
            $position->name = $request->name;
            $position->description  = $request->description;
            $position->department_id  = $request->department;
            $position->save();
            
            DB::commit();
            Toastr::success('Create new department successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Add Designation failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Add Designation fail :)','Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try{
            $id    = $request->id;
            $name  = $request->name;
            $description  = $request->description;
            $department_id  = $request->department;

            $update = [
                'id'   => $id,
                'name' => $name,
                'description' => $description,
                'department_id' => $department_id,
            ];

            Position::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Department updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Designation update failed', [
                'designation_id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Designation update fail :)','Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try{
            Position::where('id',$request->id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Designation deletion failed', [
                'designation_id' => $request->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
}
