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

    /**
    * 
    * @param 
    *
    * @return Object|null
    */
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

    /**
    * Gets the header via ID
    * @param string
    *
    * @return Object|null|string
    */
    public static function Get_HDR($id)
    {
    	try {
    		return DB::table(self::$tbl_name)->where(self::$pk, '=', $id)->first();
    	} catch (\Exception $e) {
    		ErrorCode::Generate('model', 'DTR', '00001', $e->getMessage());
    		return "error";
    	}
    }

    /**
    * Gets all the header periods, distinct
    * @param
    *
    * @return Object|null|string
    */
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

    /**
    * Gets all the Summary via code
    * @param string
    *
    * @return Object|null|string
    *
    */
    public static function GetAllHDRSummaryByCode($code)
    {
        try {
            return DB::table(self::$tbl_name2)->where('dtr_sum_id', $code)->get();
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return "error";
        }
    }

    /**
    * Gets all Summary via date_from
    * @param string
    *
    * @return Object|null|string
    */
    public static function GetAllHDRSummaryByDate($date_from, $type)
    {
        try {
            // return DB::table(self::$tbl_name)->where('date_from', $date_from)/*->where('date_to', $date_to)*/->get();
            return DB::table(self::$tbl_name2)
                        ->select('hr_dtr_sum_hdr.*', 'hr_dtr_sum_employees.*')
                        ->leftJoin('hr_dtr_sum_hdr', 'hr_dtr_sum_hdr.code', '=', 'hr_dtr_sum_employees.dtr_sum_id')
                        ->where('hr_dtr_sum_hdr.date_from', $date_from)
                        ->where('hr_dtr_sum_hdr.generationtype', $type)
                        ->get();
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return "error";
        }
    }

    /**
    * Gets all Summary via date_from
    * @param string
    *
    * @return Object|null|string
    */
    public static function GetAllHDRSummaryByDateWithEmployee($date_from, $type, $emp)
    {
        try {
            // return DB::table(self::$tbl_name)->where('date_from', $date_from)/*->where('date_to', $date_to)*/->get();
            $data = DB::table(self::$tbl_name2)
                        ->select('hr_dtr_sum_hdr.*', 'hr_dtr_sum_employees.*')
                        ->leftJoin('hr_dtr_sum_hdr', 'hr_dtr_sum_hdr.code', '=', 'hr_dtr_sum_employees.dtr_sum_id')
                        ->where('hr_dtr_sum_hdr.date_from', $date_from)
                        ->where('hr_dtr_sum_hdr.generationtype', $type)
                        ->where('hr_dtr_sum_hdr.empid', $emp)
                        ->get();

            foreach($data as $k => $v) {
                $data[$k]->days_worked_readable = json_decode($data[$k]->days_worked_arr);
                $data[$k]->undertime_readable = json_decode($data[$k]->undertime_arr);
                if(count($data[$k]->days_worked_readable) > 0)
                    $data[$k]->days_worked_readable[0][3] = \Carbon\Carbon::parse($data[$k]->days_worked_readable[0][0])->format('M d, Y');
                if(count($data[$k]->undertime_readable) > 0)
                    $data[$k]->undertime_readable[0][3] = \Carbon\Carbon::parse($data[$k]->undertime_readable[0][0])->format('M d, Y');
            }

            return $data;
            // foreach($data as $k => $v) {
            //     $data[$k]->date_readable = \Carbon\Carbon::parse($data[$k]->service_from)->format('M d, Y');
            // }
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
    * Gets All Summary via office
    * @param string
    *
    * @return Object|null|string
    */
    public static function GetAllHDRSumarryByOffice($office)
    {
        try {
            $data = DB::table(self::$tbl_name)
                    ->select('hr_dtr_sum_hdr.*', 'hr_employee.department')
                    ->leftJoin('hr_employee', 'hr_employee.empid', '=', 'hr_dtr_sum_hdr.empid')
                    ->where('hr_employee.department', '=', $office)
                    ->distinct('hr_dtr_sum_hdr.date_from', 'hr_dtr_sum_hdr.date_to')
                    ->pluck('hr_dtr_sum_hdr.date_from', 'hr_dtr_sum_hdr.date_to');

            return $data;
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'DTR', '00004', $e->getMessage());
            return $e->getMessage();
        }
    }
}
