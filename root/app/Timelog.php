<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;
use Leave;

class Timelog extends Model
{
    public static $lunchbreak; // Must be in h:m:s format and 24 hours

    public function __construct() {
        self::$lunchbreak = self::get_lunch_break(); // Must be in h:m:s format and 24 hours
    }

    public static function getWorkdates($empid, $date1, $date2, $con = [])
    {
    	try {
    		$date1 = date('Y-m-d', strtotime($date1));
	    	$date2 = date('Y-m-d', strtotime($date2));
            $sql = DB::table('hr_tito2')->where('empid', $empid)->whereBetween('work_date', [$date1,$date2])->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC');
            if (count($con)>0) {
                foreach($con as $c) {
                    $sql->where($c[0], $c[1]);
                }
            }
	    	return $sql->orderBy('time_log', 'ASC')->get();
    	} catch (\Exception $e) {
    		return "error";
    	}
    	
    }

    public static function ShiftHours()
    {
        /**
        * Returns total hours between start time and end time
        * In this case, Required Time In in the Morning (eg. 8 AM) and Required Time Out in the Afternoon (eg. 5 PM)
        * In double (0.00) format
        * @return 0.00
        */
        try {
            $time = Core::GET_TIME_DIFF(self::ReqTimeIn(), self::ReqTimeOut_2());
            list($hour, $minute) = explode(":", $time);
            $minute = $minute / 60;
            $hour = $hour + $minute;
            $hour = $hour - Core::ToHours(self::get_lunch_break());
            return round($hour, 2);
        } catch (\Exception $e) {
            // return $e->getMessage();
            return 0;
        }
    }

    public static function ReqTimeIn()
    {
        /**
        * Returns the required time in (morning).
        * Value returned must be in string format
        * @return "hh:mm:ss"
        */
        // return "08:00";
        $fy = DB::table('hris.m99')->first()->fy;
        $d = DB::table('hris.m99')->where('fy', $fy)->first()->req_time_in_1;
        return ($d == null || $d == "")?"08:00":$d;
    }

    public static function ReqTimeInWithoutGracePeriod()
    {
        /**
        * Returns the required time in (morning).
        * Value returned must be in string format
        * @return "hh:mm:ss"
        */
        // return "08:00";
        $fy = DB::table('hris.m99')->first()->fy;
        $d = DB::table('hris.m99')->where('fy', $fy)->first()->req_time_in_wo_grace_period;
        return ($d == null || $d == "")?"08:00":$d;
    }

    public static function ReqTimeIn_2()
    {
        /**
        * Returns the required time in (afternoon).
        * Value returned must be in string format
        * @return "hh:mm:ss"
        */

        $req_timeout_1 = self::ReqTimeOut();
        $lunch = self::get_lunch_break();
        return Core::GET_TIME_TOTAL([$req_timeout_1, $lunch]);
    }

    public static function ReqTimeOut()
    {
        /**
        * Returns the required time out (morning).
        * Value returned must be in string format
        * @return "hh:mm:ss"
        */
       // return "12:00";
        $fy = DB::table('hris.m99')->first()->fy;
        $d = DB::table('hris.m99')->where('fy', $fy)->first()->req_time_out_1;
        return ($d == null || $d == "")?"12:00":$d;
    }

    public static function ReqTimeOut_2()
    {
        /**
        * Returns the required time out (afternoon).
        * Value returned must be in string format
        * @return "hh:mm:ss"
        */
       // return "17:40";
        $fy = DB::table('hris.m99')->first()->fy;
        $d = DB::table('hris.m99')->where('fy', $fy)->first()->req_time_out_2;
        return ($d == null || $d == "")?"17:00":$d;
    }

    // public static $lunchbreak = self::get_lunch_break(); // Must be in h:m:s format and 24 hours

    public static function get_lunch_break()
    {
        /* --------- OLD --------- */
        /**
        * Must be in h:m:s format and 24 hours
        * @return "hh:mm:ss"
        */
        // $fy = DB::table('hris.m99')->first()->fy;
        // $d = DB::table('hris.m99')->where('fy', $fy)->first()->lunch_break;
        // return ($d == null || $d == "")?"12:00":$d;
        /* --------- OLD --------- */

        /**
        * Returns the duration of the lunch break
        * Must be in h:m:s format and 24 hours
        * @return "hh:mm:ss"
        */
        try {
            return DB::table('hris.m99')->first()->lunch_break;
        } catch (\Exception $e) {
            return "00:00";
        }
    }

