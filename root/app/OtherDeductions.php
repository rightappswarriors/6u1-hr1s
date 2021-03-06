<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OtherDeductions extends Model
{
    public static $tbl_name = "hr_other_deductions";
    public static $pk = "code";

    /**
    * returns all the deductions that are not cancelled
    * @param
    *
    * @return Object | null
    */
    public static function Load_List()
    {
    	return DB::table(self::$tbl_name)->where('cancel', null)->get();
    }

    /**
    * returns deduction description (specifically name)
    * @param string
    *
    * @return Object | null
    */
    public static function Get_Name($id)
    {
    	return DB::table(self::$tbl_name)->where(self::$pk, $id)->first()->description;
    }

    /**
    * returns records via empid, date_from, date_to
    * @param string, string, string
    *
    * @return Object | null
    */
    public static function Get_Records($empid, $from, $to)
    {
        return DB::table('hr_deduction_entry')->where('emp_no', $empid)->where('date_from', $from)->where('date_to', $to)->get();
    }
}
