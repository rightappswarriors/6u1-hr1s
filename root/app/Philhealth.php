<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class Philhealth extends Model
{
    public static $tbl_name = "hr_philhealth";
    public static $pk = "code";

    public static function Get_PhilHealth_Deduction($amt)
    {
    	try {
            $sql = "SELECT * FROM hris.hr_philhealth WHERE CANCEL IS NULL ";
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
