<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Department extends Model
{
    public static $tbl_name = "hr_department";
    public static $pk = "deptid";

}
