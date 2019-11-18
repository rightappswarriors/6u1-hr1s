<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use EmployeeStatus;
use DB;

class EmployeeStatusController extends Controller
{
	public function __construct()
    {
        $SQLDepart = "SELECT * FROM hris.hr_emp_status WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->depart = DB::select($SQLDepart);;
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.employeestatus', ['dept' => $this->depart]);
    }
    public function add(Request $r)
    {
    	$data = ['statcode'=>strtoupper($r->txt_code) , 'description' => strtoupper($r->txt_name)];
    	try {

    		DB::table(EmployeeStatus::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Employee Status.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['description' => strtoupper($r->txt_name)];
    	try {

    		DB::table(EmployeeStatus::$tbl_name)->where(EmployeeStatus::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Employee Status.');
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

            DB::table(EmployeeStatus::$tbl_name)->where(EmployeeStatus::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully remove a Employee Status.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
