<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use Department;
use DB;

class DepartmentController extends Controller
{
	public function __construct()
    {
        $SQLDepart = "SELECT * FROM hris.hr_department WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->depart = DB::select($SQLDepart);;
    }
    public function view()
    {
    	// return dd($this->depart);
    	return view('pages.mfile.department', ['dept' => $this->depart]);
    }
    public function add(Request $r)
    {
    	$data = ['deptid'=>$r->txt_code , 'dept_name' => $r->txt_name];
    	try {

    		DB::table(Department::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Department.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['dept_name' => $r->txt_name];
    	try {

    		DB::table(Department::$tbl_name)->where(Department::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Department.');
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

            DB::table(Department::$tbl_name)->where(Department::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully remove a Department.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
}
