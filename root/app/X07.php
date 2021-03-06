<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class X07 extends Model
{

    public static $tbl_name = "x07";
    public static $pk = "grp_id";

    public static function Load_X07()
    {
        return DB::table(self::$tbl_name)->where('grp_id', '<>', '001')->where('cancel', null)->orderBy('grp_id', 'ASC')->get();
    }

    public static function Load_ALL_X07()
    {
        return DB::table(self::$tbl_name)->where('cancel', null)->orderBy('grp_id', 'ASC')->get();
    }

    public static function GetGroup($id) 
    {
        return DB::table(self::$tbl_name)->where(self::$pk, $id)->first();
    }
}
