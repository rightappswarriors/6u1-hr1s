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
use LoanType;
use ErrorCode;
use Office;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - LoEC
|
| 00000 - add
| 00001 - update
| 00002 - delete
| 00003 - find
| 
|--------------------------------------------------------------------------
*/

class LoanEntryController extends Controller
{
    

    public function __construct()
    {
    	$this->loanEntryQuery = "SELECT 
                                loan.loan_code,
                                loan.loan_desc,
                                loan.loan_type,
                                loan.loan_amount,
                                loan.loan_deduction,
                                loan.months_to_be_paid,
                                (case 
                                 when loan.period_to_pay = '30' then 
                                    '30th day' 
                                 else 
                                    '15th day' 
                                 end) as period_readable,
                                (to_char(loan.deduction_date :: date, 'Mon dd, yyyy')) as deduction_date,
                                (to_char(loan.loan_transdate :: date, 'Mon dd, yyyy')) as loan_transdate,
                                (emp.lastname||', '||emp.firstname||' '||emp.mi) as emp_name,
                                emp.department as deptid,

                                -- loan type
                                (coalesce((select lt.description 
                                            from hris.hr_loan_type lt
                                            where lt.code = loan.loan_type), 

                                            -- when the result of above select is empty
                                          (case
                                           when loan.loan_type = 'pagibig' then 
                                                (select pb.description 
                                                 from hris.hr_pagibig_sub pb 
                                                 where pb.id::integer = loan.loan_sub_type::integer)
                                           when  loan.loan_type = 'gsis' then
                                                (select s.description
                                                 from hris.hr_sss_sub s 
                                                 where s.id::integer = loan.loan_sub_type::integer)
                                           else 
                                                'Loan type not found'
                                           end))
                                ) as type_readable 
                                from hris.hr_loanhdr loan 
                                left join hris.hr_employee emp 
                                    on emp.empid = loan.employee_no "; // space at the end is intentional
    }

    public function view()
    {

    	$data = ['offices'  => Office::get_all()];

    	return view('pages.payroll.loan.loan_entry', compact('data'));
    }

    /**
    * add ajax
    * @param Request
    *
    * @return view | back
    */
    public function add(Request $r) 
    {
        
        $emp=Employee::GetEmployee($r->empid);
        // dd($emp);

        $dpm = number_format( floatval($r->txt_amnt_loan) / floatval($r->txt_mo_tbp) , 2, '.', ''); // deduc per month
        $loan_sub_type = "";

        switch($r->cbo_contraacct) {
            case "pagibig":
                $loan_sub_type = $r->cbo_pagibig_sub;
                break;
            case "sss":
                $loan_sub_type = $r->cbo_sss_sub;
                break;
            case "gsis":
                $loan_sub_type = $r->cbo_sss_sub;
                break;
            default: $loan_sub_type = "";
        }

        $data = [
            // 'loan_code'=>$r->txt_code, 
            'loan_desc'=>$r->txt_desc, 
            'loan_transdate'=>$r->dtp_trnxdt, 
            // 'loan_location'=>$r->cbo_stocklocation, 
            'loan_type'=>$r->cbo_contraacct, 
            'user_id'=>Account::CURRENT()->uid, 
            // 'whs_location_code'=>$r->cbo_stocklocation/*, 'loan_cost_center_code'=>$r->cbo_costcenter*/, 
            'loan_cost_center_name'=>$r->cbo_costcenter/*, 'loan_sub_cost_center'=>$r->cbo_scc*/, 
            'loan_amount'=>$r->txt_amnt_loan, 
            'loan_deduction'=>$dpm/*, 'deduction_date'=>$r->dtp_deduction*/, 
            'employee_no'=>$r->empid, 
            'employee_name'=>$emp->firstname.' '.$emp->lastname, 
            'months_to_be_paid'=>$r->txt_mo_tbp, 
            'period_to_pay'=>$r->cbo_per_tp, 
            'loan_sub_type'=>$loan_sub_type,
            'status'=>'unpaid'
        ];

        try {

            // DB::table(Loan::$tbl_name)->insert($data);
            // Core::Set_Alert('success', 'Successfully added new Loan Entry.');
            // return back();
            
            DB::table(Loan::$tbl_name)->insert($data);
            return $r->dtp_trnxdt;

        } catch (\Illuminate\Database\QueryException $e) {
            // Core::Set_Alert('danger', $e->getMessage());
            // ErrorCode::Generate('controller', 'LoanEntryController', '00000', $e->getMessage());
            // return back();
            
            return 'error';
        }
    }

