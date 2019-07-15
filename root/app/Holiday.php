<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Holiday extends Model
{
    public static $tbl_name = "hr_holidays";
    public static $pk = "id";

    public static function Load_Holidays()
    {
        return DB::table(self::$tbl_name)->where('cancel', '=', null)->get();
    }

    public static function Load_Holidays_Deleted()
    {
        return DB::table(self::$tbl_name)->where('cancel', '<>', null)->get();
    }

    public static function Get_Holiday_Color($code) {
    	$color_in_hex = "";

    	switch($code) {
    		case "RH": $color_in_hex = "#3a4"; break;
    		case "SH": $color_in_hex = "#f00"; break;/*48b*/
    	}

    	return $color_in_hex."^".$code;
    }

    public static function HolidayType($id)
    {
        /*
        | Returns Holiday Type
        */
        try {
            $day = DB::table(self::$tbl_name)->where('cancel', '=', null)->where('id', '=', $id)->first();
            if ($day == null) {
                return null;
            }
            return $day->holiday_type;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function FindHoliday(string $date)
    {
        try {
            $day = DB::table(self::$tbl_name)->where('cancel', '=', null)->where('date_holiday', '=', date('Y-m-d', strtotime($date)))->first();
            if ($day == null) {
                return null;
            }
            return $day;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function HolidayPercentage($htype)
    {
        $data = (object) [];
        switch ($htype) {
            case 'RH':
                $data->nowork = 100;
                $data->work = 200;
                $data->ot = 30;
                $data->rd_work = 30;
                $data->rd_ot = 30;

                break;

            case 'SH':
                $data->nowork = 0;
                $data->work = 30;
                $data->ot = 30;
                $data->rd_work = 0;
                $data->rd_ot = 0;
                break;
            
            default:
                $data->nowork = 0;
                $data->work = 0;
                $data->ot = 0;
                $data->rd_work = 0;
                $data->rd_ot = 0;
                break;
        }

        return $data;
    }

    public static function GetMonth($number)
    {
        $data = 0;
        switch($number) {
            case 1: $data = "January"; break;
            case 2: $data = "February"; break;
            case 3: $data = "March"; break;
            case 4: $data = "April"; break;
            case 5: $data = "May"; break;
            case 6: $data = "June"; break;
            case 7: $data = "July"; break;
            case 8: $data = "August"; break;
            case 9: $data = "September"; break;
            case 10: $data = "October"; break;
            case 11: $data = "November"; break;
            case 12: $data = "December"; break;
            default: $data = "Undefined"; break;
        }
        return $data;
    }

    public static function GetUpcomingHoliday()
    {
        try {
            $day = DB::table(self::$tbl_name)->where('cancel', '=', null)->whereDate('date_holiday', '>', date('Y-m-d'))->first();
            if ($day == null) {
                return null;
            }
            return $day;
        } catch (\Exception $e) {
            return null;
        }
    }
}
