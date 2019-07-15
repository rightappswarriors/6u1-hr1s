<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ShiftSchedule extends Model
{
    public static $tbl_name = "hr_shift_schedule";
    public static $pk = "code";


    public static function GetShiftSched($mode, $id)
    {
    	// $db = DB::table($this->tbl_name)->where($id)
    	switch ($mode) {
    		case 'in':
    			# code...
    			break;

    		case 'out':
    			# code...
    			break;
    		
    		default:
    			# code...
    			break;
    	}
    }
}
