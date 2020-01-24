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


class PagibigContributionsController extends Controller
{
    public function __construct()
    {
        $this->ghistory = DB::table('hr_emp_payroll2')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'DESC')->get();
        $this->table = Core::InformationSchema_Columns('hr_emp_payroll2'); 
        $this->office = Office::get_all();
    }
    public function view(){
        $data = [$this->office];
        return view('pages.reports.pagibig', compact('data'));    
    }
    public function findPayrollPeriod(request $request)
    {
        $ofc_id = $request->ofc_id;
        
        $sql = DB::select("SELECT DISTINCT pp.date_from, pp.date_to FROM (SELECT empid, department FROM hris.hr_employee WHERE cancel is null AND department = '$ofc_id') emp LEFT JOIN (SELECT * FROM hris.hr_emp_payroll3) pp ON emp.empid = pp.empid WHERE pp.date_from IS NOT NULL AND pp.date_to IS NOT NULL");

        return $sql;
    }

    public function find(Request $request)
    {
        $ofc_id = $request->ofc_id;
        $pp = $request->pp;
        $sql = DB::select("SELECT emp.*, pp.* FROM (SELECT empid, department, sss, pagibig, philhealth, firstname, lastname, mi FROM hris.hr_employee WHERE cancel is null AND department = '$ofc_id') emp LEFT JOIN (SELECT * FROM hris.hr_emp_payroll3 WHERE date_from BETWEEN '$pp[0]' AND '$pp[1]') pp ON emp.empid = pp.empid");
        $m99 = DB::select("SELECT comp_name FROM rssys.m99");
        $return = [$m99, $sql];
        return $return;
    }

    public function print(Request $request){
        // $arrRet = [];
        // $ofc_id = $request->ofc_id;
        // $employee = Employee::getEmployeeOffice($ofc_id);

        // foreach($employee as $e){
    
        // array_push($arrRet, [$e,DB::select("SELECT s_ec, empshare_sc, empshare_ec  FROM hris.hr_sss WHERE bracket1 >= '$e->pay_rate' AND cancel is null ORDER BY bracket1 ASC LIMIT 1"), DB::select("SELECT cc_desc FROM rssys.m08 WHERE cc_id = '$ofc_id' "), DB::select("SELECT * FROM hris.m99")] );
        // }
        
        $ofc_id = $request->ofc_id;
        $pp = $request->pp;
        $m99 = DB::select('SELECT * FROM hris.m99');
        $sql = DB::select("SELECT emp.*, pp.* FROM (SELECT empid, department, sss, pagibig, philhealth, firstname, lastname, mi FROM hris.hr_employee WHERE cancel is null AND department = '$ofc_id') emp LEFT JOIN (SELECT * FROM hris.hr_emp_payroll3 WHERE date_from BETWEEN '$pp[0]' AND '$pp[1]') pp ON emp.empid = pp.empid");
        return view('print.reports.print_pagibig_contributions', compact('sql', 'm99'));
    }
}