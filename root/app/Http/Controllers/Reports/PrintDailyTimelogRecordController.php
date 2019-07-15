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

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - PDTRC
|
| 00000 - find
| 
|--------------------------------------------------------------------------
*/

class PrintDailyTimelogRecordController extends Controller
{
    public function view()
    {
        // dd($data);
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
    */
    public function find(Request $r)
    {
        try {
            $date = explode("-",$r->date_sent);
            $date = $date[2].'-'.$date[0].'-'.$date[1];
            $date_readable = \Carbon\Carbon::parse($date)->format('M d, Y');

            $employees = Employee::Load_Employees_Simple();

            $dataIn1 = array();
            $dataOut2 = array();


            for($i = 0; $i < count($employees); $i++) {
                $dataIn1[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '1')->where('work_date', $date)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();

                $dataOut2[$i] = DB::table('hr_tito2')->where('empid', $employees[$i][0])->where('status', '0')->where('work_date', $date)->orderBy('work_date', 'ASC')->orderBy('time_log', 'ASC')->first();
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
}