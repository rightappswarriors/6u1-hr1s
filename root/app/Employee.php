<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use JobTitle;

class Employee extends Model
{
    // Load all employees

    public static $tbl_name = "hr_employee";
    public static $pk = "empid";
    public static $emp_sql = "SELECT DISTINCT a.*, COALESCE(b.cc_desc, 'no-assigned-office') cc_desc FROM (SELECT empid, firstname, lastname, mi, CONCAT(lastname, ', ',firstname) AS empname, section, CAST(positions AS INTEGER) positions, picture, CAST(department AS INTEGER) department, date_hired, contractual_date, prohibition_date, date_regular, date_resigned, date_terminated, CAST(empstatus AS INTEGER) empstatus, contract_days, prc, ctc, rate_type, pay_rate, biometric, sss, pagibig, philhealth, payroll_account, tin, tax_bracket, dayoff1, dayoff2, sex, birth, civil_status, religion, height, weight, father, father_address, father_contact, father_job, mother, mother_address, mother_contact, mother_job, emp_contact, home_tel, email, home_address, emergency_name, emergency_contact, em_home_address, relationship, shift_sched_from, shift_sched_sat_from, shift_sched_to, shift_sched_sat_to, fixed_rate, graduate, primary_ed, tertiary_ed, secondary_ed, post_graduate, pagibig_bracket, philhealth_bracket, shift_sched, shift_sched_sat, sss_bracket, fixed_sched, accountnumber, COALESCE(b.jtitle_name, 'no-jobtitle-assigned') jobtitle, COALESCE(c.description, 'no-employee-status') empstatus_desc FROM (SELECT * FROM hris.hr_employee WHERE cancel IS NULL ORDER BY lastname ASC) a LEFT JOIN (SELECT * FROM hris.hr_jobtitle WHERE cancel IS NULL ) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id) a LEFT JOIN (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08 WHERE active IS TRUE) b ON a.department = b.cc_id"; // SQL For hr_employee table JOINS hr_jobtitle JOINS hr_emp_status JOINS rssys.m08

    public static function Load_Employees()
    {
    	return DB::table(self::$tbl_name)->where('cancel', '=', null)->orderBy('lastname', 'ASC')->get();
    }

    //added by Syrel for Dynamic where clause and condition
    public static function Load_Employees_Dynamic($whereClause = [], $selectFirstOnly = false)
    {
        $toReturn = DB::table(self::$tbl_name)->where($whereClause);
        return ($selectFirstOnly ? $toReturn->first() : $toReturn->get());
    }

    public static function Load_Employees_Office($ofc_id)
    {
        return DB::table(self::$tbl_name)->where('cancel', '=', null)->where('department', $ofc_id)->orderBy('lastname', 'ASC')->get();
    }

    public static function Load_Employees_Simple($deptid = null)
    {
        $data = array();
        $query = DB::table(self::$tbl_name)->select('empid', 'department')->where('cancel', '=', null)->get();
        for($i=0; $i<count($query); $i++) {
            if($deptid != null) {
                if($query[$i]->department == $deptid) {
                    $data[] = [$query[$i]->empid, self::Name($query[$i]->empid), $query[$i]->department];
                }
            } else {
                $data[] = [$query[$i]->empid, self::Name($query[$i]->empid), $query[$i]->department];
            }
        }

        return $data;
    }

    public static function bmIDtoempID($bmid)
    {
    	$returnVal = null;
    	$employee = DB::table(self::$tbl_name)->where('biometric', $bmid)->first();
    	if ($employee!=null) {
    		$returnVal = $employee->empid;
    	}
    	return $returnVal;
    }

    public static function empIDtobmID($empid)
    {
    	$returnVal = null;
    	$employee = DB::table(self::$tbl_name)->where('empid', $empid)->first();
    	if ($employee!=null) {
    		$returnVal = $employee->biometric;
    	}
    	return $returnVal;
    }

