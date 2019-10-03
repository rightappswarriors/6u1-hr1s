<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Employee;
use ErrorCode;
use Holiday;
use DateTime;
use Office;
use Timelog;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - PDTRC
|
| 00000 - find
| 00001 - find2
| 
|--------------------------------------------------------------------------
*/

class PrintDailyTimelogRecordController extends Controller
{
    public function view()
    {
        $data = [[], Office::get_all()];
        return view('pages.reports.print_daily_timelog_record', compact('data'));
    }

    public function print()
    {
        return view('print.reports.timekeeping.print_daily_timelog_record_p');
    }

    /**
    * @param Request 
    *           -> all(); (array) [date_sent]
    * @return Array
    *           -> [[Date], [Employee::Load_Employees_Simple], [timein_timeout]]
    * --------------------------------------
    * This function will find employees' timelogs with specif date
    * --------------------------------------
    * PLEASE REFER TO `function find2` as the current find function for this module
    */
    public function find(Request $r)
    {
        try {
            // $date = explode("-",$r->date_sent);
            // $date = $date[0].'-'.$date[0].'-'.$date[1];
            $date_readable = \Carbon\Carbon::parse($r->date_sent)->format('M d, Y');

            $employees = Employee::Load_Employees_Simple($r->office);

            $dataIn1 = array();
            $dataOut2 = array();


            for($i = 0; $i < count($employees); $i++) {
                $dataIn1[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '1')->where('work_date', $r->date_sent)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                $dataOut2[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '0')->where('work_date', $r->date_sent)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();
            }
            $timein_timeout = [$dataIn1, $dataOut2];

            $data = [$date_readable, $employees, $timein_timeout];

            return $data;
        } catch (\Illuminate\Database\QueryException $e) {
            Core::Set_Alert('danger', $e->getMessage());
            ErrorCode::Generate('controller', 'PrintDailyTimelogRecordController', '00000', $e->getMessage());
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
    *                               Date: <string>,
    *                               HoursRequired: <number>,
    *                             },
    *              ]
    */
    public function find2(Request $r)
    {
        try {
            $employees = Employee::Load_Employees_Simple($r->office);

            $dataIn1 = array();
            $dataOut2 = array();

            $data = array();
            $external_data = array();

            for($i = 0; $i < count($employees); $i++) {
                $dataIn1[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '1')->where('work_date', $r->date_sent)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                $dataOut2[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '0')->where('work_date', $r->date_sent)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                $external_data = [
                    "Date" => \Carbon\Carbon::parse($r->date_sent)->format('M d, Y'),
                    "HoursRequired" => intval(explode(':', Timelog::ReqTimeOut())[0]) - intval(explode(':', Timelog::ReqTimeIn())[0]) - 1,
                ];

                $data[$i]['_Name'] = $employees[$i][1];

                if($dataIn1[$i] != null) {

                    if( $dataIn1[$i]->time_log < substr(Timelog::ReqTimeOut_2(), 0, 5) ) {
                        /* AM */

                        $data[$i]['AM']['Arrival'] = $dataIn1[$i]->time_log;

                        if( $dataOut2[$i] != null ) {
                            if( ($dataOut2[$i]->time_log > substr(Timelog::ReqTimeOut_2(), 0, 5)) ) {
                                $data[$i]['AM']['Departure'] = "12:00pm";

                                $data[$i]['PM']['Arrival'] = "1:00pm";
                                $data[$i]['PM']['Departure'] = $dataOut2[$i]->time_log;
                            } else {
                                $data[$i]['AM']['Departure'] = $dataOut2[$i]->time_log;

                                $data[$i]['PM']['Arrival'] = "";
                                $data[$i]['PM']['Departure'] = "";
                            }
                        } else {
                            $data[$i]['AM']['Departure'] = "<span class='text-danger'>missing</span>";

                            $data[$i]['PM']['Arrival'] = "";
                            $data[$i]['PM']['Departure'] = "";
                        }

                    } else if( $dataIn1[$i]->time_log >= substr(Timelog::ReqTimeOut_2(), 0, 5) ) {
                        /* PM */

                        $data[$i]['AM']['Arrival'] = "";
                        $data[$i]['AM']['Departure'] = "";

                        $data[$i]['PM']['Arrival'] = $dataIn1[$i]->time_log;
                        if( $dataOut2[$i] != null ) {
                            $data[$i]['PM']['Departure'] = $dataOut2[$i]->time_log;
                        } else {
                            $data[$i]['PM']['Departure'] = "<span class='text-danger'>missing</span>";
                        }
                    }


                    if($dataOut2[$i] != null) {
                        $in = new DateTime($dataIn1[$i]->time_log);
                        $out = new DateTime($dataOut2[$i]->time_log);

                        $diff = date_diff($in, $out);
                        $diff->h -= 1;

                        $minutes_required = ((intval(explode(":", Timelog::ReqTimeOut())[0]) - intval(explode(":", Timelog::ReqTimeIn())[0])) - 1) * 60;

                        $minutes_rendered = $diff->h * 60;
                        $minutes_rendered += $diff->i;



                        $data[$i]['_Rendered'] = ($minutes_rendered >= $minutes_required)?0:$minutes_required-$minutes_rendered;
                        ;
                    } else {
                        $data[$i]['_Rendered'] = "";
                    }

                } else {
                    $data[$i]['AM']['Arrival'] = "";
                    $data[$i]['AM']['Departure'] = "";
                    $data[$i]['PM']['Arrival'] = "";
                    $data[$i]['PM']['Departure'] = "";

                    $data[$i]['_Rendered'] = "";
                }
            }

            $data = array_values($data);

            return [$data, $external_data];
            
        } catch (Exception $e) {
            ErrorCode::Generate('controller', 'PrintDailyTimelogRecordController', '00001', $e->getMessage());
            return back();
        }
    }
}