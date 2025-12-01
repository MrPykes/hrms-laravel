<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\module_permission;

class EmployeeController extends Controller
{
    // all employee card view
    public function cardAllEmployee(Request $request)
    {
        // $employees = DB::table('employees')
        //             ->join('positions', 'positions.id', '=', 'employees.position_id')
        //             ->select('employees.*', 'positions.name as position_name')
        //             ->get();
        // $userList = DB::table('users')->get();
        // $permission_lists = DB::table('permission_lists')->get();
        $positions = Position::all();
        $name = $request->name;
        $position_id = $request->position;
        $employees = Employee::with(['department', 'position'])
                ->where(function ($query) use ($request) {

                    if ($request->filled('name')) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }

                    if ($request->filled('position')) {
                        $query->where('position_id', $request->position);
                    }
                })
                ->get();
        return view('form.allemployeecard',compact('employees','positions','name','position_id'));
    }
    // all employee list
    public function listAllEmployee(Request $request)
    {
        // dd($request->all());
        // $employees = DB::table('employees')
        //             ->join('positions', 'positions.id', '=', 'employees.position_id')
        //             ->select('employees.*', 'positions.name as position_name')
        //             ->get();
        // $userList = DB::table('users')->get();
        // $permission_lists = DB::table('permission_lists')->get();
        // return view('form.employeelist',compact('employees','userList','permission_lists'));
        $positions = Position::all();
        $name = $request->name;
        $position_id = $request->position;
        $employees = Employee::with(['department', 'position'])
                ->where(function ($query) use ($request) {

                    if ($request->filled('name')) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }

                    if ($request->filled('position')) {
                        $query->where('position_id', $request->position);
                    }
                })
                ->get();
        return view('form.employeelist',compact('employees','positions','name','position_id'));
    }

    // save data employee
    public function saveRecord(Request $request)
    {
        DB::beginTransaction();
        try{
            $employees = Employee::where('email', '=',$request->email)->first();
            if ($employees === null)
                {
                $employee = new Employee;
                $employee->name         = $request->name;
                $employee->email        = $request->email;
                $employee->birth_date   = $request->birthDate;
                $employee->gender       = $request->gender;
                $employee->department_id = $request->department;
                $employee->position_id   = $request->position;
                $employee->save();

                DB::commit();
                Toastr::success('Add new employee successfully :)','Success');
                return redirect()->route('all/employee/card');
            } else {
                DB::rollback();
                Log::error('Add new employee failed - email already exists', [
                    'data' => $request->all(),
                ]);
                Toastr::error('Add new employee exits :)','Error');
                return redirect()->back();
            }
         
        }catch(\Exception $e){
            DB::rollback();
            Log::error('Add new employee failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Add new employee fail :)','Error');
            return redirect()->back();
        }
    }
    // view edit record
    public function viewRecord($id)
    {
        $departments = Department::all();
        $positions = Position::all();
        $employees = Employee::with(['department', 'position'])->where('id', $id)->first();
        return view('form.edit.editemployee',compact('employees','departments','positions'));   
    }
    // update record employee
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $employee = Employee::find($request->id);
            $employee->name         = $request->name;
            $employee->email        = $request->email;
            $employee->birth_date   = $request->birthDate;
            $employee->gender       = $request->gender;
            $employee->department_id = $request->department;
            $employee->position_id   = $request->position;
            $employee->save();
            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->back();
        
        }catch(\Exception $e){
            DB::rollback();
            Log::error('Update employee record failed', [
                'employee_id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('updated record fail :)','Error');
            return redirect()->back();
        }
    }
    // delete record
    public function deleteRecord($id)
    {
        DB::beginTransaction();
        try{

            Employee::where('id',$id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Employee deletion failed', [
                'employee_id' => $id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
    // employee search
    public function employeeSearch(Request $request)
    {

        $departments = Department::all();
        $positions = Position::all();
        $employees = Employee::with(['department', 'position'])->where('id', $id)->first();
        return view('form.edit.editemployee',compact('employees','departments','positions'));

        $permission_lists = DB::table('permission_lists')->get();
        $employees = Employee::with(['department', 'position'])
                ->where(function ($query) use ($request) {

                    if ($request->filled('name')) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }

                    if ($request->filled('position')) {
                        $query->where('position_id', $request->position);
                    }
                })
                ->get();
        return view('form.allemployeecard',compact('employees','permission_lists'));
    }
    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                    ->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.rec_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        return view('form.employeelist',compact('users','userList','permission_lists'));
    }

    // employee profile
    public function profileEmployee($rec_id)
    {
        $users = DB::table('profile_information')
                ->join('users', 'users.rec_id', '=', 'profile_information.rec_id')
                ->select('profile_information.*', 'users.*')
                ->where('profile_information.rec_id','=',$rec_id)
                ->first();
        $user = DB::table('users')->where('rec_id',$rec_id)->get();
        return view('form.employeeprofile',compact('user','users'));
    }
}
