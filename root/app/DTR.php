<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use ErrorCode;

class DTR extends Model
{
    public static $tbl_name = "hr_dtr_sum_hdr";
    public static $tbl_name2 = "hr_dtr_sum_employees";
    public static $pk = "code";
    public static $pk2 = "dtr_sum_id";

    public static function GetTimedInToday()
    {
    	try {
			$schema = env('DB_SCHEMA');
			$sql = Core::sql("SELECT work_date, array_to_string(array_agg(time_log), ',') time_log, empid, array_to_string(array_agg(status), ',') status, array_to_string(array_agg(logs_id), ',') logs_id FROM ".DB::raw($schema).".hr_tito2 hr_tito2 WHERE work_date >= '".date('Y-m-d')."' GROUP BY hr_tito2.work_date, empid ORDER BY hr_tito2.work_date ASC LIMIT 5");
			return $sql;
		} catch (\Exception $e) {
			return [];
		}	
    }

    public static function Get_HDR($id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where(self::$pk, '=', $id)->first();
    	} catch (\Exception $e) {
    		ErrorCode::Generate('model', 'DTR', '00001', $e->getMessage());
    		return "error";
    	}
    }

    public static function GetAllHDRPeriods()
    {
        try {
            // return DB::table(self::$tbl_name)->distinct('date_from')->get();
            return DB::select("SELECT DISTINCT date_from, date_to FROM hris.hr_dtr_sum_hdr");
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00002', $e->getMessage());
            return "error";
        }
    }

    public static function GetAllHDRSummaryByCode($code)
    {
        try {
            return DB::table(self::$tbl_name2)->where('dtr_sum_id', $code)->get();
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return "error";
        }
    }

    public static function GetAllHDRSumarryByOffice($office)
    {
        try {
            $data = DB::table(self::$tbl_name)
                    ->select('hr_dtr_sum_hdr.*', 'hr_employee.department')
                    ->leftJoin('hr_employee', 'hr_employee.empid', '=', 'hr_dtr_sum_hdr.empid')
                    ->where('hr_employee.department', '=', $office)
                    ->get();

            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return "error";
        }
    }
}
