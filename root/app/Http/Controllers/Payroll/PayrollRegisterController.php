<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use PayrollRegister;

class PayrollRegisterController extends Controller
{
	public function __construct()
	{
		$SQLPayrollRegister = "SELECT r.payrollreg_code, r.payroll_period_code, r.dept_frm, r.dept_until, r.employee, r.report_type, r.payroll_period_desc, e.lastname || ',' || e.firstname  AS empname FROM hris.hr_payroll_register r LEFT JOIN hris.hr_employee e ON r.employee = e.empid  ORDER BY payrollreg_code";
		$this->payroll_register = DB::select($SQLPayrollRegister);
		if(count($this->payroll_register) > 0){
			for($i = 0; $i < count($this->payroll_register); $i++)
			{
				$deptFrom_desc = DB::table('hr_department')->select('dept_name')->where('deptid', '=', $this->payroll_register[$i]->dept_frm)->first();
				$this->payroll_register[$i]->dept_frm_name = (isset($deptFrom_desc)) ? $deptFrom_desc->dept_name : '';

				$deptTo_desc = DB::table('hr_department')->select('dept_name')->where('deptid', '=', $this->payroll_register[$i]->dept_until)->first();
				$this->payroll_register[$i]->dept_To_name = (isset($deptTo_desc)) ? $deptTo_desc->dept_name : '';
			}
		}

		$SQLPayrollPeriod = "SELECT pay_code, concat(to_char(date_from, 'Mon dd, YYYY'),' To ',to_char(date_to, 'Mon dd, YYYY')) as period FROM hris.hr_payrollpariod WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->payroll_period = DB::select($SQLPayrollPeriod);

		$SQLDept = "SELECT * FROM hris.hr_department WHERE COALESCE(cancel,cancel,'')<>'Y'";
		$this->dept = DB::select($SQLDept);

		$SQLEmp = "SELECT empid,concat(firstname,' ',lastname) AS name FROM hris.hr_employee";
		$this->employee = DB::select($SQLEmp);
	}

	public function view()
	{
		// return dd($this->payroll_register);
		return view('pages.payroll.payroll_register', ['payroll_register' => $this->payroll_register, 'payroll_period' => $this->payroll_period, 'dept' => $this->dept, 'employee' => $this->employee]);
	}

	public function add(Request $r)
	{
		$txt_code = Core::getm99One('payrollreg_code');
		// return dd($txt_code->payrollreg_code);
		$data = [
					'payrollreg_code' => $txt_code->payrollreg_code,
					'dept_frm' => $r->txt_dept_frm,
					'dept_until' => $r->txt_dept_until,
					'employee' => $r->txt_spec_emp,
					'report_type' => $r->txt_rep_typ,
					'payroll_period_code' => $r->txt_pay_per,
					'payroll_period_desc' => $r->txt_pay_per_desc
				];
		//
		try {

    		DB::table(PayrollRegister::$tbl_name)->insert($data);
    		Core::updatem99('payrollreg_code', Core::get_nextincrementlimitchar($txt_code->payrollreg_code, 8));
    		Core::Set_Alert('success', 'Successfully added new Payroll Register.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
	}

	public static function getOne(Request $r)
    {
        try {
            return response()->json(['status'=>'OK','data'=>DB::table(PayrollRegister::$tbl_name)->where(PayrollRegister::$pk, '=', $r->id)->first()]);
        } catch (\Illuminate\Database\QueryException $e) {
            // return $e->getMessage();
            return response()->json(['status'=>'ERROR','data'=>$e->getMessage()]);
        }
    }
    public static function update(Request $r)
    {
    	$data = [
					'dept_frm' => $r->txt_dept_frm,
					'dept_until' => $r->txt_dept_until,
					'employee' => $r->txt_spec_emp,
					'report_type' => $r->txt_rep_typ,
					'payroll_period_code' => $r->txt_pay_per,
					'payroll_period_desc' => $r->txt_pay_per_desc
				];
    	try {

    		DB::table(PayrollRegister::$tbl_name)->where(PayrollRegister::$pk, $r->txt_code)->update($data);
    		Core::Set_Alert('success', 'Successfully modified a Payroll Register.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
    public static function delete (Request $r)
    {
    	try {

    		DB::table(PayrollRegister::$tbl_name)->where(PayrollRegister::$pk, $r->txt_code)->delete();
    		Core::Set_Alert('success', 'Successfully deleted a Payroll Register.');
    		return back();

    	} catch (\Illuminate\Database\QueryException $e) {
    		Core::Set_Alert('danger', $e->getMessage());
    		return back();
    	}
    }
}