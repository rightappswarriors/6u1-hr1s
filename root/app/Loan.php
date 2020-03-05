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

    /**
    * returns all the loans that are not cancelled
    * @param
    *
    * @return Object | null
    */
    public static function Load_Loans()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    /**
    * returns a loan via empid and date
    * @param string, string
    *
    * @return Object | null
    */
    public static function Find_Loan($empid, string $date)
    {
    	$tbl = DB::table(self::$tbl_name)->where('cancel', '=', null)->where('deduction_date', '=', date('Y-m-d', strtotime($date)))->where('employee_no', '=', $empid)->get();
    	return $tbl;
    }

    public static function Find_Loan2($empid)
    {
        try {
            return DB::table(self::$tbl_name)->where('cancel', null)->where('employee_no', $empid)->get();
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function PreviousLoanRecords($loanhdr_id)
    {
        try {
            return DB::table('hr_loanln')->where('loan_hdr_code', $loanhdr_id)->get();
        } catch (\Exception $e) {
            return $e;
            return [];
        }
    }

}