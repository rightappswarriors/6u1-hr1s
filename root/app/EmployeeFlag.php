<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;


class EmployeeFlag extends Model
{
	
	public static $tbl_name = "hr_emp_flag";
	public static $pk = "flag_id";
	
	public static function get_all()
	{
		return DB::table(self::$tbl_name)->where('cancel', '=', null)->get();
	}

	public static function chk_flagged($empid)
	{
		$flag = DB::table(self::$tbl_name)->where('empid', $empid)->where('cancel', '=', null)->first();
		if ($flag==null) {
			return "not-found";
		}
		if ($flag->status==1) {
			return true;
		}
		return false;
	}
}