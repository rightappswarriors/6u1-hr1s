<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Account;
use PayrollPeriod;
use Employee;
use EmployeeLeaveCount;
use Timelog;
use Leave;
use LeaveType;
use Payroll;
use ErrorCode;
use Office;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - LTC
|
| 00001 - find
| 00002 - update
| 00003 - add
| 00004 - delete
| 
|--------------------------------------------------------------------------
*/

class LeavesEntryController extends Controller
{
    public function __construct()
    {
    	$this->ghistory = DB::table('hr_leaves')->where('cancel', '=', null)->orderBy('d_filed', 'DESC')->orderBy('leave_from', 'DESC')->get();
        $this->employees = Employee::Load_Employees();

    }

    public function view()
    {
    	$data = [$this->ghistory, $this->employees, Office::get_all()];
        // dd(session()->all());
    	return view('pages.timekeeping.leaves_entry', compact('data'));
    }

    /**
    * getType ajax
    * @param Request
    *
    * @return String
    */
    public function getType(Request $r) {
        $ln = Leave::GetLeaveName($r->get('data'));
        if ($ln!=null) {
            return $ln->description;
        }
        return null;
    }

    /**
    * find leave count record
    * @param string
    *
    * @return Object
    */
    public function FindLeaveCountRecord($empid)
    {
        $sql = Core::sql("SELECT code, description, count, empid, peak, carry_over  FROM hris.hr_leave_type a  RIGHT JOIN hris.hr_emp_leavecount b ON a.code = b.leave_type WHERE b.empid = '".$empid."' ORDER BY description");

        return $sql;
    }

    /**
    * find ajax
    * @param Request
    *
    * @return array
    */
    public function find(Request $r)
    {
        try {
            $sql = Core::sql("SELECT * FROM hris.hr_leaves WHERE hr_leaves.empid = '".$r->tito_emp."' AND hr_leaves.d_filed >='".$r->date_from."' AND hr_leaves.d_filed <= '".$r->date_to."' AND hr_leaves.cancel IS NULL ORDER BY hr_leaves.d_filed DESC");
            $lcr = $this->FindLeaveCountRecord($r->tito_emp);

            if (count($lcr)==0) {
                EmployeeLeaveCount::Create_LeaveLimit($r->tito_emp);
                $lcr = $this->FindLeaveCountRecord($r->tito_emp);
            }

            if ($sql!=null) {
                for($i=0;$i<count($sql);$i++) {
                    $sql[$i]->d_filed = \Carbon\Carbon::parse($sql[$i]->d_filed)->format('M d, Y');
                    $sql[$i]->emp_name = Employee::GetEmployee($sql[$i]->empid)->lastname.', '.Employee::GetEmployee($sql[$i]->empid)->firstname.' '.Employee::GetEmployee($sql[$i]->empid)->mi;
                    $sql[$i]->leave_from = \Carbon\Carbon::parse($sql[$i]->leave_from)->format('M d, Y');
                    $sql[$i]->leave_to = \Carbon\Carbon::parse($sql[$i]->leave_to)->format('M d, Y');
                    $sql[$i]->lloyd = $sql[$i]->leave_type;
                    $sql[$i]->leave_desc = Leave::GetLeaveName($sql[$i]->leave_type)->description;
                }

                return [$sql, $lcr];
            } else {
                return ["No record found.", $lcr];
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'LeavesEntryController', '00001', $e->getMessage());
            return "error";
        }
    }

