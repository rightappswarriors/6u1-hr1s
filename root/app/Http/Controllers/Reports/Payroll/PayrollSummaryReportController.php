<?php

namespace App\Http\Controllers\Reports\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use ErrorCode;
use Employee;
use Export2_1;
use Exports\ExportBlade;
use JobTitle;
use OtherDeductions;
use Office;
use Payroll;

use Maatwebsite\Excel\Facades\Excel;

class PayrollSummaryReportController extends Controller
{
	public function __construct()
    {
    	/*
    	| Values that are called within this controller
    	*/
    	$this->ghistory = DB::table('hr_emp_payroll2')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'DESC')->get();
        $this->table = Core::InformationSchema_Columns('hr_emp_payroll2'); 
        $this->office = Office::get_all();
    }

	public function view()
	{
		$data = [$this->office];
		
		
		return view('pages.reports.payroll.payroll_summary_report', compact('data'));
	}

	public function getDates(Request $r)
	{
		/**
		* @param $r->ofc
		*/
		// return dd($r->all());
		# Rework this fucntion
		# use this sql "SELECT DISTINCT CONCAT(date_from, ' to ', date_to) pp, date_from, date_to FROM hris.hr_emp_payroll3 pr INNER JOIN (SELECT empid, department FROM hris.hr_employee) emp ON pr.empid = emp.empid WHERE emp.department = '97'"
		try {
			$ofc = $r->ofc;
			return Core::sql("SELECT DISTINCT CONCAT(date_from, ' to ', date_to) pp, date_from, date_to FROM hris.hr_emp_payroll3 pr INNER JOIN (SELECT empid, department FROM hris.hr_employee) emp ON pr.empid = emp.empid WHERE emp.department = '$ofc' ORDER BY date_from ASC");
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00001', $e->getMessage());
			return "error";
		}
	}

	public function getRecords(Request $r)
	{
		/**
		* @param $r->pp
		* @param $r->gen_type
		*/
		try {
			$record = [];
			$emp = Employee::$emp_sql;
			if ($r->pp != null || $r->pp != "") {
				list($date_from, $date_to) = explode("|", $r->pp);
				$gen_type = $r->gen_type;
				$record = Core::sql("SELECT CONCAT(emp.lastname, ',', emp.firstname) empname, log.date_generated, log.time_generated, pr.*, emp.*, dtr.*, hp.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN ($emp) emp ON log.empid = emp.empid LEFT JOIN (SELECT hp_id, hp_type, CAST(cc_id AS integer) AS ofc_id, withpay as hp_withpay, hp_pct, hp_amount FROM hris.hr_hazardpay) hp ON emp.department = hp.ofc_id WHERE dtr.generationtype = '$gen_type' AND log.date_from = '$date_from' AND log.date_to = '$date_to'");
			}
			return $record;
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00002', $e->getMessage());
			return "error";
		}
	}

	public function export(Request $r)
	{
		/**
		* @param $r->ofc
		* @param $r->gen_type
		* @param $r->pp
		*/
		try {
			# Payroll Info
			$pi = (object)[];
			$pi->title = "General Payroll";
			$pi->ofc = Office::GetOffice($r->ofc);
			list($date_from, $date_to) = explode("|", $r->pp);
			$pi->payroll_period = $date_to." to ".$date_to;

			# Payoll Record
			$record = $this->getRecords($r);

			$data = [
				'inf' => $pi,
				'record' => $record,
			];

			return Excel::download(new ExportBlade('print.reports.payroll.export_payroll_summary_report', $data), 'general-payroll-'.date('YmdHis').'.xlsx');
			// Export2_1::exportBlade('print.reports.payroll.export_payroll_summary_report', $data);
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00003', $e->getMessage());
			return "error";
		}
	}

	public function RetrieveSummaryReport($empid, $ppID, $dateFrom, $dateTo)
	{
		return DB::table('hr_emp_payroll2')->where('empid', '=', $empid)->where('pp_id', '=',$ppID)->where('date_from', '=',$dateFrom)->where('date_to', '=',$dateTo)->first();
	}

	public function print(Request $r)
	{
		/**
		* @param $r->pcode
		*/
		/*$p = DB::table('hr_emp_payroll2')->where('item_no', '=', $r->item_no)->first();
		$emp = Employee::GetEmployee($p->empid);
		$jt = JobTitle::Get_JobTitle($emp->positions);*/
		$pcode = $r->pcode;
		$sql = "SELECT emp.empname, emp.department, emp.biometric, CONCAT(pr.date_from, ' to ', pr.date_to) payroll_period, pr.* FROM hris.hr_emp_payroll3 pr LEFT JOIN (SELECT emp.*, ofc.cc_desc AS department FROM (SELECT empid, CONCAT(lastname, ', ', firstname) empname, biometric, CAST(department AS integer) cc_id FROM hris.hr_employee) emp LEFT JOIN rssys.m08 ofc ON emp.cc_id = ofc.cc_id) emp ON pr.empid = emp.empid";
		$con = " WHERE pr.emp_pay_code = '$pcode'";
		$record = Core::sql($sql.$con);
		if (count($record) > 0) {
			$record = $record[0];
		} else {
			$record = null;
		}
		// return $record;
		return view('print.reports.payroll.print_payroll_summary_report3', compact('record'));
	}

	public function print_ot(Request $r)
	{
		$record = "ok";
		return view('print.reports.payroll.print_payroll_summary_report_ot', compact('record'));
	}

}