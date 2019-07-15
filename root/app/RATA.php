<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class RATA extends Model
{
	// Load all loans

    public static $tbl_name = "hr_rata";
    public static $pk = "rata_id";

    public static function Load_RATA()
    {
    	return DB::table(self::$tbl_name)->get();
    }
}