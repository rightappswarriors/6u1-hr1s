<?php

namespace App\Http\Controllers\Reports\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;

use ErrorCode;
use Employee;
use JobTitle;
use Office;
use Payroll;

class PayslipController extends Controller
{
	public function __construct()
	{
		$this->office = Office::get_all();
	}

	public function view()
	{
		$data = [$this->office];
		return view('pages.reports.payroll.payslip', compact('data'));
	}

	public function getRecord(Request $r)
	{
		// return dd($r->all());
		try {
			$return_val = (object)[];
			$log = DB::table('hr_emp_payroll_log')->where('cancel', null)->get();
			$ofc = json_decode(Office::OfficeEmployees($r->ofc)); 
			$log_tm = array();
			$return_val->psr = array();
			if (count($log) > 0) {
				for ($i=0; $i < count($log); $i++) { 
					$tmp = $log[$i];
					$tmp->name = Employee::Name($tmp->empid);
					list($yr, $month, $day) = explode('-', $tmp->date_from);
					if ($yr == $r->year) {
						for ($j=0; $j < count($ofc); $j++) { 
							$ofc_emp = $ofc[$j];
							if ($tmp->empid === $ofc_emp->empid) {
								array_push($return_val->psr, $tmp);
							}
						}
					}
				};
			}
			return json_encode($return_val);
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayslipController', '00001', $e->getMessage());
			return "error";
		}
	}

	public function print(Request $r)
	{
		/**
		* @param $r->item_no
		*/
		$log = DB::table('hr_emp_payroll_log')->where('emp_pay_code', $r->item_no)->first();
		if ($log==null) {
			return "error";
		}
		switch ($log->payroll_version) {
			case '1':
				# code...
				break;

			case '2':
				# code...
				break;

			case '3':
				$p = DB::table('hr_emp_payroll3')->where('emp_pay_code', $r->item_no)->first();
				if ($p==null) {
					return "no-record";
				}
				$emp = Employee::GetEmployee($p->empid);
				$p->daily_rate = Payroll::GetDailyRate($emp->pay_rate, $emp->rate_type);
				$jt = JobTitle::Get_JobTitle($emp->positions);
				// return view('print.reports.payroll.print_payroll_summary_report', compact(['log', 'p', 'emp', 'jt']));
				// return view('print.reports.payroll.print_payroll_summary_report2', compact(['log', 'p', 'emp', 'jt']));
				return view('print.reports.payroll.print_payroll_summary_report3', compact(['log', 'p', 'emp', 'jt']));
				break;
			
			default:
				return "error";
				break;
		}
	}
}