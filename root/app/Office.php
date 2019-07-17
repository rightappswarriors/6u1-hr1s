<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Office extends Model
{
    public static $tbl_name = "rssys.m08";
    public static $pk = "cc_code";
    public static $oid = 'oid';

    public static function get_all()
    {
    	return DB::table(self::$tbl_name)->select('oid', 'cc_code', 'cc_desc')->where('active', '=', 't')->orderBy('cc_desc', 'ASC')->get();
    }

    public static function GetOffice($val)
    {
    	return DB::table(self::$tbl_name)->where(self::$oid, '=', $val)->first();
    }
}
