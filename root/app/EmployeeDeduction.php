<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Core;

class EmployeeDeduction extends Model
{
	public static $tbl_name = "hr_deduction_entry";
    public static $pk = "ntf_id";
}
