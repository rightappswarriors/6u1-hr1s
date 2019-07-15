<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Employee;
use Payroll;
use Timelog;

class ViewGeneratedPayrollController extends Controller
{
	public function __construct()
	{
		// $this->
	}

	public function view()
	{
		$payroll = DB::table('hr_emp_payroll')
			->leftjoin('hr_employee', 'hr_emp_payroll.empid', '=', 'hr_employee.empid')
			->leftjoin('hr_payrollpariod', 'hr_emp_payroll.ppid', '=', 'hr_payrollpariod.pay_code')
			->select('hr_emp_payroll.emp_pay_code', 'hr_emp_payroll.empid', 'hr_employee.firstname', 'hr_employee.lastname', 'hr_payrollpariod.date_from', 'hr_payrollpariod.date_to')
			->orderBy('hr_payrollpariod.date_from', 'DESC')
			->get();

		for ($i=0; $i < count($payroll); $i++) { 
			$payroll[$i]->name = $payroll[$i]->firstname." ".$payroll[$i]->lastname;
			$payroll[$i]->pp = $payroll[$i]->date_from." to ".$payroll[$i]->date_to;
		}

		$data = [$payroll];
		return view('pages.payroll.view_generated_payroll', compact('data'));
	}

	public function info(Request $r)
	{
		try {
			$d = DB::table('hr_emp_payroll')
				->select('hr_emp_payroll.*')
				->where('hr_emp_payroll.emp_pay_code', $r->id)
				->first();
			$e = Employee::GetEmployee($d->empid);
			if ($e == null) {
				return "error";
			}
			$ds = DB::table('hr_dtr_sum_employees')->where('empid', $d->empid)->where('ppid', $d->ppid)->first();
			if ($ds==null) {
				return "error";
			}

			$d->rateType = Employee::RateType($d->empid, 1);
			$d->rate = $e->pay_rate;
			$d->dailyrate = Payroll::GetDailyRate($e->pay_rate, $d->rate);
			$d->hourlyrate = Payroll::ConvertRate($e->pay_rate, Timelog::ShiftHours($d->empid));
			$d->minuterate = Payroll::ConvertRate($d->hourlyrate, 60);
			$d->grosspay = Core::GetTotal([
				$d->basic_pay,
                $d->reqular_ot_b, 
                $d->dayoff_ot_b,
                $d->legal_hol_ot_b,
                $d->special_hol_ot_b,
                $d->leave_amnt,
                $d->other_earnings
			])[0];
			$d->deductions = Core::GetTotal([
				$d->sss_cont_b,
                $d->philhealth_cont_b,
                $d->pag_ibig_b,
                $d->w_tax,
                $d->other_deduction,
                $d->advances_loans,
                $d->others
			])[0];
			$d->netpay = ($d->grosspay + $d->deductions);

			$d->absences_a = $ds->absences;
			$d->absences_b = $ds->absences * $d->dailyrate;
			$d->late_a = Core::ToHours($ds->late);
			$d->late_b  = Core::ToMinutes($ds->late) * $d->minuterate;
			$d->undertime_a = Core::ToHours($ds->undertime);
			$d->undertime_b = Core::ToMinutes($ds->undertime) * $d->minuterate;
			$d->lu_a = $d->late_a + $d->undertime_a;
			$d->lu_b = $d->late_b + $d->undertime_b;

			// $d->regularot
			return json_encode($d);
		} catch (\Exception $e) {
			return $e->getMessage();
			return "error";
		}
	}
}