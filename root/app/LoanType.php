<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Pagibig;

class LoanType extends Model
{
    public static $tbl_name = "hr_loan_type";
    public static $pk = "code";
    public static $cancel = "cancel";

    public static function Load_LoanTypes()
    {
    	try {
    		return DB::table(self::$tbl_name)->where(self::$cancel, '=', null)->get();
    	} catch (Exception $e) {
    		return $e;
    	}
    }

    public static function Get_LoanType($id, $sub)
    {
    	try {
            $data = DB::table(self::$tbl_name)/*->where(self::$cancel, '=', null)*/->where('code', $id)->first()/*->description*/;
            if($data != null) {
                return $data->description;
            } else {
                return Pagibig::Get_Sub_ById($sub)->description;
            }
    	} catch (Exception $e) {
    		return $e;
    	}
    }
}
