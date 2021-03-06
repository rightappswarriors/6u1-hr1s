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
				$record = Core::sql("SELECT CONCAT(emp.lastname, ',', emp.firstname) empname, log.date_generated, log.time_generated, pr.*, emp.*, dtr.*, hp.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN (SELECT distinct a.empid, a.department, a.lastname, a.firstname, jtitle_name as position FROM (SELECT empid, jtitle_name, firstname, lastname, mi, CONCAT(lastname, ', ',firstname) AS empname, section, CAST(positions AS INTEGER) positions, picture, CAST(department AS INTEGER) department, date_hired, contractual_date, prohibition_date, date_regular, date_resigned, date_terminated, CAST(empstatus AS INTEGER) empstatus, contract_days, prc, ctc, rate_type, pay_rate, biometric, sss, pagibig, philhealth, payroll_account, tin, tax_bracket, dayoff1, dayoff2, sex, birth, civil_status, religion, height, weight, father, father_address, father_contact, father_job, mother, mother_address, mother_contact, mother_job, emp_contact, home_tel, email, home_address, emergency_name, emergency_contact, em_home_address, relationship, shift_sched_from, shift_sched_sat_from, shift_sched_to, shift_sched_sat_to, fixed_rate, graduate, primary_ed, tertiary_ed, secondary_ed, post_graduate, pagibig_bracket, philhealth_bracket, shift_sched, shift_sched_sat, sss_bracket, fixed_sched, accountnumber, COALESCE(b.jtitle_name, 'no-jobtitle-assigned') jobtitle, COALESCE(c.description, 'no-employee-status') empstatus_desc FROM (SELECT * FROM hris.hr_employee WHERE cancel IS NULL ORDER BY lastname ASC) a LEFT JOIN (SELECT * FROM hris.hr_jobtitle WHERE cancel IS NULL ) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id) a LEFT JOIN (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08 WHERE active IS TRUE) b ON a.department = b.cc_id) emp ON log.empid = emp.empid LEFT JOIN (SELECT hp_id, hp_type, CAST(cc_id AS integer) AS ofc_id, withpay as hp_withpay, hp_pct, hp_amount FROM hris.hr_hazardpay) hp ON emp.department = hp.ofc_id WHERE dtr.generationtype = '$gen_type' AND log.date_from = '$date_from' AND log.date_to = '$date_to'");
				// $record = Core::sql("SELECT CONCAT(emp.lastname, ',', emp.firstname) empname, log.date_generated, log.time_generated, pr.*, emp.*, dtr.*, hp.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN (SELECT distinct a.empid, a.department, a.lastname, a.firstname FROM (SELECT empid, firstname, lastname, mi, CONCAT(lastname, ', ',firstname) AS empname, section, CAST(positions AS INTEGER) positions, picture, CAST(department AS INTEGER) department, date_hired, contractual_date, prohibition_date, date_regular, date_resigned, date_terminated, CAST(empstatus AS INTEGER) empstatus, contract_days, prc, ctc, rate_type, pay_rate, biometric, sss, pagibig, philhealth, payroll_account, tin, tax_bracket, dayoff1, dayoff2, sex, birth, civil_status, religion, height, weight, father, father_address, father_contact, father_job, mother, mother_address, mother_contact, mother_job, emp_contact, home_tel, email, home_address, emergency_name, emergency_contact, em_home_address, relationship, shift_sched_from, shift_sched_sat_from, shift_sched_to, shift_sched_sat_to, fixed_rate, graduate, primary_ed, tertiary_ed, secondary_ed, post_graduate, pagibig_bracket, philhealth_bracket, shift_sched, shift_sched_sat, sss_bracket, fixed_sched, accountnumber, COALESCE(b.jtitle_name, 'no-jobtitle-assigned') jobtitle, COALESCE(c.description, 'no-employee-status') empstatus_desc FROM (SELECT * FROM hris.hr_employee WHERE cancel IS NULL ORDER BY lastname ASC) a LEFT JOIN (SELECT * FROM hris.hr_jobtitle WHERE cancel IS NULL ) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id) a LEFT JOIN (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08 WHERE active IS TRUE) b ON a.department = b.cc_id) emp ON log.empid = emp.empid LEFT JOIN (SELECT hp_id, hp_type, CAST(cc_id AS integer) AS ofc_id, withpay as hp_withpay, hp_pct, hp_amount FROM hris.hr_hazardpay) hp ON emp.department = hp.ofc_id WHERE dtr.generationtype = '$gen_type' AND log.date_from = '$date_from' AND log.date_to = '$date_to'");
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
		// $data = DB::table('hr_pagibig_sub')->where('cancel',null)->get();
		// $toRet = [];
		// foreach($data as $d){
		// 	array_push($toRet, [$d->id, $d->description, rand(10,100)]);
		// }
		// dd(json_encode($toRet));
		try {
			# Payroll Info
			$pi = (object)[];
			$pi->title = "General Payroll";
			$pi->ofc = Office::GetOffice($r->ofc);
			list($date_from, $date_to) = explode("|", $r->pp);
			$pi->payroll_period = Date('Y-m-16',strtotime($date_from))." to ".Date('Y-m-t',strtotime($date_to));
			$pi->date_to = $date_to;
			$pi->date_from = $date_from;
			# Payoll Record
			$record = $this->getRecords($r);
			$data = [
				'inf' => $pi,
				'record' => $record,
				'payroll_period' => ($r->pptype ?? 1)
			];
			$view = (($r->pptype ?? 1) == 1 ? 'print.reports.payroll.export_payroll_summary_report' : 'print.reports.payroll.export_payroll_summary_report_2');
			// return view($view,compact('data'));
			return Excel::download(new ExportBlade($view, $data), 'general-payroll-'.date('YmdHis').'.xlsx');
		} catch (\Exception $e) {
			return $e;
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
		# General
		$pcode = $r->pcode;
		$record = [];
		$emp = Employee::$emp_sql;
		$gen_type = 'OVERTIME';
		$record = DB::select("SELECT emp.empname, emp.department, emp.biometric, emp.firstname, emp.lastname, emp.mi, emp.empname, emp.tin, emp.pay_rate, emp.cc_desc, emp.department,  CONCAT(dtr.date_from, ' to ', dtr.date_to) payroll_period, dtr.*, pr.* FROM hris.hr_emp_payroll3 pr INNER JOIN hris.hr_emp_payroll_log log ON pr.emp_pay_code = log.emp_pay_code LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id WHERE generationtype = 'OVERTIME') dtr ON pr.dtr_sum_id = dtr.code LEFT JOIN ($emp) emp ON dtr.empid = emp.empid WHERE pr.emp_pay_code = '$pcode' "); /*dd($record);*/

		# Payroll Info
		$hourly_rate = 0; $hourly_rate = (($record[0]->rate / 22) / 2) / 8;

		# OT Regular Day
		$days_worked_arr = json_decode($record[0]->total_overtime_arr);
		$ot_timelogs = [];
		if (isset($days_worked_arr)) {
			for ($i=0; $i < count($days_worked_arr); $i++) { 
				list($date, $timelog, $rendered) = $days_worked_arr[$i]; # [date, timelog, rendered]
				# May 2, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm = 4 hours
				$date = date('M d, Y', strtotime($date));
				$timelog1 = "";
				$timelog2 = "";
				$rendered = Core::ToHourOnly($rendered);
				if (count($timelog) > 2) {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
					$timelog2 = date('h:ia', strtotime($timelog[2]))." - ".date('ha', strtotime($timelog[3]));
				} else {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
				}
				$tmp = [
					'date' => $date,
					'timelog1' => $timelog1,
					'timelog2' => $timelog2,
					'rendered' => $rendered,
				];
			}
		}
		$regular_day_rate = 0; $regular_day_rate = number_format($hourly_rate + ($hourly_rate * 0.25), 2);

		# OT Holiday
		$holiday_rate = 0; $holiday_rate = number_format($hourly_rate + ($hourly_rate * 0.50), 2);
		$ls_holiday_amt = 0; $ls_holiday_amt = $record[0]->legal_holiday_ot_amt + $record[0]->special_holiday_ot_amt;

		$legal_holiday_ot = json_decode($record[0]->legal_holiday_ot);
		$legal_timelogs = [];
		if (count($legal_holiday_ot) > 0) {
			for ($i=0; $i < count($legal_holiday_ot); $i++) {
				list($date, $timelog, $rendered) = $legal_holiday_ot[$i]; # [date, timelog, rendered]
				# May 2, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm = 4 hours
				$date = date('M d, Y', strtotime($date));
				$timelog1 = "";
				$timelog2 = "";
				$rendered = Core::ToHourOnly($rendered);
				if (count($timelog) > 2) {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
					$timelog2 = date('h:ia', strtotime($timelog[2]))." - ".date('ha', strtotime($timelog[3]));
				} else {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
				}
				$tmp = [
					'date' => $date,
					'timelog1' => $timelog1,
					'timelog2' => $timelog2,
					'rendered' => $rendered,
				];
				array_push($legal_timelogs, $tmp);
			}
		}
		
		$special_holiday_ot = json_decode($record[0]->special_holiday_ot);
		$special_timelogs = [];
		if (count($special_holiday_ot) > 0) {
			for ($i=0; $i < count($special_holiday_ot); $i++) { 
				list($date, $timelog, $rendered) = $legal_holiday_ot[$i]; # [date, timelog, rendered]
				# May 2, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm = 4 hours
				$date = date('M d, Y', strtotime($date));
				$timelog1 = "";
				$timelog2 = "";
				$rendered = Core::ToHourOnly($rendered);
				if (count($timelog) > 2) {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
					$timelog2 = date('h:ia', strtotime($timelog[2]))." - ".date('ha', strtotime($timelog[3]));
				} else {
					$timelog1 = date('h:ia', strtotime($timelog[0]))." - ".date('ha', strtotime($timelog[1]));
				}
				$tmp = [
					'date' => $date,
					'timelog1' => $timelog1,
					'timelog2' => $timelog2,
					'rendered' => $rendered,
				];
				array_push($special_timelogs, $tmp);
			}
		}
		return view('print.reports.payroll.print_payroll_summary_report_ot', compact('record', 'ot_timelogs', 'regular_day_rate', 'holiday_rate', 'legal_timelogs', 'special_timelogs', 'ls_holiday_amt'));
	}

}