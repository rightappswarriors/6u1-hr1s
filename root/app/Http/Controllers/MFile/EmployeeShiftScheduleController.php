<?php

namespace App\Http\Controllers\MFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use EmployeeShiftSchedule;
use DB;

class EmployeeShiftScheduleController extends Controller
{
	public function __construct()
	{
		$SQLEmployeeShiftSchedule = "SELECT hris.hr_emp_shift.esid,hris.hr_shift_schedule.code,hris.hr_employee.empid,concat(hris.hr_employee.firstname,' ',hris.hr_employee.lastname) as name, hris.hr_shift_schedule.time_in, hris.hr_shift_schedule.time_out,to_char(hris.hr_emp_shift.date_from, 'yyyy-MM-dd') AS date_from,to_char(hris.hr_emp_shift.date_to, 'yyyy-MM-dd') AS date_to FROM ((hris.hr_emp_shift INNER JOIN hris.hr_shift_schedule ON hris.hr_emp_shift.shiftcode = hris.hr_shift_schedule.code) INNER JOIN hris.hr_employee ON hris.hr_emp_shift.empid = hris.hr_employee.empid) WHERE COALESCE(hris.hr_emp_shift.cancel,hris.hr_emp_shift.cancel,'')<>'Y'";
		$this->EmployeeShiftSchedule  = DB::select($SQLEmployeeShiftSchedule);
		$SQLEmployee = "SELECT empid, concat(firstname,' ',lastname) AS name FROM hris.hr_employee WHERE fixed_sched = 'N' ORDER BY name ASC";
		$this->Employee = DB::select($SQLEmployee);
		$SQLShiftSchedule = "SELECT code, name, time_in, time_out FROM hris.hr_shift_schedule";
		$this->ShiftSchedule = DB::select($SQLShiftSchedule);
	}
    public function view()
    {
    	// return dd($this->EmployeeShiftSchedule);
    	return view('pages.mfile.employee_shift_sched', ['employee' => $this->Employee, 'shiftsched' => $this->ShiftSchedule, 'empsched' =>  $this->EmployeeShiftSchedule]);
    }
    public function add(Request $r)
    {
    	// return dd($r->all());
    	$data = ['shiftcode' => $r->txt_sft, 'empid' => $r->txt_emp, 'date_from' => $r->txt_dt_fr, 'date_to' => $r->txt_dt_to];
    	try {

    		DB::table(EmployeeShiftSchedule::$tbl_name)->insert($data);
    		Core::Set_Alert('success', 'Successfully added new Employee Shift Schedule.');
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
    	$data = ['shiftcode' => $r->txt_sft, 'empid' => $r->txt_emp, 'date_from' => $r->txt_dt_fr, 'date_to' => $r->txt_dt_to];
    	try {

    		DB::table(EmployeeShiftSchedule::$tbl_name)->where(EmployeeShiftSchedule::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified an Employee Shift Schedule.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public function delete(Request $r)
    {
    	// return dd($r->all());
    	//code'=>$r->txt_code
    	$data = ['cancel' => "Y"];
    	try {

    		DB::table(EmployeeShiftSchedule::$tbl_name)->where(EmployeeShiftSchedule::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully canceled an Employee Shift Schedule.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
}