    /**
    * add ajax
    * @param Request
    *
    * @return view | back
    */
    public function add(Request $r) 
    {
        // return $r->all();
        /**
        * @param $r->cbo_employee_txt
        * @param $r->cbo_employee
        * @param $r->txt_code
        * @param $r->dtp_filed
        * @param $r->cbo_leave
        * @param $r->dtp_lfrm
        * @param $r->dtp_lfrm
        * @param $r->txt_no_of_days
        * @param $r->dtp_lto
        * @param $r->cbo_leave_pay
        * @param $r->txt_reason
        * @param $r->mode
        * @param $r->from (from application of loan)
        */
        // return dd($r->all());
        $amount = "0.00";
        $lr = null;

        $fam = ($r->fam == "on")?"True":"False";
        $fpm = ($r->fpm == "on")?"True":"False";
        $tam = ($r->tam == "on")?"True":"False";
        $tpm = ($r->tpm == "on")?"True":"False";
        $data = [
            'd_filed'=>$r->dtp_filed,
            'leave_from'=>$r->dtp_lfrm,
            'leave_to'=>$r->dtp_lto,
            'frm_am'=>$fam,
            'frm_pm'=>$fpm,
            'to_am'=>$tam,
            'to_pm'=>$tpm,
            'no_of_days'=>$r->txt_no_of_days,
            'leave_pay'=>$r->cbo_leave_pay,
            'leave_type'=>$r->cbo_leave,
            'leave_amount'=>$amount,
            'leave_reason'=>$r->txt_reason,
            'generatedby'=>Account::ID(),
            'fromapprovalid'=>$r->from
        ];
        $ampm = [
            'leave_from'=>$r->dtp_lfrm,
            'leave_to'=>$r->dtp_lto,
            'fam' => $fam,
            'fpm' => $fpm,
            'tam' => $tam,
            'tpm' => $tpm,
            'no_of_days' => $r->txt_no_of_days,
        ];

        if (isset($r->txt_no_of_days) && $r->txt_no_of_days <= 0) {
            Core::Set_Alert('warning', "Invalid dates.");
            return back();
        }
        $leavepay_mode = true;
        // $leavepay_mode = ((strtoupper($r->cbo_leave_pay) == "YES" ? true : false));
        switch ($r->mode) {

            case 'new':
                if (count(Leave::GetLeaveRecord($r->cbo_employee, $r->dtp_lfrm, $r->dtp_lto)) > 0) {
                    Core::Set_Alert('warning', "Employee is already on leave on the selected dates. Please review the records.");
                    return back();
                }
                $data['lvcode'] = Core::getm99('lvcode');
                $data['empid'] = $r->cbo_employee;
                $check_ll = EmployeeLeaveCount::CheckLeaveLimit($r->cbo_leave, $r->cbo_employee, $ampm, $leavepay_mode);

                $response = $check_ll->response;
                $response_msg = $check_ll->msg;
                if ($response=="invalid") {
                    Core::Set_Alert('warning', $response_msg);
                    return back();
                } else {
                    if ($response=="ok") {
                        if ($leavepay_mode) {
                            EmployeeLeaveCount::Update_LeaveLimit($r->cbo_leave, $r->cbo_employee, $r->txt_no_of_days, '+');
                        }
                        DB::table(Leave::$tbl_name)->insert($data);
                        Core::Set_Alert('success', 'Successfully added new Leaves Entry.');
                        Core::updatem99('lvcode',Core::get_nextincrementlimitchar($data['lvcode'], 8));
                    } else {
                        Core::Set_Alert('warning', $response_msg);
                        return back();
                    }
                }
                break;

            case 'update':
                $lr = DB::table(Leave::$tbl_name)->where(Leave::$pk, $r->txt_code)->first();
                $check_ll = EmployeeLeaveCount::CheckLeaveLimit($r->cbo_leave, $r->cbo_employee, $ampm, $leavepay_mode);
                $response = $check_ll->response;
                $response_msg = $check_ll->msg;
                if ($lr==null) {
                    Core::Set_Alert('danger', "Leave record not found. Unable to update.");
                    return back();
                } else {
                    $leavepay_mode2 = ((strtoupper($lr->leave_pay) == "YES" ? true : false));
                    if ($leavepay_mode2) {
                        EmployeeLeaveCount::Update_LeaveLimit($lr->leave_type, $lr->empid, $lr->no_of_days, '-');
                    }
                }
                if ($response=="invalid") {
                    Core::Set_Alert('warning', $response_msg);
                    return back();
                } else {
                    if ($response=="ok") {
                        if ($leavepay_mode) {
                            EmployeeLeaveCount::Update_LeaveLimit($r->cbo_leave, $r->cbo_employee, $r->txt_no_of_days, '+');
                        }
                        Core::Set_Alert('success', 'Successfully update the Leaves Entry.');
                        DB::table(Leave::$tbl_name)->where(Leave::$pk, $r->txt_code)->update($data);
                    } else {
                        Core::Set_Alert('warning', $response_msg);
                        return back();
                    }
                }
                break;

            case 'override':
                $empid = $r->cbo_employee;
                foreach ($r->except('mode','cbo_employee') as $key => $value) {
                    $data = EmployeeLeaveCount::Update_LeaveLimit($key, $empid, $value, 'get');
                    if(EmployeeLeaveCount::Update_LeaveLimit($key, $empid, $value, '=') == 'ok'){
                        DB::table('hris.hr_emp_leavecount_hist')->insert(['elccode' => $data->elccode, 'leave_type' => $data->leave_type, 'empid' => $data->empid, 'count' => $data->count, 'peak' => $data->peak, 't_time' => Date('G:i:s'), 't_date' => Date('Y-m-d'), 'editby' => (Core::getSessionData()[0]->uid ?? 'NOT LOGGED IN')]);
                    }
                }
                break;

            case 'apply':
                //to work here
                unset($data['generatedby']);
                $data['empid'] = $r->cbo_employee;
                $data['status'] = 0;
                $data['t_date'] = Date('Y-m-d');
                $data['t_time'] = Date('G:i:s');
                if(DB::table(Leave::$approval)->insert($data)){
                    Core::Set_Alert('success', 'Applied Successfully. Please wait for notification regarding the decision');
                }
                break;


            
            default:
                Core::Set_Alert('danger', 'An error occured. Please contact administrator. (No mode selected)');
                return back();
                break;
        }
        return back();
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
            $lr = DB::table('hr_leaves')->where('lvcode', '=', $r->txt_code)->first();
            $leavepay_mode = ((strtoupper($lr->leave_pay) == "YES" ? true : false));
            $elc = DB::table('hr_emp_leavecount')->where('leave_type', '=', $lr->leave_type)->where('empid', $lr->empid)->first();
            if ($lr==null) {
                $msg = 'Leave record not found.';
                ErrorCode::Generate('controller', 'LeavesEntryController', '00005', $msg);
                Core::Set_Alert('danger', $msg);
                return back();
            }

            if ($elc==null) {
                $msg = 'Leave count error. No record.';
                ErrorCode::Generate('controller', 'LeavesEntryController', '00006', $msg);
                Core::Set_Alert('danger', $msg);
                return back();
            }
            DB::table(Leave::$tbl_name)->where(Leave::$pk, $r->txt_code)->update($data);
            DB::table('hr_emp_leavecount')->where([['empid', $lr->empid],['leave_type',$r->type]])->update(['count' => $elc->count - $r->noofdays]);
            if ($leavepay_mode) {
                $Update_LeaveLimit = EmployeeLeaveCount::Update_LeaveLimit($lr->leave_type, $lr->empid, (float)$lr->no_of_days, '-');
                // if ($Update_LeaveLimit!="ok") {
                //     $msg = 'An error occurred when updating leave limit.';
                //     ErrorCode::Generate('controller', 'LeavesEntryController', '00007', $msg);
                //     Core::Set_Alert('danger', $msg);
                //     return back();
                // }
            }
            Core::Set_Alert('success', 'Successfully removed a Leaves Entry.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LeavesEntryController', '00004', $e);
            return back();
        }
    }

    /**
    * get ajax
    * @param Request
    *
    * @return json
    */
    public function get_entry(Request $r)
    {
        // return $r->all();
        $table = DB::table('hr_leaves')->where('lvcode', $r->code)->first();
        $table->name = Employee::Name($table->empid);
        return json_encode($table);
    }
}
