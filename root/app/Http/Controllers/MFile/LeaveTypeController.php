<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use LeaveType;
use DB;

class LeaveTypeController extends Controller
{
	public function __construct()
    {
        $SQLOtherEarnings = "SELECT * from hris.hr_leave_type WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->otherearnings = DB::select($SQLOtherEarnings);
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.leavetypes', ['otherearnings' => $this->otherearnings]);
    }
    public function add(Request $r)
    {
        // return dd($r->all());
    	$data = ['code'=>strtoupper($r->txt_code) , 'description' => strtoupper($r->txt_name), 'leave_limit' => $r->txt_limit, 'carry_over' => $r->txt_carry_over, 'incremental' => $r->increment];
    	try {

    		DB::table(LeaveType::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Leave Types.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['description' => strtoupper($r->txt_name), 'leave_limit' => $r->txt_limit, 'carry_over' => $r->txt_carry_over, 'incremental' => $r->increment];
    	try {

    		DB::table(LeaveType::$tbl_name)->where(LeaveType::$pk, strtoupper($r->txt_code))->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Leave Types.');
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

            DB::table(LeaveType::$tbl_name)->where(LeaveType::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified an Leave Types.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
