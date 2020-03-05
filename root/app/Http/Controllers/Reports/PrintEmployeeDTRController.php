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
use DateTime;
use Office;

// NEW imports //
use DTR;
// NEW imports //

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - PEDC
|
| 00000 - find
| 00001 - find2
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

    ////////////////////////////////////////////////////////// NEW //////////////////////////////////////////////////////////
    /*
    * New functions for the new timekeeping reports blade, but is currently unused because
    */
    public function view2()
    {
        $data = [
            DTR::GetAllHDRPeriods(),
            Office::get_all()
        ];
        // dd($data);
        return view('pages.reports.timekeeping_new.print_employee_dtr', compact('data'));
    }

    public function generateEmployee(Request $r){
        $gentype = $r->gentype;
        $datefrom = $r->from;
        $dateto = $r->to;

        $generatedEmployee = Employee::getGeneratedEmployee($gentype, $datefrom, $dateto);

        return $generatedEmployee;

    }

    public function findnew(Request $r)
    {
        // $data = DTR::GetAllHDRSummaryByCode($r->code);
        $data = DTR::GetAllHDRSummaryByDate($r->code, $r->type);
        // dd($data);
        foreach($data as $k => $v) {
            $v->employee_readable = Employee::Name($v->empid);
        }
        return $data;
    }

    public function findnew2(Request $r)
    {
        // $data = DTR::GetAllHDRSummaryByCode($r->code);
        $data = DTR::GetAllHDRSummaryByDateWithEmployee($r->code, $r->type, $r->emp);
        foreach($data as $k => $v) {
            $v->employee_readable = Employee::Name($v->empid);
            $v->date_from_readable = \Carbon\Carbon::parse($v->date_from)->format('M d, Y');
            $v->date_to_readable = \Carbon\Carbon::parse($v->date_to)->format('M d, Y');
            $v->covered_dates = Core::CoveredDates(Date('Y-m-01',strtotime($v->date_from)), Date('Y-m-t',strtotime($v->date_to)));

            for($i=0; $i<count($v->covered_dates); $i++) {
                $v->covered_dates[$i] = [Date('j',strtotime($v->covered_dates[$i])), date('Y-m-d', strtotime($v->covered_dates[$i]))];
            }
        }
        return $data;
    }

    public function getperiods(Request $r)
    {
        // return DTR::GetAllHDRPeriods();
        return DTR::GetAllHDRSumarryByOffice($r->office);
    }
    ////////////////////////////////////////////////////////// NEW //////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////// OLD //////////////////////////////////////////////////////////

    public function view()
    {
        $data = [$this->employee, Office::get_all()];
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
    * --------------------------------------
    * PLEASE REFER TO `function find2` as the current find function for this module
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

    /**
    * @param Request
    *           -> all(); (array) [empid, month, year, period];
    *
    * @return Array
    *           -> [
    *               0: <array> => [
    *                               <index>: <object> => {
    *                                                       AM: <object> => {
    *                                                                           Arrival: <string>,
    *                                                                           Departure: <string>,
    *                                                                       },
    *                                                       PM: <object> => {
    *                                                                           Arrival: <string>,
    *                                                                           Departure: <string>,
    *                                                                       },
    *                                                       _Date: <string>,
    *                                                       _Rendered: <number>,
    *                                                    },
    *                             ],
    *               1 <object> => {
    *                               HoursRendered: <number>,
    *                               Month: <string>,
    *                               Name: <string>,
    *                               Year: <string>,
    *                             },
    *              ]
    */
    public function find2(Request $r)
    {
        try {
            $payroll_period = Payroll::PayrollPeriod2($r->month, $r->period.'D', $r->year);
            $covered_dates = Core::CoveredDates($payroll_period->from, $payroll_period->to);

            $dataIn1 = array();
            $dataOut2 = array();
            $covered_dates_strtotime = array();

            $data = array();

            $external_data = [
                "Name" => Employee::Name($r->empid),
                "Month" => Holiday::GetMonth($r->month),
                "Year" => $r->year,
                "HoursRequired" => intval(explode(':', Timelog::ReqTimeOut())[0]) - intval(explode(':', Timelog::ReqTimeIn())[0]) - 1,
            ];

            foreach($covered_dates as $k => $v) {
                $covered_dates_strtotime[] = date('Y-m-d', strtotime($covered_dates[$k]));

                if( date('l', strtotime($covered_dates_strtotime[$k])) != "Sunday" ) {
                    if( date('l', strtotime($covered_dates_strtotime[$k])) != "Saturday" ) {
                        $dataIn1[$k] = DB::table('hr_tito2')->where('empid', $r->empid)->where('status', '1')->where('work_date', $covered_dates_strtotime[$k])->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                        $dataOut2[$k] = DB::table('hr_tito2')->where('empid', $r->empid)->where('status', '0')->where('work_date', $covered_dates_strtotime[$k])->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                        $data[$k]['_Date'] = $covered_dates[$k];

                        /* CALC */
                        if($dataIn1[$k] != null) {

                            if( $dataIn1[$k]->time_log < substr(Timelog::ReqTimeOut_2(), 0, 5) ) {
                                /* AM */

                                $data[$k]['AM']['Arrival'] = $dataIn1[$k]->time_log;

                                if( $dataOut2[$k] != null ) {
                                    if( ($dataOut2[$k]->time_log > substr(Timelog::ReqTimeOut_2(), 0, 5)) ) {
                                        $data[$k]['AM']['Departure'] = /*"12:00pm"*/"12:00";

                                        $data[$k]['PM']['Arrival'] = /*"1:00pm"*/"13:00";
                                        $data[$k]['PM']['Departure'] = $dataOut2[$k]->time_log;
                                    } else {
                                        $data[$k]['AM']['Departure'] = $dataOut2[$k]->time_log;

                                        $data[$k]['PM']['Arrival'] = "";
                                        $data[$k]['PM']['Departure'] = "";
                                    }
                                } else {
                                    $data[$k]['AM']['Departure'] = "<span class='text-danger'>missing</span>";

                                    $data[$k]['PM']['Arrival'] = "";
                                    $data[$k]['PM']['Departure'] = "";
                                }

                            } else if( $dataIn1[$k]->time_log >= substr(Timelog::ReqTimeOut_2(), 0, 5) ) {
                                /* PM */

                                $data[$k]['AM']['Arrival'] = "";
                                $data[$k]['AM']['Departure'] = "";

                                $data[$k]['PM']['Arrival'] = $dataIn1[$k]->time_log;
                                if( $dataOut2[$k] != null ) {
                                    $data[$k]['PM']['Departure'] = $dataOut2[$k]->time_log;
                                } else {
                                    $data[$k]['PM']['Departure'] = "<span class='text-danger'>missing</span>";
                                }
                            }


                            if($dataOut2[$k] != null) {
                                $in = new DateTime($dataIn1[$k]->time_log);
                                $out = new DateTime($dataOut2[$k]->time_log);

                                $diff = date_diff($in, $out);
                                $diff->h -= 1;

                                $minutes_required = ((intval(explode(":", Timelog::ReqTimeOut())[0]) - intval(explode(":", Timelog::ReqTimeIn())[0])) - 1) * 60;

                                $minutes_rendered = $diff->h * 60;
                                $minutes_rendered += $diff->i;



                                $data[$k]['_Rendered'] = ($minutes_rendered >= $minutes_required)?0:$minutes_required-$minutes_rendered;
                                ;

                                // $data[$k]['_Rendered'] = ($dataIn1[$k]->time_log < intval(explode(":", Timelog::ReqTimeOut())[0]) && $dataOut2[$k]->time_log > intval(explode(":", Timelog::ReqTimeIn_2())[0]))?$data[$k]['_Rendered']-60:$data[$k]['_Rendered'];

                                $data[$k]['_Rendered'] = ($data[$k]['_Rendered'] < 0)?0:$data[$k]['_Rendered'];
                            } else {
                                $data[$k]['_Rendered'] = "";
                            }

                        } else {
                            $data[$k]['AM']['Arrival'] = "";
                            $data[$k]['AM']['Departure'] = "";
                            $data[$k]['PM']['Arrival'] = "";
                            $data[$k]['PM']['Departure'] = "";

                            $data[$k]['_Rendered'] = "";
                        }
                    }
                } 
            }

            $data = array_values($data);

            return [$data, $external_data];

        } catch (Exception $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'PrintEmployeeDTRController', '00001', $e->getMessage());
            return back();
        }
    }
    ////////////////////////////////////////////////////////// OLD //////////////////////////////////////////////////////////
}