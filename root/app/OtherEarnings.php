<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OtherEarnings extends Model
{
    public static $tbl_name = "hr_other_earnings";
    public static $pk = "code";

    public static function Load_List()
    {
    	return DB::table(self::$tbl_name)->where('cancel', null)->get();
    }

    public static function Get_Name($id)
    {
    	return DB::table(self::$tbl_name)->where(self::$pk, $id)->first()->description;
    }

    public static function Get_Records($empid, $from, $to)
    {
        return DB::table('hr_earnings_entry')->where('emp_no', $empid)->where('date_from', $from)->where('date_to', $to)->get();
    }
}
