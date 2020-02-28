<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use ErrorCode;
use Employee;
use ServiceRecord;

class Leave extends Model
{

    public static $tbl_name = "hr_leaves";
    public static $pk = "lvcode";
    public static $approval = "hr_leaves_approval";

    /**
    * Get Leave Entry Info
    * @param String
    * @param String
    * @param String
    * @param String
    *
    * @return array | String
    */
    public static function GetLeaveInfo($empid, $rate, String $dateFrom, String $dateTo)
    {
        try { /*dd($empid, $dateFrom, $dateTo);*/
            $lr = self::GetLeaveRecord($empid, $dateFrom, $dateTo);
            $arr_lr = [];
            /*
            get leave records
            check each record if leave is with pay
            get salary if pay is leave is with pay
            */
            for ($i=0; $i < count($lr); $i++) {
                $temp_arr = (object)[];
                $leave_dates = Core::CoveredDates($lr[$i]->leave_from, $lr[$i]->leave_to);
                if ($lr[$i]->leave_pay == "YES") {
                    $r = 1;
                } else {
                    $r = 0;
                }
                $temp_arr->empid = $empid;
                $temp_arr->lvcode = $lr[$i]->lvcode;
                $temp_arr->lvpay = $r;
                $temp_arr->lvdates = $leave_dates;
                array_push($arr_lr, $temp_arr);
            }

            return $arr_lr;
        } catch (\Exception $e) {
            ErrorCode::Generate('model', 'Leave', '00001', $e->getMessage());
            return "error";
        }
    }

    /**
    * Get Leave Entry Record
    * @param String
    * @param String
    * @param String
    *
    * @return Object | null
    */
    public static function GetLeaveRecord($empid, String $dateFrom, String $dateTo)
    {
    	try {
    		return DB::table('hr_leaves')->whereBetween('leave_from', [$dateFrom, $dateTo])->where([['empid', $empid],['cancel','<>','Y']])->get();

    	} catch (\Exception $e) {
    		return $e->getMessage();
    		return null;
    	}
    }

    public static function GetLeaveRecordPerMonth($empid,$monthFrom,$monthTo,$onleave = false){
        $toRet = [];
        if(isset($empid) && isset($monthFrom) && isset($monthTo)){
            $onleave = ($onleave ? 'YES' : 'NO');
            $from = Date('Y-m-01',strtotime($monthFrom));
            $to = Date('Y-m-t',strtotime($monthTo));
            $toRet = DB::table(self::$tbl_name)
                ->where('empid', '=', $empid)
                ->where('cancel', '=', null)
                ->whereDate('leave_from', '>=', $from)
                ->whereDate('leave_to', '<=', $to)
                ->where('leave_pay', '=', $onleave)
                ->where('isgenerated', '=', FALSE)
                ->get();

        }
        return $toRet;
    }

    /**
    * Get Leave Entry Record2
    * @param String
    * @param String
    * @param bool
    *
    * @return Object | null
    */
    public static function GetLeaveRecord2($empid, String $date, $onleave = false)
    {
        /**
        * @param $empid - employee id
        * @param $date - selected date
        * @param $onleave - by default false; true if on leave
        * @return leave record(s)
        */
        try {
            if ($onleave) {
                $onleave = "YES";
            } else {
                $onleave = "NO";
            }
            return DB::table(self::$tbl_name)->where('isgenerated', '=', FALSE)->where('empid', '=', $empid)->where('cancel', '=', null)->whereDate('leave_from', '<=', $date)->whereDate('leave_to', '>=', $date)->where('leave_pay', '=', $onleave)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
    * Get Leave Entry Name
    * @param String
    *
    * @return Object | null
    */
    public static function GetLeaveName($lvcode) {
    	try {
    		return DB::table('hr_leave_type')->where('code', '=', $lvcode)->where('cancel', '=', null)->first();
    	} catch (\Exception $e) {
    		return $e->getMessage();
    		return null;
    	}
    }

    /**
    * Get Leave Entry Qualified for SIL
    * @param String
    *
    * @return bool
    */
    public static function QualifiedForSIL($empid)
    {
        /*
        | Returns bolean if employee is qualified for Service Incentive Leave benefits
        | Returns true if qualified
        | Returns false if not
        */
        $sr = ServiceRecord::Find_ServiceRecord($empid); // add trim on designation because of the whitespace
        $sr[0]->service_to = ($sr[0]->service_to != null) ? $sr[0]->service_to : date('Y-m-d'); //Set to today if service_to is until present
        $qualified_days = 260; // Without weekends, can be changed but must be an integer/float dataType, 365 with weekends
        $total_days = Core::DateDiff($sr[0]->service_to, $sr[count($sr)-1]->service_from);

        if ($total_days > $qualified_days) {
            return true;
        }
        return false;
    }

    /**
    * Get Today Leave
    * @param
    *
    * @return Object | null
    */
    public static function GetTodayLeave()
    {
        try {
            $leave = DB::table(self::$tbl_name)->where('cancel', '=', null)->whereDate('leave_from', '<=', date('Y-m-d'))->whereDate('leave_to', '>=', date('Y-m-d'))->get();

            return $leave;

        } catch (\Exception $e) {
            return $e->getMessage();
            return null;
        }
    }


}
