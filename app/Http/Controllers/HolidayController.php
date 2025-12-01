<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use App\Models\Holiday;
use Carbon\Carbon;
use DB;

class HolidayController extends Controller
{
    // holidays
    public function holiday()
    {
        $holiday = Holiday::orderBy('date_holiday', 'asc')->get();
        $today_date = Carbon::today(); // Carbon object

        return view('form.holidays', compact('holiday', 'today_date'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'nameHoliday' => 'required|string|max:255',
            'holidayDate' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $holiday = new Holiday;
            $holiday->name_holiday = $request->nameHoliday;
            $holiday->date_holiday  = $request->holidayDate;
            $holiday->save();
            
            DB::commit();
            Toastr::success('Create new holiday successfully :)','Success');
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Add Holiday failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Add Holiday fail :)','Error');
            return redirect()->back();
        }
    }
    // update
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $id           = $request->id;
            $holidayName  = $request->holidayName;
            $holidayDate  = $request->holidayDate;

            $update = [

                'id'           => $id,
                'name_holiday' => $holidayName,
                'date_holiday' => $holidayDate,
            ];

            Holiday::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Holiday updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Log::error('Holiday update failed', [
                'holiday_id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Holiday update fail :)','Error');
            return redirect()->back();
        }
    }
}
