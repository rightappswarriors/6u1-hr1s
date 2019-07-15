<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Core;
use DB;
use Department;
use DepartmentSection;

class DepartmentSectionController extends Controller
{
    public function __construct()
    {
        $SQLDepart = "SELECT * FROM hris.hr_department WHERE COALESCE(cancel,cancel,'')<>'Y'";
        $this->depart = DB::select($SQLDepart);
        $SQLDepartSection = "SELECT * FROM hris.hr_depsection LEFT JOIN hr_department ON hr_department.deptid = hr_depsection.deptid WHERE COALESCE(hr_depsection.cancel,hr_depsection.cancel,'')<>'Y'";
        $this->depart_section =  DB::select($SQLDepartSection);;
    }
    public function view()
    {
    	// return dd($this->depart_section);
    	return view('pages.mfile.department_section', ['dept' => $this->depart, 'dept_section' => $this->depart_section]);
    }
    public function add(Request $r) 
    {
    	$data = ['secid'=>$r->txt_code , 'section_name' => $r->txt_name, 'deptid' => $r->txt_dept];
    	try {

    		DB::table(DepartmentSection::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Department Section.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function update(Request $r)
    {
    	$data = ['section_name' => $r->txt_name, 'deptid' => $r->txt_dept];
    	try {

    		DB::table(DepartmentSection::$tbl_name)->where(DepartmentSection::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Department Section.');
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

            DB::table(DepartmentSection::$tbl_name)->where(DepartmentSection::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a Department Section.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            return back();
        }
    }
    public function getOne(Request $r)
    {
        try {
            return response()->json(['status'=>'OK','data'=>DB::table(DepartmentSection::$tbl_name)->where(Department::$pk, '=', $r->id)->get()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
    }
}