    public static function MinReqOTHrs()
    {
        /**
        * Minimum OT Hours
        * Must be in h:m:s format and 24 hours
        * @return "hh:mm:ss"
        */
        return "00:59";
    }

    public static function MinReqOTHrs2()
    {
        /**
        * Minimum OT Hours
        * Must be in h:m:s format and 24 hours
        * @return "hh:mm:ss"
        * from database
        */
        $fy = DB::table('hris.m99')->first()->fy;
        $d = DB::table('hris.m99')->where('fy', $fy)->first()->req_min_ot;
        return ($d == null || $d == "")?"01:00":$d;
    }


    public static function ValidateLog_AM(string $time)
    {
        /**
        * @param string "hh:mm:ss" format
        * Validates a log if it is within ReqTimeIn() and ReqTimeOut() range
        * @return bolean true / false
        */ 

        if (strtotime(self::ReqTimeIn()) <= strtotime($time) && strtotime($time) <= strtotime(self::ReqTimeOut()) || self::ReqTimeIn() > strtotime($time)) {
            return true;
        } else {
            return false;
        }
    }

    public static function ValidateLog_PM(string $time)
    {
        /**
        * @param string "hh:mm:ss" format
        * Validates a log if it is within ReqTimeIn_2() and ReqTimeOut_2()+MinReqOTHrs() range
        * MinReqOTHrs() is added to ReqTimeOut_2() is because the minimum required overtime hours is stated in MinReqOTHrs()
        * @return bolean true / false
        */

        if (strtotime(self::ReqTimeIn_2()) <= strtotime($time) && strtotime($time) <= strtotime(Core::GET_TIME_TOTAL([self::ReqTimeOut_2(), self::MinReqOTHrs()]))) {
            return true;
        } else {
            return false;
        }
    }

    public static function ValidateLog_OTHrs(string $time)
    {
        /**
        * @param string "00:00" format
        * Validates a log if it is Over ReqTimeOut_2()(PM time out) + MinReqOTHrs() (Minimum OT Hrs)
        * @return bolean true / false
        */
        if (strtotime(Core::GET_TIME_TOTAL([self::ReqTimeOut_2(), self::MinReqOTHrs()])) <= strtotime($time)) {
            return true;
        } else {
            return false;
        }
    }

    public static function ValidateLog_OTHrs2(string $time)
    {
        /**
        * @param string "00:00" format
        * Validates a log if it is Over ReqTimeOut_2()(PM time out) + MinReqOTHrs() (Minimum OT Hrs)
        * @return bolean true / false
        */
        if (strtotime(self::ReqTimeOut_2()) <= strtotime($time)) {
            return true;
        } else {
            return false;
        }
    }

    public static function ReqHours()
    {
        /**
        * Returns the time difference between start time and end time in
        * Values must be in float format
        * @return 0.0000...
        */
        $ti = self::ReqTimeIn();
        $to = self::ReqTimeOut();
        $lb = self::get_lunch_break();
        return Core::ToHours(Core::GET_TIME_DIFF($lb, Core::GET_TIME_DIFF($ti, $to)));
    }

