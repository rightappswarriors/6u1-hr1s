<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use PayrollPeriod;
use Employee;
use EmployeeLeaveCount;
use Timelog;
use Leave;
use LeaveType;
use Payroll;
use Account;
use ErrorCode;

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
    	$data = [$this->ghistory, $this->employees];
    	return view('pages.timekeeping.leaves_entry', compact('data'));
    }

    public function getType(Request $r) {
        $ln = Leave::GetLeaveName($r->get('data'));
        if ($ln!=null) {
            return $ln->description;
        }
        return null;
    }

    public function FindLeaveCountRecord($empid)
    {
        $sql = Core::sql("SELECT code, description, count, empid, peak, carry_over  FROM hris.hr_leave_type a  RIGHT JOIN hris.hr_emp_leavecount b ON a.code = b.leave_type WHERE b.empid = '".$empid."' ORDER BY description");

        return $sql;
    }

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

    public function add(Request $r) 
    {
        $amount = "0.00";
        // if(isset($r->txt_amount)) {
        //     $amount = $r->txt_amount;
        // }

        $fam = ($r->fam == "on")?"True":"False";
        $fpm = ($r->fpm == "on")?"True":"False";
        $tam = ($r->tam == "on")?"True":"False";
        $tpm = ($r->tpm == "on")?"True":"False";
        $data = [
            'lvcode'=>Core::getm99('lvcode'),
            'empid'=>$r->cbo_employee,
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
            'leave_reason'=>$r->txt_reason
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


        if ($r->txt_no_of_days <= 0) {
            Core::Set_Alert('warning', 'Invalid dates.');
            return back();

            // return ['error', 'data' => ['alert_type' => 'warning', 'alert_msg' => 'Invalid dates.']];
        }

        $CheckLeaveLimit = EmployeeLeaveCount::CheckLeaveLimit($r->cbo_leave, $r->cbo_employee, $ampm);
        if ($CheckLeaveLimit->response==="error") {
            ErrorCode::Generate('controller', 'LeavesEntryController', '00003', $CheckLeaveLimit->response);
            Core::Set_Alert('danger', "Error LeEC00003");
            return back();
        } else {
            if ($CheckLeaveLimit->response!="error") {
                if ($CheckLeaveLimit->response=="ok") {
                    if (EmployeeLeaveCount::Update_LeaveLimit($r->cbo_leave, $r->cbo_employee, $CheckLeaveLimit->applied_leave_count, '+')=="ok") {
                        try {
                            DB::table(Leave::$tbl_name)->insert($data);
                            Core::updatem99('lvcode',Core::get_nextincrementlimitchar($data['lvcode'], 8));
                            Core::Set_Alert('success', 'Successfully added new Leaves Entry.');
                            return back();

                        } catch (\Illuminate\Database\QueryException $e) {
                            $emsg = $e->getMessage();
                            Core::Set_Alert('danger', $emsg);
                            ErrorCode::Generate('controller', 'LeavesEntryController', '00003', $emsg);
                            return back();
                        }
                    } else {
                        return back();
                    }
                } else {
                    Core::Set_Alert('warning', 'Employee exceeds the allocated leave limit. Leave entry denied.');
                    return back();
                }
            } else {
                return back();
            }
        }
    }

    public function update(Request $r) {
        $amount = "0.00";
        $lr = DB::table(Leave::$tbl_name)->where(Leave::$pk, $r->txt_code)->first();
        // dd($lr);

        if ($lr==null) {
            $msg = "Leave record not found. Unable to update.";
            Core::Set_Alert('danger', $msg);
            return back();
        }

        // if(isset($r->txt_amount)) {
        //     $amount = $r->txt_amount;
        // }

        $fam = ($r->fam == "on")?"True":"False";
        $fpm = ($r->fpm == "on")?"True":"False";
        $tam = ($r->tam == "on")?"True":"False";
        $tpm = ($r->tpm == "on")?"True":"False";
        $data = [
            'lvcode'=>$r->txt_code, 
            'empid'=>$r->cbo_employee, 
            'd_filed'=>$r->dtp_filed, 
            'leave_from'=>$r->dtp_lfrm, 
            'leave_to'=>$r->dtp_lto, 
            'frm_am'=>($r->fam == "on")?"True":"False", 
            'frm_pm'=>($r->fpm == "on")?"True":"False", 
            'to_am'=>($r->tam == "on")?"True":"False", 
            'to_pm'=>($r->tpm == "on")?"True":"False", 
            'no_of_days'=>$r->txt_no_of_days, 
            'leave_pay'=>$r->cbo_leave_pay, 
            'leave_type'=>$r->cbo_leave, 
            'leave_amount'=>$amount,
            'leave_reason'=>$r->txt_reason
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

        try{
            if (EmployeeLeaveCount::Update_LeaveLimit($lr->leave_type, $lr->empid, $lr->no_of_days, '-')!="ok") {
                return back();
            }
            $CheckLeaveLimit = EmployeeLeaveCount::CheckLeaveLimit($r->cbo_leave, $r->cbo_employee, $ampm);
            if ($CheckLeaveLimit->response==="error") {
                ErrorCode::Generate('controller', 'LeavesEntryController', '0000X', $CheckLeaveLimit->response);
                Core::Set_Alert('danger', "Error LeEC0000X");
                return back();
            } else {
                if ($CheckLeaveLimit->response=="ok") {
                    if (EmployeeLeaveCount::Update_LeaveLimit($r->cbo_leave, $r->cbo_employee, $CheckLeaveLimit->applied_leave_count, '+')=="ok") {
                        try {
                            DB::table(Leave::$tbl_name)->where(Leave::$pk, $r->txt_code)->update($data);
                            Core::Set_Alert('success', 'Successfully update the Leaves Entry.');
                            return back();

                        } catch (\Illuminate\Database\QueryException $e) {
                            $emsg = $e->getMessage();
                            Core::Set_Alert('danger', $emsg);
                            ErrorCode::Generate('controller', 'LeavesEntryController', '0000X', $emsg);
                            return back();
                        }
                    } else {
                        return back();
                    }
                } else {
                    Core::Set_Alert('warning', 'Employee exceeds the allocated leave limit. Leave entry denied.');
                    return back();
                }
            }

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LeavesEntryController', '00002', $e->getMessage());
            return back();
        }
    }

    public function delete(Request $r)
    {
        try {
            $data = ['cancel'=>"Y"];
            $lr = DB::table('hr_leaves')->where('lvcode', '=', $r->txt_code)->first();
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
            $Update_LeaveLimit = EmployeeLeaveCount::Update_LeaveLimit($lr->leave_type, $lr->empid, (float)$lr->no_of_days, '-');
            if ($Update_LeaveLimit!="ok") {
                $msg = 'An error occurred when updating leave limit.';
                ErrorCode::Generate('controller', 'LeavesEntryController', '00007', $msg);
                Core::Set_Alert('danger', $msg);
                return back();
            }
            Core::Set_Alert('success', 'Successfully removed a Leaves Entry.');
            return back();

        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'LeavesEntryController', '00004', $e);
            return back();
        }
    }

    public function get_entry(Request $r)
    {
        // return $r->all();
        $table = DB::table('hr_leaves')->where('lvcode', $r->code)->first();
        $table->name = Employee::Name($table->empid);
        return json_encode($table);
    }
}
