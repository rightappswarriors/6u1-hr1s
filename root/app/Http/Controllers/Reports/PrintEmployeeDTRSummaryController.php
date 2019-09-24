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

// NEW imports //
use DTR;
// NEW imports //

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - PEDSC
|
| 00000 - find
| 
|--------------------------------------------------------------------------
*/

class PrintEmployeeDTRSummaryController extends Controller
{
    public function __construct()
    {
    	$this->employee = Employee::Load_Employees();
        $this->ghistory = DB::table('hr_tito2')->where('cancel', '=', null)->orderBy('work_date', 'ASC')->orderBy('time_log', 'DESC')->take(6)->get();
    }

    ////////////////////////////////////////////////////////// NEW //////////////////////////////////////////////////////////
    public function view2()
    {
        $data = [
            array(),
            Office::get_all()
        ];
        // dd($data);
        return view('pages.reports.timekeeping_new.print_employee_dtr_summary', compact('data'));
    }
    ////////////////////////////////////////////////////////// NEW //////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////// OLD //////////////////////////////////////////////////////////
    public function view()
    {
        $data = [$this->employee, Office::get_all()];
        // dd($data);
        return view('pages.reports.print_employee_dtr_summary', compact('data'));
    }

    /**
    * @param Request 
    *           -> all(); (array) [month, year, period]
    * @return Array
    *           -> [[[employees]], Holiday::GetMonth($r->month), r->year, [date_readable], [day_of_the_week], [[dates_rendered]], 
    *                   [[employee_full_data]], xx, yy]
    * --------------------------------------
    * This function will find employees' timelogs with selected month, year and payroll period
    */
    public function find(Request $r)
    {
        // return Office::OfficeEmployees($r->office);
        try {
            $number_of_days = cal_days_in_month(CAL_GREGORIAN, $r->month, $r->year);

            $day_of_the_week = array();
            $date_readable = array();
            $employees = Employee::Load_Employees_Simple($r->office);
            // return $employees;
            $dates = Payroll::PayrollPeriod2($r->month, $r->period.'D', $r->year);
            $days_worked = Core::CoveredDates($dates->from, $dates->to);
            $dates_rendered = array();

            $employee_total_days_rendered = array();
            $employee_total_late = array();
            $employee_total_undertime = array();
            $employee_total_overtime = array();

            $employee_full_data;

            for($i=0; $i < count($employees); $i++) {
                $dates_rendered[$i] = Timelog::getWorkdates($employees[$i][0], $dates->from, $dates->to);
                $days_rendered = 0;
                $days_late = 0;
                $days_undertime = 0;

                $hours_late = array();
                $hours_undertime = array();

                $hours_overtime_in = array();
                $hours_overtime_out = array();

                for($j=0; $j<count($dates_rendered[$i]); $j++) {
                    if($dates_rendered[$i][$j]->status == "1") {
                        $days_rendered++;

                        if(Timelog::IfLate($dates_rendered[$i][$j]->time_log) 
                            && intval(explode(":",$dates_rendered[$i][$j]->time_log)[0]) < 
                            intval(explode(":",Timelog::ReqTimeOut())[0]) ) {

                            $hours_late[$j] = Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $dates_rendered[$i][$j]->time_log);
                            
                        }

                        if(intval(explode(":",$dates_rendered[$i][$j]->time_log)[0]) >= intval(explode(":",Timelog::ReqTimeOut())[0])) { // if overtime
                            dd($dates_rendered);
                            if($dates_rendered[$i][$j+1] != null || $dates_rendered[$i][$j] != undefined) {
                                if($dates_rendered[$i][$j+1]->status=="0")
                                    $hours_overtime_in[$j] = Core::GET_TIME_DIFF($dates_rendered[$i][$j]->time_log,$dates_rendered[$i][$j+1]->time_log);
                            }
                        }

                    } else if($dates_rendered[$i][$j]->status == "0") {
        
                        if(Timelog::IfEarlyOut($dates_rendered[$i][$j]->time_log)) {

                            $hours_undertime[$j] = Core::GET_TIME_DIFF($dates_rendered[$i][$j]->time_log, Timelog::ReqTimeOut());
                        }
                    }
                }

                $employee_total_days_rendered[$i] = $days_rendered;
                // $employee_total_late[$i] = $days_late;
                $employee_total_late[$i] = Core::GET_TIME_TOTAL($hours_late);
                // $employee_total_undertime[$i] = $days_undertime;
                $employee_total_undertime[$i] = Core::GET_TIME_TOTAL($hours_undertime);
                $employee_total_overtime[$i] = Core::GET_TIME_TOTAL($hours_overtime_in);
            }

            $employee_full_data = [$employee_total_days_rendered, $employee_total_late, $employee_total_undertime, $employee_total_overtime];


            foreach($days_worked as $k => $v) {
                $days_worked[$k] = date('Y-m-d', strtotime($days_worked[$k]));

                $day_of_the_week[$k] = date('l', strtotime($days_worked[$k]));

                $date_readable[$k] = \Carbon\Carbon::parse($days_worked[$k])->format('M d, Y');
            }

            for($i=0; $i<count($day_of_the_week); $i++) {
                if($day_of_the_week[$i] == "Saturday") {
                    array_splice($day_of_the_week, $i, 1);
                    array_splice($date_readable, $i, 1);
                    array_splice($days_worked, $i, 1);
                }
            }

            for($i=0; $i<count($day_of_the_week); $i++) {
                if($day_of_the_week[$i] == "Sunday") {
                    array_splice($day_of_the_week, $i, 1);
                    array_splice($date_readable, $i, 1);
                    array_splice($days_worked, $i, 1);
                }
            }

            $xx = TimeLog::GetTotalWorkingHours($days_worked);
            $yy = 0;

            for($i=0; $i<count($days_worked); $i++) {
                if($days_worked[$i] > date("Y-m-d") ) {
                    break;
                }
                $yy++;
            }

            $data = [$employees, Holiday::GetMonth($r->month), $r->year, $date_readable, $day_of_the_week, $dates_rendered, $employee_full_data, $xx, $yy];
            return $data;
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage()); 
            ErrorCode::Generate('controller', 'PrintEmployeeDTRSummaryController', '00000', $e->getMessage());
            return back();
        } 
    }
    ////////////////////////////////////////////////////////// OLD //////////////////////////////////////////////////////////
}