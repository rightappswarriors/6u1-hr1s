<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use Session;
use DB;
use Carbon\Carbon;
use ErrorCode;
use Employee;
use Timelog;

/*
|--------------------------------------------------------------------------
| Error Code
|--------------------------------------------------------------------------
| Prefix - TTC
|
| 00001 - TimeLog (general try/catch)
| 00002 - TimeLog (out of statement, did not get any result)
| 00003 - TimeLog (no value in the selector)
| 
|--------------------------------------------------------------------------
*/

class TiToController extends Controller
{
    public function __construct()
    {
        // $this->ghistory = DB::table('hr_tito2')->where('cancel', '=', null)->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->take(6)->get();
        $this->ghistoryIn = DB::table('hr_tito2')->where('cancel', '=', null)->whereDate('work_date', date('Y-m-d'))->where('status', '=', '1')->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get();
        $this->ghistoryOut = DB::table('hr_tito2')->where('cancel', '=', null)->whereDate('work_date', date('Y-m-d'))->where('status', '=', '0')->orderBy('work_date', 'DESC')->orderBy('time_log', 'DESC')->orderBy('logs_id', 'DESC')->take(6)->get();

    }

    public function view()
    {
        // dd(Session::get('_user')[0]);
        $data = [$this->ghistoryIn];
        // dd($data);
        return view('pages.timekeeping.timein_timeout', compact('data'));
    }

    public function TimeLog(Request $r)
    {
        try {
            $error_msg = [
                'acc_id.required' => 'Invalid Account ID',
                'acc_mode.required' => 'Please select time mode.',
            ];
            $this->validate($r, [
                'acc_id' => 'required',
                'acc_mode' => 'required',
            ],$error_msg);

            if (Employee::GetEmployee($r->acc_id)==null) {
                Core::Set_Alert('danger', 'Employee not found.');
                return back();
            }

            if(Employee::GetBiometric($r->acc_id)!=$r->acc_pwd) {
                Core::Set_Alert('danger', 'Password incorrect.');
                return back();
            }

            switch ($r->acc_mode) {
                case 'timein':
                    $mode = "1";
                    $msg = "Timed In!";
                    break;

                case 'timeout':
                    $mode = "0";
                    $msg = "Timed Out!";
                    break;
                
                default:
                    Core::Set_Alert('danger','An Error Occured. Please try again. '.ErrorCode::Generate('controller', 'TiToController', '00003', 'Value is not in the selection')['error_code']);
                    return back();
                    break;
            }

            $log = $this->SetLog($r, $mode);
            if ($log=="ok") {
                Core::Set_Alert('success', $msg);
                return back();
            }

            if ($log=="invalid") {
                Core::Set_Alert('warning','You already have timed in/out.');
                return back();
            }

            Core::Set_Alert('danger', 'An Error Occured. Please try again. '.ErrorCode::Generate('controller', 'TiToController', '00002', $log)['error_code']);

            return back();
        } catch (\Exception $e) {
            Core::Set_Alert('danger', 'An Error Occured. Please try again. '.ErrorCode::Generate('controller', 'TiToController', '00001', $e->getMessage())['error_code']);
            return back();
        }
    }

    public function SetLog(Request $r, $mode)
    {
	  	try {
	  		$nlogs_id = Core::getm99One('logs_id')->logs_id;
	  		$date = Carbon::now()->format('Y-m-d');
            $time = Carbon::now()->format('H:i');
            $time_strt = "07:00";

            // dd($this->CheckExistingLog($r, $time));
            if ($this->CheckExistingLog($r, $time)==true) {
                return "invalid";
            }

            // if ($mode=="1") { 
            //     if ($this->CheckTimeIn($time_strt, $time)==true) {
            //         $time = $time_strt;
            //     }
            //     if ($this->CheckTimeOut($r, $date)) {
            //         return "invalid";
            //     }
            // }
            // if ($mode=="0") {
            //     if ($this->CheckTimeOut($r, $date)) {
            //         return "invalid";
            //     }
            // }

	  		if (DB::table('hr_tito2')->insert(['work_date' => $date, 'time_log' => $time, 'empid' => $r->acc_id, 'status' => $mode, 'source' => 'LB', 'logs_id' => $nlogs_id, ])) {
	  			Core::updatem99('logs_id',Core::get_nextincrementlimitchar($nlogs_id, 8));
	  			return "ok";
	  		}
	    	return "Log was not saved.";
	  	} catch (\Exception $e) {
	  		return $e->getMessage();
	  	}
    }

    public function CheckTimeIn($time1, $time2)
    {
        list($hour1, $minute1) = explode(":", $time1);
        list($hour2, $minute2) = explode(":", $time2);

        $hour1 = (int)$hour1; 
        $hour2 = (int)$hour2; 
        $minute1 = (int)$minute1;
        $minute2 = (int)$minute2;

        if ($hour1>$hour2) {
            return true;
        } else {
            if ($hour1==$hour2) {
                if ($minute1>$minute2) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function CheckTimeOut(Request $r, $date)
    {
        return Timelog::IfEmployeeAlreadyOut($r->acc_id, $date);
    }

    public function CheckExistingLog(Request $r, $time)
    {
        try {
            $date = Carbon::now()->format('Y-m-d');
            $data = DB::table('hr_tito2')->where('work_date', $date)->where('time_log', $time)->where('empid', $r->acc_id)->first();
            // dd($data);
            if ($data!=null) {
                if ($time==$data->time_log) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return true;
        }
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