    /**
    * find ajax
    * @param Request
    *
    * @return array | null
    */
    public function find(Request $r)
    {
        try {
            $employeeNo = Core::quote($r->tito_emp);
            $dateFrom = Core::quote($r->date_from);
            $dateTo = Core::quote($r->date_to);

            $where = "where loan.employee_no = " . $employeeNo . " and loan.loan_transdate >=" . $dateFrom ." and loan.loan_transdate <=" . $dateTo . " and loan.cancel is null order by loan.loan_transdate desc";

            $data = DB::select($this->loanEntryQuery . $where);

            if ($data != null) {
                return $data;
            } else {
                return "No record found.";
            }
        } catch (\Exception $e) {
            return $e;
            ErrorCode::Generate('controller', 'LoanEntryController', '00003', $e->getMessage());
            return "error";
        }
    }

    /**
    * update ajax
    * @param Reuqest
    *
    * @return view | back
    */
    public function update(Request $r)
    {
        $emp=Employee::GetEmployee($r->empid);

        $dpm = number_format( floatval($r->txt_amnt_loan) / floatval($r->txt_mo_tbp) , 2, '.', ''); // deduc per month
        $loan_sub_type = "";

        switch($r->cbo_contraacct) {
            case "pagibig":
                $loan_sub_type = $r->cbo_pagibig_sub;
                break;
            case "sss":
                $loan_sub_type = $r->cbo_sss_sub;
                break;
            case "gsis":
                $loan_sub_type = $r->cbo_sss_sub;
                break;
            default: $loan_sub_type = "";
        }

        $data = [
            'loan_code'=>$r->txt_code,
            'loan_desc'=>$r->txt_desc,
            'loan_transdate'=>$r->dtp_trnxdt,
            // 'loan_location'=>$r->cbo_stocklocation,
            'loan_type'=>$r->cbo_contraacct,
            'user_id'=>Account::CURRENT()->uid,
            // 'whs_location_code'=>$r->cbo_stocklocation/*, 'loan_cost_center_code'=>$r->cbo_costcenter*/,
            'loan_cost_center_name'=>$r->cbo_costcenter/*, 'loan_sub_cost_center'=>$r->cbo_scc*/,
            'loan_amount'=>$r->txt_amnt_loan,
            'loan_deduction'=>$dpm/*, 'deduction_date'=>$r->dtp_deduction*/,
            'employee_no'=>$r->empid,
            'employee_name'=>$emp->firstname.' '.$emp->lastname,
            'months_to_be_paid'=>$r->txt_mo_tbp,
            'period_to_pay'=>$r->cbo_per_tp,
            'loan_sub_type'=>$loan_sub_type
        ];
        try {
            // DB::table(Loan::$tbl_name)->where(Loan::$pk, $r->txt_code)->update($data);
            // Core::Set_Alert('success', 'Successfully modified a Loan Entry.');
            // return back();

            DB::table('hr_loanhdr')->where('loan_code', $r->txt_code)->update($data);
            return $r->dtp_trnxdt;

        } catch (\Illuminate\Database\QueryException $e) {
            // Core::Set_Alert('danger', $e->getMessage());
            // ErrorCode::Generate('controller', 'LoanEntryController', '00001', $e->getMessage());
            return 'error';
        }
    }

    /**
    * delete ajax
    * @param Request
    *
    * @return view | back
    */
    public function delete(Request $r)
    {
        try {
            $data = ['cancel'=>"Y"];
            DB::table(Loan::$tbl_name)->where(Loan::$pk, $r->code)->update($data);
            return 'Successfully removed a Loan Entry.';

        } catch (\Illuminate\Database\QueryException $e) {
            return 'Error in Removing Loan Entry.';
        }
        
    }

    /**
    * get ajax
    * @param Request
    *
    * @return Object | null
    */
    public function get_entry(Request $r)
    {
        // return $r->all();
        $table = DB::table('hr_loanhdr')->where('loan_code', $r->code)->first();
        $table->name = Employee::Name($table->employee_no);
        return json_encode($table);
    }

    /**
    * finds ID ajax
    * @param Request
    *
    * @return Object | null
    */
    public function FindID(Request $r)
    {
        try {
            $id = Core::quote("%". strtoupper($r->id) ."%");
            $where = "where employee_no like " . $id ." and loan.cancel is null order by loan.loan_transdate desc";
            $data = DB::select($this->loanEntryQuery . $where);

            return $data;
        } catch (Exception $e) {
            return "error";
        }
    }

}
