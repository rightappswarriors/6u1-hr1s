<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeStatus extends Model
{
    public static $tbl_name = "hr_emp_status";
    public static $pk = "statcode";
    public static $id = "status_id";

    public static function get_all()
    {
    	return DB::table(self::$tbl_name)->where('cancel', '=', null)->orderBy('description', 'ASC')->get();
    }

    public static function find($id)
    {
    	return DB::table(self::$tbl_name)->where('cancel', '=', null)->where(self::$id, '=', $id)->first();
    }

}
