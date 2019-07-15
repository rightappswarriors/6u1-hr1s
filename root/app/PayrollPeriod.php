<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PayrollPeriod extends Model
{
    public static $tbl_name = "hr_payrollpariod";
    public static $pk = "pay_code";

    public static function Load_PayrollPeriod()
    {
    	return DB::table(self::$tbl_name)->orderBy('date_from', 'DESC')->get();
    }
    
    public static function getPayrollPeriod($pay_code)
    {
        $date_from = date('Y-m-d');
    	$date_to = date('Y-m-d');
        $db = DB::table(self::$tbl_name)->where('pay_code', $pay_code)->first();
        if ($db!=null) {
            $date = [$date_from, $date_to];
        } else {
            $date = [$date_from, $date_to];
        }
    	return $date;
    }

    public static function GetHolidays($dateFrom, $dateTo, $type = null)
    {
        try {
            $sql = DB::table('hr_holidays')->select('date_holiday', 'holiday_type')->whereBetween('date_holiday', [$dateFrom, $dateTo]);
            $holidays = $sql->get();
            if ($type!=null) {
                $holidays = $sql->where('holiday_type', $type)->get();
            }
            return $holidays;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
