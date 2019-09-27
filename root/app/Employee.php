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

    public static function Load_Employees()
    {
    	return DB::table(self::$tbl_name)->where('cancel', '=', null)->orderBy('lastname', 'ASC')->get();
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
        return $data->department == $ofc_id;
    }
}
