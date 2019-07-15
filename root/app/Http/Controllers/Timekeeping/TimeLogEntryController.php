<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Employee;
use DB;
use Core;
use ErrorCode;
use Timelog;

class TimeLogEntryController extends Controller
{
    protected $page;
    protected $employees;

    public function __construct()
    {
    	$this->page = "pages.timekeeping.timelog_entry";
    	$this->employees = Employee::Load_Employees();
    }

    public function view()
    {
    	$data = [$this->employees];
    	return view($this->page, compact('data'));
    }

    public function loadBatchTimeLogsInfo(Request $r)
    {
	    try {
	    	$error_msg = [
		        'required' => 'Some fields are missing.',
		        'tito_emp.exists' => 'Account ID DOES NOT EXISTS',
		    ];
	    	$this->validate($r, [
	    		'tito_emp' => 'required|exists:hr_employee,empid',
	    		'tito_dateStrt' => 'required',
	    		'tito_dateEnd' => 'required',
	    	],$error_msg);
	    	$timelogs = Timelog::getWorkdates($r->tito_emp,$r->tito_dateStrt,$r->tito_dateEnd);
		    if (!empty($timelogs)) {
		    	for($i = 0; $i < count($timelogs); $i++)
		    	{
		    		$timelogs[$i]->status_desc = Core::io((string)$timelogs[$i]->status);
		    		$timelogs[$i]->source_desc = Core::source($timelogs[$i]->source);
		    	}
		    }
		    if (count($timelogs)==0) {
		    	$timelogs = "empty";
		    }
		    return $timelogs;
	    } catch (\Exception $e) {
	    	return "error";
	    }
    }

    public function addLog(Request $r)
    {
	    try {
	    	$nlogs_id = Core::getm99One('logs_id')->logs_id;
            list($d_m, $d_d, $d_y) = explode("-", $r->date_workdate);
            $month = Core::GetMonth((int)$d_m);
            $date = $month." ".$d_d.", ".$d_y;

	    	if (DB::table('hr_tito2')->insert(['work_date' => date('Y-m-d', strtotime($date)), 'time_log' => date('H:i', strtotime($r->time_timelog)), 'empid' => $r->id, 'status' => $r->sel_status, 'source' => 'M', 'logs_id' => $nlogs_id])) {
	    		Core::updatem99('logs_id',Core::get_nextincrementlimitchar($nlogs_id, 8));
	    		$data = DB::table('hr_tito2')->where('logs_id', $nlogs_id)->first();
	    		$data->status_desc = Core::IO((string)$data->status);
	    		$data->source_desc = Core::source($data->source);
	    		return json_encode($data);
	    	}
	    	return "error";
	    } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'TimeLogEntryController', '00002', $e->getMessage());
	    	return "error";
	    }
    }

    public function addLog2($r)
    {
        try {
            $nlogs_id = Core::getm99One('logs_id')->logs_id;
            if (DB::table('hr_tito2')->insert(['work_date' => date('Y-m-d', strtotime($r->work_date)), 'time_log' => date('H:i', strtotime($r->time_log)), 'empid' => $r->empid, 'status' => $r->status, 'source' => $r->source, 'logs_id' => $nlogs_id])) {
                Core::updatem99('logs_id',Core::get_nextincrementlimitchar($nlogs_id, 8));
                return "ok";
            }
            return "error";
        } catch (\Exception $e) {
            return "error";
        }
    }

    public function deleteLog(Request $r)
    {
    	try {
    		if ($r->row!=null) {
    			if (DB::table('hr_tito2')->where('logs_id', $r->row)->first()!=null) {
    				DB::table('hr_tito2')->where('logs_id', $r->row)->delete();
    				return "Time Log Deleted.";
    			}
    			return "error";
    		}
    		return "error";
    	} catch (\Exception $e) {
    		return "error";
    	}
    }

    public function deleteLog_All(Request $r)
    {
    	try {
    		$data = Timelog::getWorkdates($r->tito_emp, $r->tito_dateStrt, $r->tito_dateEnd);
    		if (!empty($data)) {
    			for($i=0;$i<count($data);$i++) {
    				DB::table('hr_tito2')->where('logs_id', $data[$i]->logs_id)->delete();
	    		}
	    		return "Logs Deleted";
    		}

    		else if ($data == null) {
    			return "empty";
    		}
    		return "error";
    	} catch (\Exception $e) {
    		return "error";
    	}
    }

    public function GetLog(Request $r)
    {
        try {
            $emp = DB::table('hr_employee')->where('empid', $r->id)->first();
            if ($emp!=null) {
                $log = DB::table('hr_tito2')->where('logs_id', $r->log)->first();
                if ($log!=null) {
                    $log->status_type = Core::IO2('capital', $log->status);
                    return json_encode($log);
                } else {
                    return "no record";
                }
            } else {
                return "no user";
            }
            return "error";
        } catch (\Exception $e) {
            return "error";
        }
    }

    public function EditLog(Request $r)
    {
        try {
            $log = DB::table('hr_tito2')->where('logs_id', $r->logid)->first();
            if ($log!=null) {
                DB::table('hr_tito2')->where('logs_id', $log->logs_id)->update([
                    'work_date' => $r->date_workdate2,
                    'time_log' => $r->time_timelog2,
                    'status' => $r->sel_status2,
                ]);
                $newlog = DB::table('hr_tito2')->where('logs_id', $r->logid)->first();
                $newlog->status_desc = Core::io((string)$newlog->status);
                $newlog->source_desc = Core::source($newlog->source);
                return json_encode($newlog);
            } else {
                return "no record";
            }
            return "error";
        } catch (\Exception $e) {
            return "error";
        }
    }
}
