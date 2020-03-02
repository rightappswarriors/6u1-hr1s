<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class SSS extends Model
{
    public static $tbl_name = "hr_sss";
    public static $pk = "code";

    public static $tbl_name_sub = "hr_sss_sub";
    public static $sub_pk = "id";

    public static function Get_All_Sub()
    {
    	return DB::table(self::$tbl_name_sub)->where('cancel', null)->orderBy('id','ASC')->get();
    }

    public static function Get_Sub_ById($id)
    {
    	return DB::table(self::$tbl_name_sub)->where('cancel', null)->where(self::$sub_pk, $id)->first();
    }

    public static function Add($data) {
        DB::table(self::$tbl_name_sub)->insert($data);
    }

    public static function Edit($id, $data) {
        DB::table(self::$tbl_name_sub)->where(self::$sub_pk, $id)->update($data);
    }

    public static function Del($id, $data) {
        DB::table(self::$tbl_name_sub)->where(self::$sub_pk, $id)->update($data);
    }

    public static function Get_SSS_Deduction($amt)
    {
        try {
            $sql = "SELECT * FROM hris.hr_sss WHERE CANCEL IS NULL ";
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
