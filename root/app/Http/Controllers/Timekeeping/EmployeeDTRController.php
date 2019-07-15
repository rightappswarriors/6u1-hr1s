<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Employee;
use Timelog;
use Core;

class EmployeeDTRController extends Controller
{
    protected $page;
    protected $employees;

    public function __construct()
    {
    	$this->page = "pages.timekeeping.employee_dtr";
    	$this->employees = Employee::Load_Employees();
    }

    public function view()
    {
    	$data = [$this->employees];
    	return view($this->page, compact('data'));
    }

    public function LoadDTR(Request $r)
    {
	    try {
	    	$sql = Core::sql("SELECT work_date, array_to_string(array_agg(time_log), ',') time_log, empid, array_to_string(array_agg(status), ',') status, array_to_string(array_agg(logs_id), ',') logs_id FROM hris.hr_tito2 hr_tito2 WHERE work_date >= '".$r->date_from."' AND work_date <= '".$r->date_to."' AND empid = '".$r->tito_emp."' GROUP BY hr_tito2.work_date, empid ORDER BY hr_tito2.work_date ASC");

            if ($sql!=null) {
                for($i=0;$i<count($sql);$i++) {
                    $time_log = explode(",", $sql[$i]->time_log);
                    $status = explode(",", $sql[$i]->time_log);
                    if (count($time_log)<=1) {
                        $sql[$i]->timein = ($sql[$i]->status=="1"||$sql[$i]->status=="I") ? $time_log : "Time in is missing.";
                        $sql[$i]->timeout = ($sql[$i]->status=="0"||$sql[$i]->status=="O") ? $time_log : "Time out is missing.";
                    } else {
                        
                        $sql[$i]->timein = $time_log[0];
                        $sql[$i]->timeout = $time_log[1];
                    }
                }
            } else {
                return "No record found.";
            }

	    	return $sql;
	    } catch (\Exception $e) {
            return "error";
	    }
    }

    public function PrintDTR(Request $r) {
        $dtr = $this->LoadDTR($r);
        $data = [$dtr];
        return view('pages.timekeeping.print.dtr', compact('data'));
    }
}
