<?php

namespace App\Http\Controllers\Reports\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use ErrorCode;
use Employee;
use Exports\ExportBlade;
use JobTitle;
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
    }

	public function view()
	{
		$ghistory = $this->ghistory;
		$data = [$ghistory];
		
		
		return view('pages.reports.payroll.payroll_summary_report', compact('data'));
	}

	public function export(Request $r)
	{
		try {
			$pp = Payroll::PayrollPeriod2($r->month, $r->pp, $r->year);
			$rsr = $this->RetrieveSummaryReport($pp->id, $pp->from, $pp->to);
			if (count($rsr) <= 0) {
				return "no record";
			}
			$data = (object)[];
			$data->pp = $pp;
			$data->rsr = $rsr;
	        return Excel::download(new ExportBlade('print.reports.payroll.export_payroll_summary_report', $data), 'general-payroll-'.date('ymdhis').'.xlsx');
		} catch (\Exception $e) {
			dd($e->getMessage());
		}
	}

	public function RetrieveSummaryReport($ppID, $dateFrom, $dateTo)
	{
		return DB::table('hr_emp_payroll2')->where('pp_id', '=',$ppID)->where('date_from', '=',$dateFrom)->where('date_to', '=',$dateTo)->get();
	}

	public function print(Request $r)
	{
		$p = DB::table('hr_emp_payroll2')->where('item_no', '=', $r->item_no)->first();
		$emp = Employee::GetEmployee($p->empid);
		$jt = JobTitle::Get_JobTitle($emp->positions);
		return view('print.reports.payroll.print_payroll_summary_report', compact(['p', 'jt']));
	}

}