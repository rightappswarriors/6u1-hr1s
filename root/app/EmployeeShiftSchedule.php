<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeShiftSchedule extends Model
{
    public static $tbl_name = "hr_emp_shift";
    public static $pk = "esid";

}
