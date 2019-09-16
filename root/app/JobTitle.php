<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobTitle extends Model
{
    public static $tbl_name = "hr_jobtitle";
    public static $pk = "jtid";
    public static $newpk = "jt_cn";

    public static function Load_JobTitles()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    public static function Get_JobTitle($postid)
    {
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first();
        if ($a != null) {
            $return_val = $a->jtitle_name;
        } else {
            $return_val = null;
        }
        return $return_val;
    }

    public static function Get_JobID($postid)
    {
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first();
        if ($a != null) {
            $return_val = $a->jt_cn;
        } else {
            $return_val = null;
        }
        return $return_val;
    }

    public static function Get_JobTitleByCN($cn = "")
    {
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first();
        if ($a != null) {
            $return_val = $a->jt_cn;
        } else {
            $return_val = null;
        }
        return $return_val;
    }

}
