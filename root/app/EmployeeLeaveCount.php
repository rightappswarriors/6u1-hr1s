<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Core;
use DB;
use ErrorCode;
use LeaveType;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - ELC
|
| 00000 - Find_EmpLeaveCount
| 00001 - CheckLeaveLimit
| 00002 - CheckLeaveLimit
| 00003 - CheckLeaveLimit
| 00004 - CheckLeaveLimit
| 00005 - CheckLeaveLimit
| 00006 - Update_LeaveLimit
| 00007 - Update_LeaveLimit
| 00008 - GetLeaveCountValue
| 00009 - Create_LeaveLimit
| 00010 - Create_LeaveLimit
| 
|--------------------------------------------------------------------------
*/

class EmployeeLeaveCount extends Model
{

    public static $tbl_name = "hr_emp_leavecount";
    public static $pk = "elccode";
    public static $cancel = "cancel";

    public static function Get_EmpLeaveCount()
    {
    	return DB::table(self::$tbl_name)->where(self::$cancel, '=', null)->get();
    }

    public static function Find_EmpLeaveCount($leave_type, $empid)
    {
    	try {
    		return DB::table(self::$tbl_name)->where(self::$cancel, '=', null)->where('leave_type', '=', $leave_type)->where('empid', '=', $empid)->first();
    	} catch (\Exception $e) {
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00000', $e->getMessage());
    		return null;
    	}
    }

    public static function CheckLeaveLimit($leave_type, $empid, $ampm = [], $allow = false)
    {
        $lt = LeaveType::Find_LeaveTypes($leave_type);
        $alt = LeaveType::Load_LeaveTypes();
        $return_val = (object) [];
        $return_val->response = "error";
        $return_val->msg = null;
        $return_val->applied_leave_count = 0;
        if ($leave_type==null) {
            $return_val->msg = "Missing fields (Leave Type).";
    		ErrorCode::Generate('model', 'EmployeeLeaveCount', '00001', $return_val->msg);
            return $return_val;
    	}

        if ($empid==null) {
            $return_val->msg = "Missing fields (Employee).";
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00002', $return_val->msg);
            return $return_val;
        }

        if (count($alt)==0) {
            $return_val->msg = "No leave types available. Please contact adiministrator";
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00003', $return_val->msg);
            return $return_val;
        }

        if ($lt==null) {
            $return_val->msg = "Leave Type not found.";
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00004', $return_val->msg);
            return $return_val;
        }
        
        try {
            if ($allow) {
                $elc = self::Find_EmpLeaveCount($leave_type, $empid);
                $return_val->applied_leave_count = self::GetLeaveCountValue($ampm);
                if ($elc != null) {
                    if ($elc->count+$return_val->applied_leave_count > $elc->peak) {
                        $return_val->response = "invalid";
                    } else {
                        $return_val->response = "ok";
                    }
                }
            } else {
                $return_val->response = "ok";
            }
            return $return_val;
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00005', $e->getMessage());
            return $return_val;
        }
    }

    public static function Update_LeaveLimit($leave_type, $empid, $applied_leave_count, $operator)
    {
    	try {
            $elc = DB::table(self::$tbl_name)->where('leave_type', '=', $leave_type)->where('empid', '=',$empid)->first();
            if ($elc == null) {
                ErrorCode::Generate('model', 'EmployeeLeaveCount', '00007', "Leave Count Record not found.");
                return "error";
            }

            switch ($operator) {
                case '+':
                    DB::table(self::$tbl_name)->where('leave_type', '=', $leave_type)->where('empid', '=', $empid)->update([
                        'count' => $elc->count+$applied_leave_count
                    ]);
                    break;

                case '-':
                    DB::table(self::$tbl_name)->where('leave_type', '=', $leave_type)->where('empid', '=', $empid)->update([
                        'count' => $elc->count-$applied_leave_count
                    ]);
                    break;

                case '=':
                    DB::table(self::$tbl_name)->where('leave_type', '=', $leave_type)->where('empid', '=', $empid)->update([
                        'peak' => $applied_leave_count
                    ]);
                    break;

                case 'get':
                    return DB::table(self::$tbl_name)->where('leave_type', '=', $leave_type)->where('empid', '=', $empid)->first();
                    break;
                
                default:
                    ErrorCode::Generate('model', 'EmployeeLeaveCount', '00010', "Invalid operator.");
                    return "error";
                    break;
            }

            return "ok";
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00006', $e->getMessage());
            return "error";
        }
    }

    public static function GetLeaveCountValue($ampm)
    { /*dd($ampm);*/
        try {
            $fam = ($ampm['fam'] == "True") ? 0.5 : 0;
            $fpm = ($ampm['fpm'] == "True") ? 0.5 : 0;
            $tam = ($ampm['tam'] == "True") ? 0.5 : 0;
            $tpm = ($ampm['tpm'] == "True") ? 0.5 : 0;
            $f = 0;
            $t = 0;
            if ($fam == $fpm) {$f = 0;}
            if ($tam == $tpm) {$f = 0;}
            if ($fam == $fpm && $tam == $tpm) {$f = 0; $t = 0;}

            $nod = round(Core::TotalDays($ampm['leave_from'], $ampm['leave_to']), 2);
            $nod = $nod - $f - $t;
            return $nod;
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00008', $e->getMessage());
            return 0;
        }
    }

    public static function Create_LeaveLimit($empid)
    {
        try {
            $alt = LeaveType::Load_LeaveTypes();
            foreach ($alt as $key) {
                $elccode = Core::getm99One('elccode')->elccode;
                DB::table(self::$tbl_name)->insert([
                    'elccode' => $elccode,
                    'leave_type' => $key->code,
                    'empid' => $empid,
                    'count' => 0,
                    'peak' => $key->leave_limit
                ]);
                Core::updatem99('elccode',Core::get_nextincrementlimitchar($elccode, 8));
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'EmployeeLeaveCount', '00009', $e->getMessage());
        }
    }
}
