<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use ShiftSchedule;
use DB;

class ShiftScheduleController extends Controller
{
	public function __construct()
	{
		$SQLShiftSchedule = "SELECT * FROM hris.hr_shift_schedule WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->shiftschedule = DB::select($SQLShiftSchedule);
	}
    public function view()
    {
    	// return dd($this->shiftschedule);
    	return view('pages.mfile.shift_schedule', ['shiftschedule' => $this->shiftschedule]);
    }
    public function add(Request $r)
    {
    	// return dd($r->all());
    	$data = ['code'=>$r->txt_code , 'time_in' => $r->txt_time_in, 'time_out' => $r->txt_time_out, 'name' => $r->txt_name];
    	try {

    		DB::table(ShiftSchedule::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Shift Schedule.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	// return dd($r->all());
    	//code'=>$r->txt_code
    	$data = ['time_in' => $r->txt_time_in, 'time_out' => $r->txt_time_out, 'name' => $r->txt_name];
    	try {

    		DB::table(ShiftSchedule::$tbl_name)->where(ShiftSchedule::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Shift Schedule.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function delete(Request $r)
    {
    	$data = ['cancel' => 'Y'];
        try {

            DB::table(ShiftSchedule::$tbl_name)->where(ShiftSchedule::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a Shift Schedule.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
