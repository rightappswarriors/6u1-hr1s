<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Employee;
use Position;
use Office;

class LogBoxController extends Controller
{
    public function __construct()
    {
        // $this->ghistory = DB::table('hr_tito2')->where('cancel', '=', null)->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->take(6)->get();
        // $this->ghistoryIn = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '1')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get();
        $date = date('Y-m-d');
        $this->ghistoryIn = DB::select("SELECT * from hris.hr_tito2 where cancel is null and status = 1::text and work_date = '$date' /*and empid not in (SELECT empid from hris.hr_tito2 where cancel is null and status = 0::text and work_date = '$date')*/ order by work_date desc, time_log desc, logs_id desc LIMIT 15");
        $this->ghistoryOut = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(15)->get();

    }

    public function view()
    {
        $data = [$this->ghistoryIn, Office::get_all()];
        // $data = $data[0];
        // dd($data);
        // dd(Employee::GetEmployee($data[0][0]->empid)->positions);
        return view('pages.timekeeping.log_box', compact('data'));
    }

    /**
    * Get Latest Time In
    * @param Request
    *
    * @return array | null
    */
    public function getLastestTimeIn(Request $r) {
        $data = $this->ghistoryIn;
        for($i=0; $i<count($data); $i++){
            $data[$i]->name = Employee::Name($data[$i]->empid);
            $data[$i]->work_date_readable = \Carbon\Carbon::parse($data[$i]->work_date)->format('M d, Y');
            $data[$i]->time_log_readable = date('h:ia', strtotime($data[$i]->time_log));
            // $data[$i]->position_readable = Position::Get_Position(Employee::GetEmployee($data[$i]->empid)->positions);
            $data[$i]->position_readable = Employee::GetJobTitle($data[$i]->empid);
            $data[$i]->picture_readable = Employee::GetEmployee($data[$i]->empid)->picture;
        }

        return $data;
    }


    /**
    * Get Latest Time Out
    * @param Request
    *
    * @return array | null
    */
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
    public function getPagination(Request $r){
        $date = date('Y-m-d');
        $data = DB::select("SELECT * from hris.hr_tito2 where cancel is null and status = 1::text and work_date = '$date' and empid not in (SELECT empid from hris.hr_tito2 where cancel is null and status = 0::text and work_date = '$date') order by work_date desc, time_log desc, logs_id desc");
        $num_rows = count($data);
        return $num_rows;
    }
    public function getPaginationOut(Request $r){
        $date = date('Y-m-d');
        $data = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->skip('$offset')->get();
        $num_rows = count($data);
        return $num_rows;
    }

    public function setPaginationIn(Request $r){
        $date = date('Y-m-d');
        if($r->pageInput == 1 || $r->pageInput == '1'){

            $returnData = DB::select("SELECT * from hris.hr_tito2 where cancel is null and status = 1::text and work_date = '$date' and empid not in (SELECT empid from hris.hr_tito2 where cancel is null and status = 0::text and work_date = '$date') order by work_date desc, time_log desc, logs_id desc LIMIT 15");
            for($i = 0; $i < count($returnData); $i++)
            {
                $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
                $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
                $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
                $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
                $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            }
        }
        else
        {   
            $page = $r->pageInput - 1;
            $offset = $r->total_data * $page;

            $returnData = DB::select("SELECT * from hris.hr_tito2 where cancel is null and status = 1::text and work_date = '$date' and empid not in (SELECT empid from hris.hr_tito2 where cancel is null and status = 0::text and work_date = '$date') order by work_date desc, time_log desc, logs_id desc LIMIT 15 OFFSET '$offset'");

            for($i = 0; $i < count($returnData); $i++)
            {
                $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
                $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
                $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
                $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
                $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            }
        }
        $data = [$returnData, $r->pageInput];
       return $data;
    }

    public function setPaginationOut(Request $r){
        $date = date('Y-m-d');
        if($r->pageInput == 1 || $r->pageInput == '1'){
            $returnData = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->limit(15)->get();
            for($i = 0; $i < count($returnData); $i++)
            {
                $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
                $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
                $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
                $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
                $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            }
        }
        else
        {   
            $page = $r->pageInput - 1;
            $offset = $r->total_data * $page;
            $returnData = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->offset($offset)->limit(15)->get();
            for($i = 0; $i < count($returnData); $i++)
            {
                $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
                $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
                $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
                $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
                $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            }
        }
        $data = [$returnData, $r->pageInput];
       return $data;
    }

    public function getFilteredData(Request $r)
    {
        $date = date('Y-m-d');


        $returnData = DB::select("SELECT * from hris.hr_tito2 where cancel is null and status = 1::text and work_date = '$date' and empid not in (SELECT empid from hris.hr_tito2 where cancel is null and status = 0::text and work_date = '$date') order by work_date desc, time_log desc, logs_id desc");
        for($i = 0; $i < count($returnData); $i++)
        {
            $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
            $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
            $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
            $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
            $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            $returnData[$i]->office = Employee::getOfficeByID($returnData[$i]->empid);
        }
        
       
        $data = [$returnData, $r->pageInput];
    
       return $data;
    }

    public function getFilteredDataOut(Request $r)
    {
        $date = date('Y-m-d');


        $returnData = DB::table('hr_tito2')->where('cancel', '=', null)->where('status', '=', '0')->where('work_date', date('Y-m-d'))->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->get();
        for($i = 0; $i < count($returnData); $i++)
        {
            $returnData[$i]->name = Employee::Name($returnData[$i]->empid);
            $returnData[$i]->work_date_readable = \Carbon\Carbon::parse($returnData[$i]->work_date)->format('M d, Y');
            $returnData[$i]->time_log_readable = date('h:ia', strtotime($returnData[$i]->time_log));
            $returnData[$i]->position_readable = Employee::GetJobTitle($returnData[$i]->empid);
            $returnData[$i]->picture_readable = Employee::GetEmployee($returnData[$i]->empid)->picture;
            $returnData[$i]->office = Employee::getOfficeByID($returnData[$i]->empid);
        }
        
       
        $data = [$returnData, $r->pageInput];
    
       return $data;
    }

    
}