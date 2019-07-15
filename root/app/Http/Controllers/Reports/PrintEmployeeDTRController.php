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

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - PEDC
|
| 00000 - find
| 
|--------------------------------------------------------------------------
*/

class PrintEmployeeDTRController extends Controller
{
    public function __construct()
    {
    	$this->employee = Employee::Load_Employees();
        $this->ghistory = DB::table('hr_tito2')->where('cancel', '=', null)->orderBy('work_date', 'ASC')->orderBy('time_log', 'DESC')->take(6)->get();
    }

    public function view()
    {
        $data = [$this->employee, $this->ghistory];
        // dd($data);
        return view('pages.reports.print_employee_dtr', compact('data'));
    }

    /**
    * @param Request 
    *           -> all(); (array) [empid, month, year, period]
    * @return Array
    *           -> [[dataIn1], [dataOut2], empName, Holiday::GetMonth($r->month), r->year, [date_readable], [day_of_the_week], r->period, 
    *                   [days_worked]]
    * --------------------------------------
    * This function will find employee's timelogs with selected employee, month, year and payroll period
    */
    public function find(Request $r)
    {
        try {
            $number_of_days = cal_days_in_month(CAL_GREGORIAN, $r->month, $r->year);

            $dataIn1 = array();
            $dataOut2 = array();
            $day_of_the_week = array();
            $date_readable = array();

            $dates = Payroll::PayrollPeriod2($r->month, $r->period.'D', $r->year);
            $days_worked = Core::CoveredDates($dates->from, $dates->to);
            foreach($days_worked as $k => $v) {
                $days_worked[$k] = date('Y-m-d', strtotime($days_worked[$k]));

                $day_of_the_week[$k] = date('l', strtotime($days_worked[$k]));

                $date_readable[$k] = \Carbon\Carbon::parse($days_worked[$k])->format('M d, Y');
            }

            for($i = 0; $i < count($days_worked); $i++) {
                $dataIn1[$i] = DB::table('hr_tito2')->where('empid', $r->empid)->where('status', '1')->where('work_date', $days_worked[$i])->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                $dataOut2[$i] = DB::table('hr_tito2')->where('empid', $r->empid)->where('status', '0')->where('work_date', $days_worked[$i])->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();
            }

            $empName = Employee::Name($r->empid);

            $data = [$dataIn1, $dataOut2, $empName, Holiday::GetMonth($r->month), $r->year, $date_readable, $day_of_the_week, $r->period, $days_worked];
            return $data;
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'PrintEmployeeDTRController', '00000', $e->getMessage());
            return back();
        } 
    }
}