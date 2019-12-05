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
    	$this->ghistory = DB::table('hr_loanhdr')->where('cancel', '=', null)->orderBy('loan_transdate', 'DESC')/*->orderBy('time_generated', 'DESC')*/->get();
        $this->employees = Employee::Load_Employees();
    }

    public function view()
    {
    	$data = [$this->ghistory, $this->employees, Office::get_all()];
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
            'loan_code'=>$r->txt_code, 
            'loan_desc'=>$r->txt_desc, 
            'loan_transdate'=>$r->dtp_trnxdt, 
            'loan_location'=>$r->cbo_stocklocation, 
            'loan_type'=>$r->cbo_contraacct, 
            'user_id'=>Account::CURRENT()->uid, 
            'whs_location_code'=>$r->cbo_stocklocation/*, 'loan_cost_center_code'=>$r->cbo_costcenter*/, 
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

            DB::table(Loan::$tbl_name)->insert($data);
            Core::Set_Alert('success', 'Successfully added new Loan Entry.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LoanEntryController', '00000', $e->getMessage());
            return back();
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
            $sql = Core::sql("SELECT * FROM hris.hr_loanhdr WHERE hr_loanhdr.employee_no = '".$r->tito_emp."' AND hr_loanhdr.loan_transdate >='".$r->date_from."' AND hr_loanhdr.loan_transdate <= '".$r->date_to."' AND hr_loanhdr.cancel IS NULL ORDER BY hr_loanhdr.loan_transdate DESC");
            if ($sql!=null) {
                for($i=0;$i<count($sql);$i++) {
                    $sql[$i]->type_readable = LoanType::Get_LoanType($sql[$i]->loan_type, $sql[$i]->loan_sub_type);
                    $sql[$i]->loan_transdate = \Carbon\Carbon::parse($sql[$i]->loan_transdate)->format('M d, Y');
                    $sql[$i]->emp_name = Employee::GetEmployee($sql[$i]->employee_no)->lastname.', '.Employee::GetEmployee($sql[$i]->employee_no)->firstname.' '.Employee::GetEmployee($sql[$i]->employee_no)->mi;
                    $sql[$i]->deduction_date = \Carbon\Carbon::parse($sql[$i]->deduction_date)->format('M d, Y');
                    $sql[$i]->period_readable = ($sql[$i]->period_to_pay == "30")?"30th day":"15th day";
                }
                return $sql;
            } else {
                return "No record found.";
            }
        } catch (\Exception $e) {
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
        $emp=Employee::GetEmployee($r->cbo_employee);

        $dpm = number_format( floatval($r->txt_amnt_loan) / floatval($r->txt_mo_tbp) , 2, '.', ''); // deduc per month
        $loan_sub_type = "";

        switch($r->cbo_contraacct) {
            case "pagibig": $loan_sub_type = $r->cbo_pagibig_sub; break;
            case "sss": $loan_sub_type = $r->cbo_sss_sub; break;
            default: $loan_sub_type = "";
        }

        $data = [
            'loan_code'=>$r->txt_code,
            'loan_desc'=>$r->txt_desc,
            'loan_transdate'=>$r->dtp_trnxdt,
            'loan_location'=>$r->cbo_stocklocation,
            'loan_type'=>$r->cbo_contraacct,
            'user_id'=>Account::CURRENT()->uid,
            'whs_location_code'=>$r->cbo_stocklocation/*, 'loan_cost_center_code'=>$r->cbo_costcenter*/,
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
            DB::table(Loan::$tbl_name)->where(Loan::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully modified a Loan Entry.');
            return back();
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LoanEntryController', '00001', $e->getMessage());
            return back();
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
            DB::table(Loan::$tbl_name)->where(Loan::$pk, $r->txt_code)->update($data);
            Core::Set_Alert('success', 'Successfully removed a Loan Entry.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LoanEntryController', '00002', $e->getMessage());
            return back();
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
            // return $r->all();
            $data = DB::table('hr_loanhdr')->where('employee_no', $r->id)->/*whereBetween('loan_transdate', [$r->date_start, $r->date_to])->*/get();
            for($i = 0; $i < count($data); $i++)
            {
                $data[$i]->deptid = Employee::GetEmployee($data[$i]->employee_no)->department;
                $data[$i]->type_readable = LoanType::Get_LoanType($data[$i]->loan_type, $data[$i]->loan_sub_type);
                $data[$i]->loan_transdate = \Carbon\Carbon::parse($data[$i]->loan_transdate)->format('M d, Y');
                $data[$i]->emp_name = Employee::GetEmployee($data[$i]->employee_no)->lastname.', '.Employee::GetEmployee($data[$i]->employee_no)->firstname.' '.Employee::GetEmployee($data[$i]->employee_no)->mi;
                $data[$i]->deduction_date = \Carbon\Carbon::parse($data[$i]->deduction_date)->format('M d, Y');
                $data[$i]->period_readable = ($data[$i]->period_to_pay == "30")?"30th day":"15th day";
            }
            return $data;
        } catch (Exception $e) {
            return "error";
        }
        // try {
        //     $data = DB::table('hr_tito2')->where('empid', $r->id)->whereBetween('work_date', [$r->date_start, $r->date_to])->get();
        //     for($i = 0; $i < count($data); $i++)
        //     {
        //         $data[$i]->status_desc = Core::io((string)$data[$i]->status);
        //         $data[$i]->source_desc = Core::source($data[$i]->source);
        //         $data[$i]->deptid = Employee::GetEmployee($data[$i]->empid)->department;
        //     }
        //     return $data;
        // } catch (Exception $e) {
        //     return "error";
        // }
    }

}
