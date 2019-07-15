<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class X05 extends Model
{
    public static $tbl_name = "x05";
    public static $pk = "mod_id";

    public static function Load_X05()
    {
        return DB::table(self::$tbl_name)->get();
    }

    public static function Get_Module_Parents()
    {
        try {
            return DB::table(self::$tbl_name)->where('level', 1)->orderBy('position', 'ASC')->get();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
