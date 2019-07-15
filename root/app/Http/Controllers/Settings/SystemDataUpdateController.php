<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use PayrollPeriod;
use Employee;
use Timelog;
use Account;

class SystemDataUpdateController extends Controller
{
    public function __construct()
    {
    	// $this->ghistory = DB::table('hr_leaves')->where('cancel', '=', null)->orderBy('d_filed', 'DESC')->orderBy('leave_from', 'DESC')->get();
        $this->employees = Employee::Load_Employees();

    }

    public function view()
    {
    	$data = [$this->employees];
    	return view('pages.settings.system_data_update', compact('data'));
    }
} 