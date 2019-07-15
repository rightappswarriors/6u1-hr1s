<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Core;
use DB;
use Employee;
use ErrorCode;
use PayrollPeriod;
use Payroll;
use Session;
use Timelog;

class GenerateDTRController extends Controller
{
    protected $employees;
    protected $payrollperiod;

    public function __construct()
    {
    	$this->employees = Employee::Load_Employees();
    	$this->payrollperiod = PayrollPeriod::Load_PayrollPeriod();
    }

    public function view()
    {
    	$dh = $this->LoadDTRHistory();
    	$emp = $this->employees;
    	for($i=0;$i<count($dh);$i++) {
    		$pp = /*PayrollPeriod::getPayrollPeriod($dh[$i]->ppid)*/ null;
    		$dh[$i]->pp = $pp[0]." to ".$pp[1];
    		$dh[$i]->empname = Employee::Name($dh[$i]->empid);
    	}
    	for ($i=0; $i < count($emp); $i++) { 
    		$emp[$i]->name = Employee::Name($emp[$i]->empid);
    	}
    	$data = [$dh, $this->payrollperiod, $emp];
    	return view('pages.timekeeping.generate_dtr', compact('data'));
    }

    public function LoadDTRHistory()
    {
    	return DB::table('hr_dtr_sum_hdr')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'ASC')->get();
    }

    public function GenerateDTR(Request $r)
    {
	    /*
        | *Retrieves timelog info and computes the total time of late, overtime, and undertime
        | *Some values are from different models
        | *Be wary when changing values that came from other models/controllers for some of them
        | are connected to other models/controllers as well
        */
        try {
            $late = "00:00";
            $arr_late = [];
            $undertime = "00:00";
            $arr_undertime = [];
            $totalovertime = "00:00";
            $arr_overtime = [];
            $arr_leavedates = [];

            $req_hrs = Timelog::ReqHours();
            $req_hrs2 = Timelog::ReqHours2();
            $employee = Employee::GetEmployee($r->code);
            $name = Employee::Name($r->code);
            $pp = Payroll::PayrollPeriod2($r->month,$r->pp, $r->year);
            // $workdays = Core::TotalDays($pp->from, $pp->to);
            $workdays = Core::CoveredDates($pp->from, $pp->to);

            $totaldays = 0;
            $totalpresent = 0;
            $totalabsent = 0;
            $totalweekend = 0;
            $totalholiday = 0;
            $totalleave = 0;
            $errors = [];
            $errors2 = [];


            for ($i=0; $i < count($workdays); $i++) { 
                // $day = $pp->start+$i;
                // $date = date('Y-m-d', strtotime(Core::GetMonth((int)$r->month)." ".$day.", ".date('Y')));
                $date = date('Y-m-d', strtotime($workdays[$i]));
                $record = DB::table('hr_tito2')->distinct('work_date')->where('work_date', '=', $date)->where('empid', $employee->empid)->orderby('work_date', 'ASC')->get(); 

                if (Timelog::IfWeekdays($date)) {
                    if (count($record)<=0) {
                        $totalabsent+=1;
                    } else {
                        if (count($record)<=1) {
                            array_push($errors, $date);
                            $totalabsent+=1;
                        } else {
                            $r_time = Timelog::GetRenHours($record[0]->time_log, $record[1]->time_log);
                            // $a_time = Core::GET_TIME_DIFF($req_hrs2, $r_time); dd($r_time);
                            if (Timelog::IfHoliday($date)) {
                                array_push($arr_overtime, $r_time);
                                $totalholiday+=1;
                            } else {
                                if (Timelog::IfLate($record[0]->time_log)) {
                                    $z = "";
                                    $z = Core::GET_TIME_DIFF(Timelog::ReqTimeIn(),$record[0]->time_log);
                                    array_push($arr_late, $z);
                                    // array_push($arr_undertime, $z);
                                }
                                if (Timelog::IfUndertime($record[0]->time_log, $record[1]->time_log)) {
                                    $z = "";
                                    $z = Core::GET_TIME_DIFF($r_time, $req_hrs2);
                                    array_push($arr_undertime, $z);
                                }
                                // if (Core::ToMinutes($r_time) > ($req_hrs * 60)) {
                                //     $z = Core::GET_TIME_DIFF(Timelog::ReqTimeOut(),$record[1]->time_log);
                                //     array_push($arr_overtime, $z);
                                //     $z = "";
                                // }
                                if (Core::ToMinutes($r_time) > Core::ToMinutes($req_hrs2)) {
                                    $z = Core::GET_TIME_DIFF(Timelog::ReqTimeIn(),$record[0]->time_log);
                                    $y = Core::GET_TIME_DIFF(Timelog::ReqTimeOut(),$record[1]->time_log);

                                    array_push($arr_overtime, $z);
                                    array_push($arr_overtime, $y);
                                }
                                $totalpresent+=1;
                                 array_push($errors2, [$i, "H"=>Timelog::IfHoliday($date), "L"=>Timelog::IfLate($record[0]->time_log), "U"=>Timelog::IfUndertime($record[0]->time_log, $record[1]->time_log), "O"=>Core::ToMinutes($r_time) > Core::ToMinutes($req_hrs2)]);
                            }
                        }
                    }
                } else {
                    $totalweekend+=1;
                }
            }

            $workdays = count($workdays) - $totalweekend;
            $totaldays = $totalpresent + $totalholiday;
            $late = Core::GET_TIME_TOTAL($arr_late);
            $undertime = Core::GET_TIME_TOTAL($arr_undertime);
            $totalovertime = Core::GET_TIME_TOTAL($arr_overtime);

            $record = null;
            if (DB::table('hr_dtr_sum_hdr')->where('empid', $employee->empid)->where('date_from', $pp->from)->where('date_to', $pp->to)->first()!=null) {
                $record = 1;
            }

            $data = [
                // 'employee'=>$employee,
                // 'empid'=>$employee->empid,
                // 'ppid' => $r->pp,
                'empname'=>$name,

                'month'=>$r->month,
                'year'=>$r->year,
                'req_hrs'=>$req_hrs,
                'workdays'=> $workdays,
                'daysworked'=>$totaldays,
                'absences'=>$totalabsent,
                'holidays'=>0,

                'late'=>$late,
                'undertime'=>$undertime,
                'overtime'=>$totalovertime,

                'date_from'=>$pp->from,
                'date_from2'=>date('M d, Y', strtotime($pp->from)),
                'date_to'=> $pp->to,
                'date_to2'=> date('M d, Y', strtotime($pp->to)),

                'isgenerated'=>$record,
                'errors'=>$errors,
                '_errors2'=>$errors2,
            ];

            // Session::forget('dtr_summary');
            // Session::put('dtr_summary', $data);

            return json_encode($data);
	    } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', '00002', $e->getMessage());
	    	return $e->getMessage();
	    	return "error";
	    }
    }

    public function SaveDTR(Request $r)
    {
        try {
            if (isset($r->dtrs['errors'])) {
                return "existing-error";
            }
            $record = DB::table('hr_dtr_sum_hdr')->where('empid', $r->empid)->where('date_from', $r->dtrs['date_from'])->where('date_to', $r->dtrs['date_to'])->first();
            if ($record==null) {
                try {
                    // $pp = Payroll::PayrollPeriod2($r->ppid);
                    try {
                        $code = Core::getm99('dtr_sum_id');
                        DB::table('hr_dtr_sum_hdr')->insert([
                            'empid' => $r->empid,
                            'ppid' => $r->ppid,
                            'date_from' => $r->dtrs['date_from'],
                            'date_to' => $r->dtrs['date_to'],
                            'date_generated' => date('Y-m-d'),
                            'time_generated' => date('h:i'),
                            'code' => $code,
                        ]);
                        Core::updatem99('dtr_sum_id',Core::get_nextincrementlimitchar($code, 8));
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GenerateDTRController', '00005', $e->getMessage());
                        return "error";
                    }

                    $dtr_summary = $r->dtrs;
                    $reply = $this->SaveSummary($r->empid, $dtr_summary['daysworked'], $dtr_summary['absences'], $dtr_summary['late'], $dtr_summary['undertime'], $dtr_summary['overtime'], $code);

                    $dh = $this->LoadDTRHistory();
                    for($i=0;$i<count($dh);$i++) {
                        $pp = Payroll::PayrollPeriod2($r->month, $dh[$i]->ppid);
                        $dh[$i]->pp = $pp->from." to ".$pp->to;
                        $dh[$i]->empname = Employee::Name($dh[$i]->empid);
                    }
                    return [json_encode($dh), $reply];
                    
                } catch (\Exception $e) {
                    ErrorCode::Generate('controller', 'GenerateDTRController', '00004', $e->getMessage());
                    return "error";
                }
            } else {
                return "max";
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', '00003', $e->getMessage());
            return "error";
        }
    }

    public function SaveSummary($empid, $days_worked, $absences, $late, $undertime, $overtime, $dtr_sum_id)
    {
        try {
            $query = DB::table('hr_dtr_sum_employees')->where('empid', $empid)->where('dtr_sum_id', $dtr_sum_id);
            $data = [
                'empid' => $empid,
                'days_worked' => $days_worked,
                'absences' => $absences,
                'late' => $late,
                'undertime' => $undertime,
                'total_overtime' => $overtime,
                'dtr_sum_id' => $dtr_sum_id,
                'isgenerated' => 0,
            ];
            if ($query->first()==null) {
                try {
                    $query->insert($data);
                } catch (\Exception $e) {
                    ErrorCode::Generate('controller', 'GenerateDTRController', '00006', $e->getMessage());
                    return "error";
                }
            } else {
                if ($query->first()->isgenerated==0) {
                    try {
                        $query->update($data);
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GenerateDTRController', '00007', $e->getMessage());
                        return "error";
                    }
                } else {
                    return "isgenerated";
                }
            }
            return "ok";
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', '00005', $e->getMessage());
            return "error";
        }
    }
}
