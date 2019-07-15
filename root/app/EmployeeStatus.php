<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class EmployeeStatus extends Model
{
    public static $tbl_name = "hr_emp_status";
    public static $pk = "statcode";
    public static $oid = "oid";

}
