<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Payroll extends Model
{
    public static function FindRecord($empid, $ppid)
    {
	    try {
	    	$record = DB::table('hr_emp_payroll')->where('empid', $empid)->where('ppid', $ppid)->first();
	    	return $record;
	    } catch (\Exception $e) {
	    	return "error";
	    }
    }

    public static function ConvertRate($rate = null, $time = null)
    {
	    $return_val = 0.00;
	    try {
	    	if ($rate != null) {
	    		$return_val = $rate / $time;
	    		return round($return_val,2);
	    	} else {
	    		return $return_val;
	    	}
	    } catch (\Exception $e) {
	    	return $return_val;
	    }
    }

    public static function GetDailyRate($rate, $rate_type)
    {
    	try {
    		if ($rate_type == "D") {
	    		$daily_rate = $rate;
	    	} else {
	    		$daily_rate = ($rate * 12) / 314;
	    	}
	    	return round($daily_rate,2);
    	} catch (\Exception $e) {
    		return 0.00;
    	}
    }

    public static function PayrollPeriod2($month, $pp, $year = null)
    {
    	$m = (int)$month;
        $year = ($year == null || $year == "") ? date('Y') : $year;
        $pd = [
            /*
            -------------------------------------------
            | How to use
            --------------------------------------------------------------
            | $pp => start date|end date|selected month
            */
    		'15D' => '26|10|-1',
    		'30D' => '11|25|0'
    	];

        $year_1 = $year;
        $year_2 = $year;

    	list($f, $l, $im) = explode("|", $pd[$pp]);

        $fm = $m + $im;

        if ($fm == 0 && $pp == "15D") {
            $year_1 = $year - 1;
            $fm = 12;
        }

    	$return_val = (object)[];
        $return_val->id = $pp;

        $return_val->from = date($year_1.'-'.$fm.'-'.$f);
        $return_val->to = date($year_2.'-'.$m.'-'.$l);
        $return_val->start = $f;
        $return_val->end = date('d', strtotime($return_val->to));

        $pp_deduction_trigger = "30D";
        $return_val->d_sss_c = ($return_val->id == $pp_deduction_trigger) ? "Y" : "N";
        $return_val->d_philhealth = ($return_val->id == $pp_deduction_trigger) ? "Y" : "N";
        $return_val->d_pagibig = ($return_val->id == $pp_deduction_trigger) ? "Y" : "N";
        $return_val->d_w_tax = ($return_val->id == $pp_deduction_trigger) ? "Y" : "N";
    	return $return_val;
    }

    public static function IfWithContributions($pp)
    {
        /*
        | Returns given value is on the 2nd payroll period of the month
        */
        if ($pp == '30D') {
            return true;
        }
        return false;
    }
}
