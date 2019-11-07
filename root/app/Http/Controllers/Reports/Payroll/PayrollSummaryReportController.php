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
			return Core::sql("SELECT DISTINCT CONCAT(date_from, ' to ', date_to) pp, date_from, date_to FROM hris.hr_emp_payroll3 pr INNER JOIN (SELECT empid, department FROM hris.hr_employee) emp ON pr.empid = emp.empid WHERE emp.department = '$ofc' ORDER BY date_from DESC");
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
				$record = Core::sql("SELECT CONCAT(emp.lastname, ',', emp.firstname) empname, log.date_generated, log.time_generated, pr.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN ($emp) emp ON log.empid = emp.empid WHERE dtr.generationtype = '$gen_type' AND log.date_from = '$date_from' AND log.date_to = '$date_to'");
			}
			return $record;
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00002', $e->getMessage());
			return "error";
		}
	}

	public function export(Request $r)
	{
		try {
			$pp = Payroll::PayrollPeriod2($r->month, $r->pp, $r->year);
			$ofc_emp = json_decode(Office::OfficeEmployees($r->ofc));
			$rsr = [];
			$pd = [];
			if (count($ofc_emp) > 0) {
				for ($i=0; $i < count($ofc_emp); $i++) { 
					$rsr_i = $this->RetrieveSummaryReport($ofc_emp[$i]->empid,$pp->id, $pp->from, $pp->to);
					$pd_content = OtherDeductions::Get_Records($ofc_emp[$i]->empid, $pp->from, $pp->to);
					if ($rsr_i!=null) {
						array_push($rsr, $rsr_i);
					}
					if (count($pd) > 0) {
						array_push($pd, $pd_content);
					}
				}
			} else {
				return "no employee";
			}
			// if (count($rsr) <= 0) {
			// 	return "no record";
			// }
			$data = (object)[];
			$data->pp = $pp;
			$data->ofc = (Office::GetOffice($r->ofc)!=null) ? strtoupper(Office::GetOffice($r->ofc)->cc_desc) : "office-not-found";
			$data->rsr = $rsr;
			$data->pd = $pd;
			// dd($data);
			// return Excel::download(new ExportBlade('print.reports.payroll.export_payroll_summary_report', $data), 'general-payroll-'.date('YmdHis').'.xlsx');
			Export2_1::exportBlade('print.reports.payroll.export_payroll_summary_report', $data);
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
		$pcode = $r->pcode;
		$record = [];
		$emp = Employee::$emp_sql;
		$gen_type = 'OVERTIME';
		// $record = Core::sql("SELECT CONCAT(emp.lastname, ',', emp.firstname) empname, log.date_generated, log.time_generated, pr.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN ($emp) emp ON log.empid = emp.empid WHERE dtr.generationtype = '$gen_type' AND pr.emp_pay_code = '$pcode'");
		
		$record = DB::select("SELECT emp.empname, emp.department, emp.biometric, emp.firstname, emp.lastname, emp.mi, emp.empname, emp.tin, emp.pay_rate, emp.cc_desc, emp.department,  CONCAT(dtr.date_from, ' to ', dtr.date_to) payroll_period, dtr.*, pr.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id WHERE generationtype = 'OVERTIME') dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN ($emp) emp ON dtr.empid = emp.empid WHERE pr.emp_pay_code = '$pcode' ");

		$days_worked_arr = json_decode($record[0]->total_overtime_arr);
		$ot_timelogs = [];
		for ($i=0; $i < count($days_worked_arr); $i++) { 
			list($date, $timelog, $rendered) = $days_worked_arr[$i]; # [date, timelog[0], rendered]
			# May 2, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm = 4 hours
			$date = date('M d, Y', strtotime($date));
			$timelog1 = "";
			$timelog2 = "";
			$rendered = Core::ToHours($rendered);
			if (count($timelog) > 2) {
				$timelog1 = date('ha', strtotime($timelog[0]))."-".date('ha', strtotime($timelog[1]));
				$timelog2 = date('ha', strtotime($timelog[2]))."-".date('ha', strtotime($timelog[3]));
			} else {
				$timelog1 = date('ha', strtotime($timelog[0]))."-".date('ha', strtotime($timelog[1]));
			}
			$tmp = [
				'date' => $date,
				'timelog1' => $timelog1,
				'timelog2' => $timelog2,
				'rendered' => $rendered
			];
			array_push($ot_timelogs, $tmp);
		}

		
		return view('print.reports.payroll.print_payroll_summary_report_ot', compact('record', 'ot_timelogs'));
	}

}