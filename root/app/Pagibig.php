<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use JobTitle;
use Core;

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

    public static function Get_PagIbig_Deduction($amt)
    {
        try {
            $sql = "SELECT * FROM hris.hr_hdmf WHERE CANCEL IS NULL ";
            $con = "AND bracket1 <= ".$amt." AND bracket2 > ".$amt." LIMIT 1";
            $result = Core::sql($sql.$con);
            if (count($result) > 0) {
                return $result[0];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
