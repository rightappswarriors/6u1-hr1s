<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Employee;
use ErrorCode;
use Holiday;
use Payroll;
use Timelog;
use Office;
use DTR;


class SSSContributionsController extends Controller
{
    public function __construct()
    {
        $this->ghistory = DB::table('hr_emp_payroll2')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'DESC')->get();
        $this->table = Core::InformationSchema_Columns('hr_emp_payroll2'); 
        $this->office = Office::get_all();
    }
    public function view(){
        $data = [$this->office];
        return view('pages.reports.sss', compact('data'));   
    }
    public function find(Request $request){
        $ofc_id = $request->ofc_id;
        $employee = Employee::getEmployeeOffice($ofc_id);
    
        return $employee;

    }
}