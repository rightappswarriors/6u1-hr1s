<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use JobTitle;

class Pagibig extends Model
{
    public static $tbl_name_sub = "hr_pagibig_sub";
    public static $pk = "id";

    public static function Get_All_Sub()
    {
    	return DB::table(self::$tbl_name_sub)->where('cancel', null)->get();
    }

    public static function Get_Sub_ById($id)
    {
    	return DB::table(self::$tbl_name_sub)->where('cancel', null)->where(self::$pk, $id)->first();
    }

    public static function Add($data) {
        DB::table(self::$tbl_name_sub)->insert($data);
    }

    public static function Edit($id, $data) {
        DB::table(self::$tbl_name_sub)->where(self::$pk, $id)->update($data);
    }

    public static function Del($id, $data) {
        DB::table(self::$tbl_name_sub)->where(self::$pk, $id)->update($data);
    }
}
