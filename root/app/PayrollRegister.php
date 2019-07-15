<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PayrollRegister extends Model
{
    public static $tbl_name = "hr_payroll_register";
    public static $pk = "payrollreg_code";

}
