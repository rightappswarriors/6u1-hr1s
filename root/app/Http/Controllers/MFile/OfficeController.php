<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Employee;
use Office;

class OfficeController extends Controller
{
	public function __construct()
    {
        $SQLOffice = "SELECT oid,* FROM rssys.m08 WHERE active = TRUE ORDER BY cc_desc ASC";
        $this->office = DB::select($SQLOffice);;
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.office', ['office' => $this->office]);
    }
    public function add(Request $r)
    {
    	$data = ['cc_code'=>$r->txt_code , 'cc_desc' => $r->txt_name];
    	try {

    		DB::table(Office::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Office.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['cc_desc' => $r->txt_name];
        if ($this->check($r->txt_id, $r->txt_code)) {
            Core::Set_Alert('danger', "Office Code Already Exists.");
            return back();
        } else {
            try {

                DB::table(Office::$tbl_name)->where(Office::$pk, $r->txt_code)->update($data);
                Core::Set_Alert('success', 'Successfully modified a Office.');
                return back();

            } catch (\Illuminate\Database\QueryException $e) {
                Core::Set_Alert('danger', $e->getMessage());
                return back();
            }
        }
    }
    public function delete(Request $r)
    {
    	$data = ['active' => FALSE];
        try {

            DB::table(Office::$tbl_name)->where(Office::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully remove a Office.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function check($oid, $cc_code)
    {
        if (DB::table(Office::$tbl_name)->where(Office::$oid, '!=', $oid)->where(Office::$pk, '=', $cc_code)->first()) {
            return true;
        }
        return false;
    }
    public function getEmployees(Request $r)
    {
        $employees = DB::table('hr_employee')->where('department', '=', $r->ofc_id)->get();
        if (count($employees) > 0) {
            for ($i=0; $i < count($employees); $i++) { 
                $emp = $employees[$i];
                $emp->empname = Employee::name($emp->empid);
            }
        }
        return json_encode($employees);
    }
}
