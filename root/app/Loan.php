<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class Loan extends Model
{
	// Load all loans

    public static $tbl_name = "hr_loanhdr";
    public static $pk = "loan_code";

    public static function Load_Loans()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    public static function Find_Loan($empid, string $date)
    {
    	$tbl = DB::table(self::$tbl_name)->where('cancel', '=', null)->where('deduction_date', '=', date('Y-m-d', strtotime($date)))->where('employee_no', '=', $empid)->get();
    	return $tbl;
    }

}