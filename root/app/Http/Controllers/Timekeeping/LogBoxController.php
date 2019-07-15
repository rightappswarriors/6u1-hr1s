<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Employee;
use Position;

class LogBoxController extends Controller
{
    public function __construct()
    {
        // $this->ghistory = DB::table('hr_tito2')->where('cancel', '=', null)->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->take(6)->get();
        $this->ghistoryIn = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '1')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get();
        $this->ghistoryOut = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get();

    }

    public function view()
    {
        $data = [$this->ghistoryIn];
        // $data = $data[0];
        // dd($data);
        // dd(Employee::GetEmployee($data[0][0]->empid)->positions);
        return view('pages.timekeeping.log_box', compact('data'));
    }

    public function getLastestTimeIn(Request $r) {
        $data = $this->ghistoryIn;
        for($i=0; $i<count($data); $i++) {
            $data[$i]->name = Employee::Name($data[$i]->empid);
            $data[$i]->work_date_readable = \Carbon\Carbon::parse($data[$i]->work_date)->format('M d, Y');
            $data[$i]->time_log_readable = date('h:ia', strtotime($data[$i]->time_log));
            // $data[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($data[$i]->empid)->positions);
            $data[$i]->position_readable = Employee::GetJobTitle($data[$i]->empid);
            $data[$i]->picture_readable = Employee::GetEmployee($data[$i]->empid)->picture;
        }

        return $data;
    }

    public function getLastestTimeOut(Request $r) {
        $data = $this->ghistoryOut;
        for($i=0; $i<count($data); $i++) {
            $data[$i]->name = Employee::Name($data[$i]->empid);
            $data[$i]->work_date_readable = \Carbon\Carbon::parse($data[$i]->work_date)->format('M d, Y');
            $data[$i]->time_log_readable = date('h:ia', strtotime($data[$i]->time_log));
            // $data[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($data[$i]->empid)->positions);
            $data[$i]->position_readable = Employee::GetJobTitle($data[$i]->empid);
            $data[$i]->picture_readable = Employee::GetEmployee($data[$i]->empid)->picture;
        }

        return $data;
    }
}