<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class Philhealth extends Model
{
    public static $tbl_name = "hr_philhealth";
    public static $pk = "code";

    // public static function Get_PhilHealth_Deduction($amt)
    // {
    // 	try {
    //         $sql = "SELECT * FROM hris.hr_philhealth WHERE CANCEL IS NULL ";
    //         $con = "AND bracket1 <= ".$amt." AND bracket2 > ".$amt." LIMIT 1";
    //         $result = Core::sql($sql.$con);
    //         if (count($result) > 0) {
    //             return $result[0];
    //         } else {
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }

    public static function Get_PhilHealth_Deduction($amt)
    {
        try {
            $m99 = (Core::getm99One('philhealth_prctg')->philhealth_prctg ?? 3.00);
            $min = $m99  * 100;
            $max = ($m99 * 10) * (Core::getm99One('philhealth_max')->philhealth_max ?? 6) * 10;
            $deducted = $amt * ($m99 / 100);
            $deducted = (($deducted > $max) ? $max : (($deducted < $min) ? $min : $deducted)) / 2;
            $objectToReturn = [];
            $objectToReturn['code'] = null;
            $objectToReturn['emp_ee'] = $deducted;
            $objectToReturn['emp_er'] = $deducted;
            return (object)$objectToReturn;
        } catch (\Exception $e) {
            return null;
        }
    }
}
