<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Office extends Model
{
    public static $tbl_name = "rssys.m08";
    public static $pk = "cc_code";
    public static $oid = 'oid';

    public static function GetOffice($val)
    {
    	return DB::table($this->tbl_name)->where($this->oid, '=', $val)->first();
    }
}
