<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class X08 extends Model
{

    public static $tbl_name = "x08";
    public static $pk = "uid";

    public static function Load_X08()
    {
        return DB::table(self::$tbl_name)->leftjoin('hr_employee','hr_employee.empid',self::$tbl_name.'.'.'empid')->where('approve_disc', '<>', 'n')->select(self::$tbl_name.'.*','hr_employee.empid','hr_employee.lastname','hr_employee.firstname')->get();
    }

    public static function GetAdmins() 
    {
        return DB::table(self::$tbl_name)->where('grp_id', '001')->where('approve_disc', '<>', 'n')->get();
    }

    public static function GetNoAdmin() 
    {
        return DB::table(self::$tbl_name)->where('grp_id', '<>', '001')->where('approve_disc', '<>', 'n')->get();
    }
}
