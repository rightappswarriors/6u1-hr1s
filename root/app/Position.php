<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class Position extends Model
{

    public static $tbl_name = "hr_position";
    public static $pk = "postid";

    public static function Load_Position()
    {
    	return DB::table(self::$tbl_name)->get();
    }

    public static function Get_Position($postid)
    {
        return DB::table(self::$tbl_name)->where(self::$pk, $postid)->first()->position_name;
    }
}