    public static function ReqHours2($isGracePeriod = false)
    {
        /**
        * Returns the time difference between start time and end time in "00:00" format
        * Values must be in 24 hours format
        * @return "hh:mm"
        */
        list($ti_h, $ti_m/*, $ti_sec*/) = explode(":", ($isGracePeriod ? self::ReqTimeInWithoutGracePeriod() : self::ReqTimeIn()) );
        list($to_h, $to_m/*, $to_sec*/) = explode(":", self::ReqTimeOut_2());
        list($lb_h, $lb_m/*, $lb_sec*/) = explode(":", self::get_lunch_break());
        $ti_h = (int)$ti_h;
        $ti_m = (int)$ti_m;
        /*$ti_sec = (int)$ti_sec;*/
        $to_h = (int)$to_h;
        $to_m = (int)$to_m;
        /*$to_sec = (int)$to_sec;*/
        $lb_h = (int)$lb_h;
        $lb_m = (int)$lb_m;
        /*$lb_sec = (int)$lb_sec;*/
        
        if ((int)$ti_m > (int)$to_m) {
            $to_h-=1;
            $to_m+=60;
        }
        $min_1 = $to_m - $ti_m;
        $hour_1 = $to_h - $ti_h;

        if ((int)$lb_m > (int)$min_1) {
            $lb_h-=1;
            $lb_m+=60;
        }
        $min_2 = $min_1 - $lb_m;
        $hour_2 = $hour_1 - $lb_h;

        if ($hour_2 < 10) {
            $hour_2 = "0".$hour_2;
        }
        if ($min_2 < 10) {
            $min_2 = "0".$min_2;
        }
        return $hour_2.":".$min_2;
    }

    public static function GetRenHours(string $time_1, string $time_2, $isWholeDay = false)
    {
        /**
        * @param string $time_1 timed in or time start "hh:mm"
        * @param string $time_2 timed out or time end "hh:mm"
        * @param string $type "am" or "pm"
        * Returns the value of required hours in timestamp format "hh:mm"
        * @return "hh:mm"
        */
        $time = Core::GET_TIME_DIFF($time_1, $time_2);
        if ((Core::ToMinutes($time_2) > Core::ToMinutes("12:00")) && $isWholeDay == true) {
            $time = Core::GET_TIME_DIFF(self::get_lunch_break(), $time);
        }
        return $time;
    }

    public static function GetRenHours2(string $time_1, string $time_2)
    {
        /**
        * @param string $time_1 timed in or time start "hh:mm"
        * @param string $time_2 timed out or time end "hh:mm"
        * @param string $type "am" or "pm"
        * Returns the value of required hours in timestamp format "hh:mm"
        * @return "hh:mm"
        */
        return Core::GET_TIME_DIFF_ADD_MINUTE($time_1, $time_2);
    }

    public static function RetrieveRenHours($empid, string $date)
    {
        /**
        * Returns the rendered hours for the given date.
        * Return value is in "hh:mm::ss" format and string data type.
        * @return "hh:mm::ss"
        */
        $r_time = "00:00";
        $date = date('Y-m-d', strtotime($date));
        $record = DB::table('hr_tito2')->distinct('work_date')->where('work_date', '=', $date)->where('empid', $empid)->orderby('work_date', 'ASC')->get();
        if (count($record) > 1) {
            $r_time = Timelog::GetRenHours($record[0]->time_log, $record[1]->time_log);
        }
        return $r_time;
    }

    public static function IfPresent($empid, string $date)
    {
        /**
        * Returns a bolean for the given employee id and date.
        * @return true if there is a time log on the given date.
        * @return false if there are no timelog recorded on the given date.
        */
        $r_time = "00:00";
        $date = date('Y-m-d', strtotime($date));
        $record = DB::table('hr_tito2')->distinct('work_date')->where('work_date', '=', $date)->where('empid', $empid)->orderby('work_date', 'ASC')->get();
        if (count($record) > 1) {
            return true;
        }
        return false;
    }

