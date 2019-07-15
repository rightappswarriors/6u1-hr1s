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
        $jtitle_name = "undefined";
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first()->jtitle_name;
        if ($a != null) {
            $jtitle_name = $a;
        }
        return $jtitle_name;
    }

    public static function Get_JobID($postid)
    {
        $jtitle_name = "undefined";
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first()->jt_cn;
        if ($a != null) {
            $jtitle_name = $a;
        }
        return $jtitle_name;
    }

    public static function Get_JobTitleByCN($cn = "")
    {
        $jtitle_name = "undefined";
        $a = DB::table(self::$tbl_name)->where(self::$newpk, $postid)->first()->jt_cn;
        if ($a != null) {
            $jtitle_name = $a;
        }
        return $jtitle_name;
    }

}
