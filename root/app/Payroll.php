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
    	/*
        * D = Daily; If not "D" it meants $rate_type = "M" for "Monthly"
        * Computation for Daily Rate if $rate_type is "M"
        * | Daily Rate = (Rate times 12 Months) divided by 314 days
        */
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

    public static function PayPeriods()
    {
        /**
        * Return how many payroll periods within a month
        */
        return 2;
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
        if ($pp == '15D') {
            return true;
        }
        return false;
    }

    public static function WithHoldingTax($rate, $tax_bracket)
    {
        $amt = 0;
        try {
            $wtax = DB::table('hr_wtax')->where('code', $tax_bracket)->first();
            if ($wtax!=null) {
                $asd = [];
                $wtax_arr = [
                   [$wtax->bracket1, $wtax->factor1, $wtax->add_on1],
                   [$wtax->bracket2, $wtax->factor2, $wtax->add_on2],
                   [$wtax->bracket3, $wtax->factor3, $wtax->add_on3],
                   [$wtax->bracket4, $wtax->factor4, $wtax->add_on4],
                   [$wtax->bracket5, $wtax->factor5, $wtax->add_on5],
                   [$wtax->bracket6, $wtax->factor6, $wtax->add_on6],
                   [$wtax->bracket7, $wtax->factor7, $wtax->add_on7],
                   [$wtax->bracket8, $wtax->factor8, $wtax->add_on8],
                   [$wtax->bracket9, $wtax->factor9, $wtax->add_on9],
                ];
                for ($i=0; $i < count($wtax_arr); $i++) { 
                    list($bs, $fs, $as) = $wtax_arr[($i == 0) ? $i : $i-1];
                    list($be, $fe, $fa) = $wtax_arr[$i];
                    # start range (eg. START - XX)
                    $a = 0;
                    if ($i == 0) {
                        $a = 0;
                    } else {
                        if ($be == 0 && $fe == 0 && $fa == 0) {
                            $a= 0;
                        } else {
                            $a = (float)$bs+1;
                        }
                    }
                    # end range (eg. XX - END)
                    $b = 0;
                    if ($be == 0 && $fe != 0) {
                        $b = $a * 2;
                    } else {
                        $b = (float)$be;
                    }
                    if ($rate > $a && $rate <= $b) {
                        $amt = (($rate - $a) * ((float)$fe / 100)) + (float)$fa;
                    }
                }
                
            }
            return $amt;
        } catch (\Exception $e) {
            return $amt;
            // return $e->getMessage();
        }
    }
}