    public static function IfLeave($empid, string $date)
    {
        /**
        * Returns a bolean for the given employee id and date.
        * @return true if employee is on leave.
        * @return false if employee is not on leave.
        */
        $tmp = Leave::GetLeaveRecord2($empid, $date, true);
        if ($tmp!=null) {
            if ($tmp->leave_pay == "YES") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function IfWeekdays($date)
    {
        /**
        * Returns bolean for date given
        * @return true if weekdays
        * @return false if weekend
        */
        list($year, $month, $day) = explode("-", $date);
        $q_date = Core::GetMonth((int)$month).' '.$day.', '.$year;
        $dayofweek = date('l', strtotime($q_date));
        if ($dayofweek != "Sunday" && $dayofweek != "Saturday") {
            return true;
        }
        return false;
    }

    public static function IfWeekend($date)
    {
        /**
        * Returns bolean for date given
        * @return true if weekend
        * @return false if weekdays
        */
        list($year, $month, $day) = explode("-", $date);
        $q_date = Core::GetMonth((int)$month).' '.$day.', '.$year;
        $dayofweek = date('l', strtotime($q_date));
        if ($dayofweek == "Sunday" || $dayofweek == "Saturday") {
            return true;
        }
        return false;
    }

    public static function IfWorkdays($date)
    {
        /**
        * Mix of IfWeekdays() and IfWeekend() functions
        * Returns bolean for date given
        * @return true if day of week is set to true
        * @return false if day of week is set to false
        */
        list($year, $month, $day) = explode("-", $date);
        $q_date = Core::GetMonth((int)$month).' '.$day.', '.$year;
        $dayofweek = date('l', strtotime($q_date));
        switch ($dayofweek) {
            case 'Sunday':
                return false;
                break;

            case 'Monday':
                return true;
                break;

            case 'Tuesday':
                return true;
                break;

            case 'Wednesday':
                return true;
                break;

            case 'Thursday':
                return true;
                break;

            case 'Friday':
                return true;
                break;

            case 'Saturday':
                return false;
                break;
            
            default:
                return false;
                break;
        }
    }

    public static function IfHoliday(string $date)
    {
        /**
        * Returns bolean for date given
        * @return true if date is holiday
        * @return false if date is not holiday
        */
        $find = DB::table('hr_holidays')->where('cancel', '=', null)->where('date_holiday', '=', date('Y-m-d', strtotime($date)))->first();
        if ($find!=null) {
            return true;
        }
        return false;
    }

    public static function IfLate(string $time)
    {
        /**
        * Returns bolean for time given
        * @return true if time is late
        * @return false if not
        */

        /*
        * reworked for grace period
        */
        $time_in = $time;
        $r_time_in = self::ReqTimeIn();
        if (strtotime($time) > strtotime($r_time_in)) {
            return true;
        }
        return false;

    }

    public static function IfEarlyOut(string $time)
    {
        /**
        * Returns bolean for time given
        * @return true if time is early out
        * @return false if not
        */

        $time_out = $time;
        $r_time_out = self::ReqTimeOut();

        if (strtotime($time_out) < strtotime($r_time_out)) {
            return true;
        }
        return false;
    }


    public static function IfUndertime(string $rendered_time, string $required_time)
    {
        /**
        * Returns bolean for time given
        * @param $rendered_time = 00:00
        * @param $required_time = 00:00
        * @return true if the time is below the required hours
        * @return false if the time is not below or more than the required hours
        */

        // $r_time_in = self::ReqTimeIn();
        // $r_time_out = self::ReqTimeOut();
        // $r_time = Core::GET_TIME_DIFF($r_time_in, $r_time_out);
        // if (Core::ToMinutes($time_1) > Core::ToMinutes($r_time_in)) {
        //     $time_1 = $r_time_in;
        // }
        // if (Core::ToMinutes($time_2) > Core::ToMinutes($r_time_out)) {
        //     $time_2 = $r_time_out;
        // }
        // $time = Core::GET_TIME_DIFF($time_1, $time_2);

        if (Core::ToMinutes($required_time) > Core::ToMinutes($rendered_time)) {
            return true;
        }
        return false;
    }

    public static function toMinuteCustom($time){
        // should be from hour
        list($hour,$minute) = explode(':', $time);
        return ($hour * 60) + $minute;
    }

    public static function toHourCustom($time){
        // should be from hour
        list($hour,$minute) = explode(':', $time);
        return ($minute / 60) + $hour;
    }

    public static function adjustFormattedTimestamp($time,$hourToAdd = 0, $minuteToAdd = 0){
        list($hour,$minute) = explode(':', $time);
        $hour += $hourToAdd;
        $minute += $minuteToAdd;
        $formatted = sprintf("%02d",$hour) . ':' . sprintf("%02d",$minute);
        return $formatted;
    }

    public static function isOT(string $time){
        /**
        * @param string "00:00" format
        * Validates a log if it is Over ReqTimeOut_2()(PM time out) + MinReqOTHrs() (Minimum OT Hrs)
        * @return bolean true / false
        */
        // return [self::toMinuteCustom(self::ReqTimeOut_2()), self::toMinuteCustom($time)];
        return self::toMinuteCustom(self::ReqTimeOut_2()) < self::toMinuteCustom($time);
    }

    public static function isPassedOnRequiredOT(string $rendered_time)
    {
        return self::toMinuteCustom(self::MinReqOTHrs2()) <= self::toMinuteCustom($rendered_time);
    }

    public static function isLate($in,$ampm = 'am', $getResult = false){
        switch ($ampm) {
            case 'pm':
                $toCompare = Timelog::ReqTimeIn_2();
                break;
            
            default:
                $toCompare = Timelog::ReqTimeIn();
                break;
        }
        $booleanReturn = self::toMinuteCustom($in) > self::toMinuteCustom($toCompare);
        if($getResult && $booleanReturn){
            list($hourIn,$minuteIn) = explode(':', $in);
            list($hourCompare,$minuteCompare) = explode(':', $toCompare);
            return [true, (sprintf("%02d", ($hourIn - $hourCompare))).':'.sprintf("%02d", ($minuteIn - $minuteCompare))];
        }
        return $booleanReturn;
    }

    public static function isUndertime(string $out, string $ampm = 'am', $getResult = false){
        if(isset($out)){
            switch ($ampm) {
                case 'pm':
                    $toCompare = Timelog::ReqTimeOut_2();
                    break;
                
                default:
                    $toCompare = Timelog::ReqTimeOut();
                    break;
            }
            $booleanReturn = self::toMinuteCustom($out) < self::toMinuteCustom($toCompare);
            if($getResult && $booleanReturn){
                list($hourOut,$minuteOut) = explode(':', $out);
                list($hourCompare,$minuteCompare) = explode(':', $toCompare);
                return [true, (sprintf("%02d", ($hourCompare - $hourOut) - 1)).':'.(60 - $minuteOut)];
            }
            return $booleanReturn;
        }
    }

    public static function computeForDeduction($am,$pm,$for = 'undertime'){
        $amCompute = $pmCompute = "00:00";
        switch ($for) {
            case 'late':
                $amData = self::isLate($am,'am',true);
                $pmData = self::isLate($pm,'pm',true);
                break;
            
            default:
                $amData = self::isUndertime($am,'am',true);
                $pmData = self::isUndertime($pm,'pm',true);
                break;
        }
        
        if($amData){
            $amCompute = $amData[1];
        }
        if($pmData){
            $pmCompute = $pmData[1];
        }

        return self::computeTimeFormatted($amCompute,$pmCompute);

    }

    public static function computeTimeFormatted($amCompute,$pmCompute){
        $amExplode = list($hourAM,$minuteAM) = explode(":", $amCompute);
        $pmExplode = list($hourPM,$minutePM) = explode(":", $pmCompute);
        $computedMinute =  (($minuteAM + $minutePM) % 60);
        $computedHour = $hourAM + $hourPM + (($minuteAM + $minutePM) / 60);
        return sprintf("%02d",$computedHour).':'.sprintf("%02d",$computedMinute);
    }

    // public static function IfOvertime(string $rendered_time, string $required_time)
    // {
    //     if (Core::ToMinutes($rendered_time) > Core::ToMinutes($required_time)) {
    //         return true;
    //     }
    //     return false;
    // }

    public static function IfOvertime(string $rendered_time)
    {
        if (Core::ToMinutes(self::MinReqOTHrs()) <= Core::ToMinutes($rendered_time)) {
            return true;
        }
        return false;
    }

    /**
     * @param Core::CoveredDates $dates
     *
     * @return int|null
     */
    public static function GetTotalWorkingHours($dates)
    {
        return count($dates) * self::ReqHours();
    }

    /**
     * @param Date
     *
     * @return bool
     */
    public static function IfEmployeeAlreadyOut($empid, $date)
    {
        $in = 0;
        $out = 0;

        $data = DB::table('hr_tito2')->where('work_date', $date)->where('empid', $empid)->get();

        foreach($data as $k => $v) {
            if($v->status == "1") $in++;
            else if ($v->status == "0") $out++;
        }

        return ( !($out > $in) && ($out == $in) );
    }


}
