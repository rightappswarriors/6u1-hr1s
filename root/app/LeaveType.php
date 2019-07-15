<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class LeaveType extends Model
{
    public static $tbl_name = "hr_leave_type";
    public static $pk = "code";
    public static $cancel = "cancel";

    public static function Load_LeaveTypes()
    {
    	return DB::table(self::$tbl_name)->where(self::$cancel, '=', null)->orderBy('description', 'ASC')->get();
    }

    public static function Find_LeaveTypes($code)
    {
    	return DB::table(self::$tbl_name)->where(self::$cancel, '=', null)->where(self::$pk, '=',$code)->orderBy('description', 'ASC')->first();
    }
}
