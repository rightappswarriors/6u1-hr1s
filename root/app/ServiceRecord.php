<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class ServiceRecord extends Model
{

    public static $tbl_name = "hr_service_record";
    public static $pk = "sr_code";

    /**
    * returns all the service record that are not cancelled
    * @param
    *
    * @return Object | null
    */
    public static function Load_ServiceRecords()
    {
    	try {
    		return DB::table(self::$tbl_name)->where('cancel', '=', null)->get();

    	} catch (\Exception $e) {
    		return $e->getMessage();
    		return null;
    	}
    }

    /**
    * returns latest record via empid
    * @param string
    *
    * @return Object | null
    */
    public static function Get_Latest_ServiceRecord($empid)
    {
        try {
            return DB::table(self::$tbl_name)->where('cancel', '=', null)->where('empid', '=', $empid)->orderBy(self::$pk, 'desc')->first();

        } catch (\Exception $e) {
            return $e->getMessage();
            return null;
        }
    }

    /**
    * returns a record with a flag
    * @param string, bool
    *
    * @return Object | null
    */
    public static function Find_ServiceRecord($empid, $latest = false)
    {
        $query = DB::table(self::$tbl_name)->where('cancel', '=', null)->where('empid', '=', $empid)->orderBy(self::$pk, 'desc');
        if ($latest == false) {
            $query = $query->get();
        } else {
            $query = $query->first();
        }
        return $query;
    }
}
