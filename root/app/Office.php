<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use JobTitle;

class Office extends Model
{
    public static $tbl_name = "rssys.m08";
    public static $pk = "cc_code";
    public static $id = "cc_id";

    public static function get_all()
    {
    	return DB::table(self::$tbl_name)->where('active', '=', 't')->orderBy('cc_desc', 'ASC')->get();
    }

    public static function GetOffice($val)
    {
    	return DB::table(self::$tbl_name)->where(self::$id, '=', $val)->first();
    }

    public static function OfficeEmployees($ofc_id)
    {
        $employees = DB::table('hr_employee')->where('department', '=', $ofc_id)->orderBy('empid', 'ASC')->get();
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
}
