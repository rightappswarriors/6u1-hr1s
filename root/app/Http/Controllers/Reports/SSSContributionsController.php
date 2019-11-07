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
        $arrRet = [];
        $ofc_id = $request->ofc_id;
        $employee = Employee::getEmployeeOffice($ofc_id);
        foreach($employee as $e){
            array_push($arrRet, [$e,DB::select("SELECT s_ec, empshare_sc, empshare_ec  FROM hris.hr_sss WHERE bracket1 >= '$e->pay_rate' AND cancel is null ORDER BY bracket1 ASC LIMIT 1")]);
        }
        return $arrRet;

    }

    public function print(Request $request){
        $arrRet = [];
        $ofc_id = $request->ofc_id;
        $employee = Employee::getEmployeeOffice($ofc_id);
        foreach($employee as $e){
    
        array_push($arrRet, [$e,DB::select("SELECT s_ec, empshare_sc, empshare_ec  FROM hris.hr_sss WHERE bracket1 >= '$e->pay_rate' AND cancel is null ORDER BY bracket1 ASC LIMIT 1"), DB::select("SELECT cc_desc FROM rssys.m08 WHERE cc_id = '$ofc_id' "), DB::select("SELECT * FROM hris.m99")] );
        }
       
        return view('print.reports.print_sss_contributions', compact('arrRet'));
    }
}