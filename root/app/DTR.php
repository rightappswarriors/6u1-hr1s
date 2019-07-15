<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
use ErrorCode;

class DTR extends Model
{
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
    		return DB::table('hr_dtr_sum_hdr')->where('code', '=', $id)->first();
    	} catch (\Exception $e) {
    		ErrorCode::Generate('model', 'DTR', '00001', $e->getMessage());
    		return "error";
    	}
    }
}
