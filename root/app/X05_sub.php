<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class X05_sub extends Model
{
    public static $tbl_name = "x05_sub";
    public static $pk = "id";

    public static function Load_All()
    {
        return DB::table(self::$tbl_name)->where('cancel', null)->get();
    }
}