    public static function GetEmployee($empid)
    {
        /**
        * @return employee info
        */
        try {
            return DB::table(self::$tbl_name)->where('empid', $empid)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function Name($empid, $mode = null)
    {
        try {
            $name = "System Notice: Employee Not Found";
            $db = DB::table(self::$tbl_name)->where('empid', $empid)->first();
            if ($db!=null) {
                $name = $db->firstname." ".$db->lastname;
                if ($mode == "complete") {
                    $name = $db->firstname." ".$db->mi.". ".$db->lastname;
                }
            }
            return $name;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function RateType($empid, $bool = 0)
    {
        try {
            $data = DB::table(self::$tbl_name)->where('empid', $empid)->first();
            if ($data!=null) {
                if ($bool==1) {
                    $data->rate_type = Core::ToWord($data->rate_type);
                }
                return $data->rate_type;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function GetDepartment($deptid)
    {
        try {
            $deptid = trim($deptid);
            $data = DB::table(DB::raw('rssys.m08'))->where('rssys.m08.cc_code', '=', trim($deptid))->select('rssys.m08.cc_desc')->first()->cc_desc;
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function GetJobTitle($empid)
    {
        try {
            $empid = trim($empid);
            $data = JobTitle::Get_JobTitle(Employee::GetEmployee($empid)->positions);
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function GetBiometric($empid)
    {
        try {
            $empid = trim($empid);
            $data = DB::table(self::$tbl_name)->where('empid', $empid)->first();
            return $data->biometric;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function IfEmployeeInOffice($empid, $ofc_id) {
        $data = DB::table(self::$tbl_name)->where('empid', $empid)->first();
        if(isset($data)){
            return $data->department == $ofc_id;    
        }
        
    }
    public static function getEmployeeOffice($ofc_id){
        $sql = "SELECT a.*, COALESCE(b.cc_desc, 'no-assigned-office') cc_desc FROM (SELECT empid, firstname, lastname, mi, CONCAT(lastname, ', ',firstname) AS empname, section, CAST(positions AS INTEGER) positions, picture, CAST(department AS INTEGER) department, date_hired, contractual_date, prohibition_date, date_regular, date_resigned, date_terminated, CAST(empstatus AS INTEGER) empstatus, contract_days, prc, ctc, rate_type, pay_rate, biometric, sss, pagibig, philhealth,  sss_bracket, fixed_sched, accountnumber, COALESCE(b.jtitle_name, 'no-jobtitle-assigned') jobtitle, COALESCE(c.description, 'no-employee-status') empstatus_desc FROM (SELECT * FROM hris.hr_employee WHERE cancel IS NULL ORDER BY lastname ASC) a LEFT JOIN (SELECT * FROM hris.hr_jobtitle WHERE cancel IS NULL ) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id) a LEFT JOIN (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08 WHERE active IS TRUE) b ON a.department = b.cc_id WHERE b.cc_id = '$ofc_id'";

        return DB::select(DB::raw($sql));

    }

    public static function getGeneratedEmployee($gentype,$datefrom,$dateto){
        $sql = "SELECT emp.*, dtr.* FROM hris.hr_employee emp LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_hdr hdr INNER JOIN hris.hr_dtr_sum_employees ln ON hdr.code = ln.dtr_sum_id) dtr ON emp.empid = dtr.empid WHERE dtr.generationtype = '$gentype' AND dtr.date_from = '$datefrom' AND dtr.date_to = '$dateto' AND emp.cancel IS NULL";

        return DB::select(DB::raw($sql));
    }

    public static function isGeneratedOnDTR($empid, $dfrom, $dto, $generationtype){
        // $pp = Payroll::PayrollPeriod2($dfrom,$pp, $year);
        // $covereddates = Core::CoveredDates($r->monthFrom, $r->monthTo);
        // return [$empid, $dfrom, $dto, $generationtype];
        return json_encode(DB::table('hr_dtr_sum_hdr')->where('empid', $empid)->where('date_from', $dfrom)->where('date_to', $dto)->where('generationtype', $generationtype)->first()!=null);
    }

    public static function getOfficeByID($empid){
        $sql = DB::table(self::$tbl_name)->select('department')->where('empid', $empid)->first();
        if(isset($sql)){
            return $sql;
        }
    }   

}
