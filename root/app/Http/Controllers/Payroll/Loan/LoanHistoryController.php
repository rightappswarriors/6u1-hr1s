<?php

namespace App\Http\Controllers\Payroll\Loan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use PayrollPeriod;
use Employee;
use Timelog;
use Leave;
use Payroll;
use Account;
use Loan;
use ErrorCode;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - LHC
|
| 00000 - find
| 
|--------------------------------------------------------------------------
*/

class LoanHistoryController extends Controller
{

    public function __construct()
    {
    	$this->ghistory = DB::table('hr_loanhdr')->where('cancel', '=', null)->orderBy('loan_transdate', 'DESC')/*->orderBy('time_generated', 'DESC')*/->get();
        $this->employees = Employee::Load_Employees();

    }

    public function view()
    {
    	$data = [$this->ghistory, $this->employees];
    	return view('pages.payroll.loan.loan_history', compact('data'));
    }

    public function find(Request $r)
    {
        try {
            $sql = Core::sql("SELECT loan_code, loan_transdate, employee_no, employee_name, loan_amount, loan_deduction, loan_desc, deduction_date, loan_location, loan_cost_center_code, loan_sub_cost_center FROM hris.hr_loanhdr WHERE hr_loanhdr.employee_no = '".$r->tito_emp."' AND hr_loanhdr.loan_transdate >='".$r->date_from."' AND hr_loanhdr.loan_transdate <= '".$r->date_to."' AND hr_loanhdr.cancel IS NULL ORDER BY hr_loanhdr.loan_transdate DESC");

            if ($sql!=null) {
                for($i=0;$i<count($sql);$i++) {
                    $sql[$i]->loan_transdate = \Carbon\Carbon::parse($sql[$i]->loan_transdate)->format('M d, Y');
                    $sql[$i]->deduction_date = \Carbon\Carbon::parse($sql[$i]->deduction_date)->format('M d, Y');
                }

                return $sql;
            } else {
                return "No record found.";
            }
        } catch (\Exception $e) {
            return "error";
            ErrorCode::Generate('controller', 'LoanHistoryController', '00000', $e->getMessage()); 
        }
    }
}
