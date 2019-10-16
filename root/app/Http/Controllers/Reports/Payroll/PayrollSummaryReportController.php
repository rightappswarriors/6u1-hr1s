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
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00001', $e->getMessage());
			return "error";
		}
	}

	public function RetrieveSummaryReport($empid, $ppID, $dateFrom, $dateTo)
	{
		return DB::table('hr_emp_payroll2')->where('empid', '=', $empid)->where('pp_id', '=',$ppID)->where('date_from', '=',$dateFrom)->where('date_to', '=',$dateTo)->first();
	}

	public function print(Request $r)
	{
		/*$p = DB::table('hr_emp_payroll2')->where('item_no', '=', $r->item_no)->first();
		$emp = Employee::GetEmployee($p->empid);
		$jt = JobTitle::Get_JobTitle($emp->positions);*/
		return view('print.reports.payroll.print_payroll_summary_report3'/*, compact(['p', 'jt'])*/);
	}

	public function getDates(Request $r)
	{
		// return dd($r->all());
		try {
			$return_val = (object)[];
			$pp = Payroll::PayrollPeriod2($r->month, $r->pp, $r->year);
			$pp->from = date('Y-m-d', strtotime($pp->from));
			$pp->to = date('Y-m-d', strtotime($pp->to));
			$return_val->pp = json_encode($pp);
			$return_val->psr = DB::table('hr_dtr_sum_hdr')->where('ppid' , '=', $r->pp)->where('date_from', '=', $pp->from)->where('date_to', '=', $pp->to)->get();
			if (count($return_val->psr) > 0) {
				for ($i=0; $i < count($return_val->psr); $i++) { 
					$psr = $return_val->psr[$i];
					$psr->name = Employee::Name($psr->empid);
				}
			}
			return json_encode($return_val);
		} catch (\Exception $e) {
			ErrorCode::Generate('controller', 'PayrollSummaryReportController', '00003', $e->getMessage());
			return "error";
		}
	}

}