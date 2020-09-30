<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use JobTitle;
use Employee;
use session;

class Office extends Model
{
    public static $tbl_name = "rssys.m08";
    public static $pk = "cc_code";
    public static $id = "cc_id";

    public static function getEmployees($ofc_id, $columns = []) {
        $id = Account::GET_ASSOCIATED_EMPLOYEE();
        
        $employees = DB::table('hr_employee')->where('department', '=', $ofc_id)->where('cancel', null);
        if(isset($id)){
            $employees = $employees->where('empid', '=', $id);
        }

        if (count($columns) > 0) {
            $employees = $employees->orderBy('empid', 'ASC')->get($columns);
        } else {
            $employees = $employees->orderBy('empid', 'ASC')->get();
        }

        return $employees;
    }

    public static function get_all()
    {
        $office = Employee::getOfficeByID(Account::GET_ASSOCIATED_EMPLOYEE());
    	$toRet = DB::table(self::$tbl_name)->where('active', '=', 't');
        if(isset($office)){
            $toRet = $toRet->where(self::$id, '=', $office->department);
        }
        return $toRet->orderBy('cc_desc', 'ASC')->get();
    }

    public static function GetOffice($val)
    {
    	return DB::table(self::$tbl_name)->where(self::$id, '=', $val)->first();
    }

    public static function OfficeEmployees($ofc_id)
    {
        $employees = self::getEmployees($ofc_id);

        if (count($employees) > 0) {
            for ($i=0; $i < count($employees); $i++) {
                $emp = $employees[$i];
                $emp->empname = Employee::name($emp->empid);
                $emp->office = (self::GetOffice($emp->department)!=null) ? self::GetOffice($emp->department)->cc_desc : "office-not-found";
                $emp->jobtitle = JobTitle::Get_JobTitle($emp->positions);
            }
        }
        return json_encode($employees);
    }

    public static function OfficeEmployees_byEmpStat($ofc_id, $empStatus)
    {
        // $employees = Core::sql("SELECT * FROM (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08) a INNER JOIN (SELECT empid, CONCAT(firstname, ' ',lastname) AS empname, mi, CAST(positions AS INTEGER) positions, COALESCE(b.jtitle_name, 'office-not-found') jobtitle, CAST(department AS INTEGER) department, rate_type, pay_rate, biometric, empstatus, COALESCE(c.description, 'employee status-not-found') empstatus_desc, sss, sss_bracket, pagibig, pagibig_bracket, philhealth, philhealth_bracket, payroll_account, tin, tax_bracket, accountnumber, emptype, fixed_sched FROM hris.hr_employee a LEFT JOIN (SELECT jtid, jtitle_name, jt_cn FROM hris.hr_jobtitle WHERE cancel IS NULL) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id WHERE a.cancel IS NULL ORDER BY empname ASC) b ON b.department = a.cc_id"." WHERE cc_id = '".$ofc_id."' AND empstatus = '".$empStatus."'");

        // return json_encode($employees);
        return json_encode(Core::sql(Employee::$emp_sql." WHERE cc_id = '".$ofc_id."' AND empstatus = '".$empStatus."'"));
    }
}
