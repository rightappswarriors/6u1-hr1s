<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SSS extends Model
{
    public static $tbl_name = "hr_sss";
    public static $pk = "code";

    public static $tbl_name_sub = "hr_sss_sub";
    public static $sub_pk = "id";

    public static function Get_All_Sub()
    {
    	return DB::table(self::$tbl_name_sub)->where('cancel', null)->get();
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
}
