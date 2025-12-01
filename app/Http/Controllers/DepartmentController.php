<?php

namespace App\Http\Controllers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Department;
use DB;
class DepartmentController extends Controller
{
     public function department()
    {
        $departments = Department::all();
        return view('form.departments',compact('departments'));
    }

     public function saveRecord(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $department = new Department;
            $department->name = $request->name;
            $department->description  = $request->description;
            $department->save();
            
            DB::commit();
            Toastr::success('Create new department successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Add Department failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Add Department fail :)','Error');
            return redirect()->back();
        }
    }

      public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $id    = $request->id;
            $name  = $request->name;
            $description  = $request->description;

            $update = [
                'id'   => $id,
                'name' => $name,
                'description' => $description,
            ];

            Department::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Department updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Department update failed', [
                'department_id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Department update fail :)','Error');
            return redirect()->back();
        }
    }
      public function deleteRecord( Request $request)
    {
       DB::beginTransaction();
        try{
            Department::where('id',$request->id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Department deletion failed', [
                'department_id' => $request->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
}