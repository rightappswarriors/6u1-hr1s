<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MFile\OfficeController;
use Core;
use DB;
use Account;
use Employee;
use EmployeeStatus;
use EmployeeFlag;
use ErrorCode;
use Office;
use Payroll;
use PayrollPeriod;
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
        $this->office = Office::get_all();
        $this->empstatus = EmployeeStatus::get_all();
    }

    public function view()
    {
        $dh = $this->LoadDTRHistory();
        $emp = $this->employees;
        $ofc = $this->office;/* dd($ofc);*/
        for($i=0;$i<count($dh);$i++) {
            $pp = /*PayrollPeriod::getPayrollPeriod($dh[$i]->ppid)*/ null;
            $dh[$i]->pp = $pp[0]." to ".$pp[1];
            $dh[$i]->empname = Employee::Name($dh[$i]->empid);
        }
        for ($i=0; $i < count($emp); $i++) { 
            $emp[$i]->name = Employee::Name($emp[$i]->empid);
        }
        $data = [$dh, $this->payrollperiod, $emp, $ofc, $this->empstatus];
        return view('pages.timekeeping.generate_dtr', compact('data'));
    }

    public function LoadDTRHistory()
    {
        return DB::table('hr_dtr_sum_hdr')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'ASC')->get();
    }

    public function GenerateDTR(Request $r)
    {
        return $this->Generate($r);
    }

    public function Generate($r)
    {
        /*
        | *Retrieves timelog info and computes the total time of late, overtime, and undertime
        | *Some values are from different models
        | *Be wary when changing values that came from other models/controllers for some of them
        | are connected to other models/controllers as well
        */
        /**
        * @param $r->code
        * @param $r->pp
        * @param $r->month
        * @param $r->year
        * @param $r->gtype
        */
        try {
            $late = "00:00";
            $arr_late = [];
            $undertime = "00:00";
            $arr_undertime = [];
            $overtime = "00:00";
            $arr_overtime = [];
            $arr_leavedates = [];
            $weekdayhrs = "00:00";
            $arr_weekdayhrs = [];
            $weekendhrs = "00:00";
            $arr_weekendhrs = [];
            $arr_daysworked = [];

            $req_hrs = Timelog::ReqHours();
            $req_hrs2 = Timelog::ReqHours2();
            $employee = Employee::GetEmployee($r->code);
            $name = Employee::Name($r->code);
            $pp = Payroll::PayrollPeriod2($r->month,$r->pp, $r->year);
            // $covereddates = Core::TotalDays($pp->from, $pp->to);
            $covereddates = Core::CoveredDates($pp->from, $pp->to);

            if ($employee == null) {
                return "noemp";
            }

            $totaldays = 0;
            $totalpresent = 0;
            $totalabsent = 0;
            $totalweekend = 0;
            $totalholiday = 0;
            $totalleave = 0;
            $totalovertime = 0;
            $errors = [];
            $errors2 = [];

            for ($i=0; $i < count($covereddates); $i++) {
                $date = date('Y-m-d', strtotime($covereddates[$i]));

                $sql_p1 = "SELECT work_date, string_agg(time_log, ',') time_log, empid, status FROM hris.hr_tito2 WHERE empid = '".$employee->empid."' AND work_date = '".$date."'";
                $sql_p2 = " GROUP BY work_date, empid, status ORDER BY work_date DESC, status DESC";

                $rec_ti = "";
                $sql_ti = " AND status = '1'";
                $sql_ti = $sql_p1.$sql_ti.$sql_p2;
                $rec_ti = Core::sql($sql_ti);

                $rec_to = "";
                $sql_to = " AND status = '0'";
                $sql_to = $sql_p1.$sql_to.$sql_p2;
                $rec_to = Core::sql($sql_to);

                $tl_in_am = "00:00";
                $tl_in_pm = "00:00";
                $tl_in_trsh = [];
                $tl_in_ot = [];
                $tl_out_am = "00:00";
                $tl_out_pm = "00:00";
                $tl_out_trsh = [];
                $tl_out_ot = [];

                $r_time_total = "00:00:00";
                $r_time_am = "00:00:00";
                $r_time_pm = "00:00:00";

                $r_time_ot_total = "00:00:00";
                $r_time_ot_total_arr = [];
                $r_time_ot_arr = [];

                /*array_push($errors2, [count($rec_ti) > 0, count($rec_to) > 0]);*/

                if (count($rec_ti) > 0) {
                    if (count($rec_to) > 0) {
                        $rec_ti = explode(",", $rec_ti[0]->time_log);
                        $rec_to = explode(",", $rec_to[0]->time_log);

                        try {
                            /**
                            * Time Validating Method
                            */
                            $tl_ti = "";
                            if (count($rec_ti) > 0) {
                                for ($j=0; $j < count($rec_ti); $j++) { 
                                    $tl_ti = $rec_ti[$j];
                                    if (Timelog::ValidateLog_AM($tl_ti) && $tl_in_am == "00:00") {
                                        $tl_in_am = $tl_ti;
                                    } elseif (Timelog::ValidateLog_PM($tl_ti) && $tl_in_pm == "00:00") {
                                        $tl_in_pm = $tl_ti;
                                    } elseif(Timelog::ValidateLog_OTHrs($tl_ti)) {
                                        array_push($tl_in_ot, $j."|".$tl_ti);
                                    } else {
                                        array_push($tl_in_trsh, [$date, $tl_ti]);
                                    }
                                }
                            }
                            $tl_ti = "";
                            if (count($rec_to) > 0) {
                                for ($j=0; $j < count($rec_to); $j++) { 
                                    $tl_ti = $rec_to[$j];
                                    if (Timelog::ValidateLog_AM($tl_ti) && $tl_out_am == "00:00") {
                                        $tl_out_am = $tl_ti;
                                    } elseif (Timelog::ValidateLog_PM($tl_ti) && $tl_out_pm == "00:00") {
                                        $tl_out_pm = $tl_ti;
                                    } elseif(Timelog::ValidateLog_OTHrs($tl_ti)) {
                                        array_push($tl_out_ot, $j."|".$tl_ti);
                                    } else {
                                        array_push($tl_out_trsh, [$date, $tl_ti]);
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00001', $e->getMessage());
                            return "error";
                        }

                        try {
                            /**
                            * Time Sorting Method
                            * imploded array format [date, timelog, computed time]
                            * timelog imploded format [timein_am, timeout_am, timein_pm, timeout_pm] / [timein, timeout] / array of [timein, timeout]
                            */
                            if ($tl_in_am != "00:00" && $tl_out_am != "00:00") { // ami = 1, amo = 1
                                if ($tl_in_pm != "00:00" && $tl_out_pm != "00:00") { // pmi = 1, pmo = 1
                                    $r_time_am = Timelog::GetRenHours($tl_in_am, $tl_out_am, "am");
                                    $r_time_pm = Timelog::GetRenHours($tl_in_pm, $tl_out_pm, "pm");
                                    $r_time_total = Core::GET_TIME_TOTAL([$r_time_am, $r_time_pm]);
                                    // IfLate
                                    if (Timelog::IfLate($tl_in_am)) {
                                        array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am)]);
                                    }
                                    // IfUndertime
                                    if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                        array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                    }
                                    // To Array
                                    array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                    $totalpresent+=1;
                                } elseif ($tl_in_am != "00:00" && $tl_out_pm != "00:00") { // ami = 1, pmo = 1
                                    $r_time_total = Timelog::GetRenHours($tl_in_am, $tl_out_pm, "am");
                                    // IfUndertime
                                    if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                        array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                    }
                                    // To Array
                                    array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_pm], $r_time_total]);
                                    $totalpresent+=1;
                                }
                            } elseif ($tl_in_am != "00:00" && $tl_out_pm != "00:00") { // ami = 1, pmo = 1
                                $r_time_total = Timelog::GetRenHours($tl_in_am, $tl_out_pm, "am"); 
                                // IfLate
                                if (Timelog::IfLate($tl_in_am)) {
                                    array_push($arr_late, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am)]);
                                }
                                // IfUndertime
                                if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                    array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                }
                                // To Array
                                array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_pm], $r_time_total]);
                                $totalpresent+=1;
                            }
                            if (count($tl_in_ot) > 0) {
                                for ($j=0; $j < count($tl_in_ot); $j++) {
                                    list($ja, $jb) = explode("|", $tl_in_ot[$j]);
                                    for ($k=0; $k < count($tl_out_ot); $k++) {
                                        list($ka, $kb) = explode("|", $tl_out_ot[$k]);
                                        if ($ja == $ka) {
                                            $jk = Timelog::GetRenHours($jb, $kb, "pm");
                                            if (Timelog::IfOvertime($jk)) {
                                                array_push($r_time_ot_arr, [$jb, $kb, $jk]);
                                            }
                                        }
                                    }
                                }
                            }
                            if (count($r_time_ot_arr) > 0) {
                                $tmp = [];
                                for ($j=0; $j < count($r_time_ot_arr); $j++) { 
                                    list($ja, $jb, $jc) = $r_time_ot_arr[$j];
                                    array_push($tmp, [[$ja, $jb], $jc]);
                                }
                                for ($j=0; $j < count($tmp); $j++) { 
                                    list($ja, $jb) = $tmp[$j];
                                    array_push($arr_overtime, [$date, $ja, $jb]);
                                }
                                $totalovertime+=1;
                            }
                        } catch (\Exception $e) {
                            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00002', $e->getMessage());
                            return "error";
                        }
                    } else {
                        // missing logs
                        array_push($errors, $date);
                    }
                } else {
                    // absent
                } array_push($errors2, [$date, "AM_I" => $tl_in_am, "AM_O" => $tl_out_am, "PM_I" => $tl_in_pm, "PM_O" => $tl_out_pm, "OT_T" => $r_time_ot_arr, "OT" => $r_time_ot_total]);


                if (Timelog::IfWorkdays($date)) {
                    array_push($arr_weekdayhrs, $r_time_total);
                } else {
                    array_push($arr_weekendhrs, $r_time_total);
                    $totalweekend+=1;
                }
            }

            try {
                /**
                * Time Counting Method
                */
                $workdays = count($covereddates) - $totalweekend;
                $totaldays = $totalpresent + $totalholiday;
                $totalabsent = $workdays - $totalpresent;
                if (count($arr_late) > 0) {
                    $tmp = [];
                    for ($i=0; $i < count($arr_late); $i++) { 
                        list($ia, $ib, $ic) = $arr_late[$i];
                        array_push($tmp, $ic);
                    }
                    $late = Core::GET_TIME_TOTAL($tmp);
                }
                if (count($arr_undertime) > 0) {
                    $tmp = [];
                    for ($i=0; $i < count($arr_undertime); $i++) { 
                        list($ia, $ib, $ic) = $arr_undertime[$i];
                        array_push($tmp, $ic);
                    }
                    $undertime = Core::GET_TIME_TOTAL($tmp);
                }
                if (count($arr_overtime) > 0) {
                    $tmp = [];
                    for ($i=0; $i < count($arr_overtime); $i++) { 
                        list($ia, $ib, $ic) = $arr_overtime[$i];
                        array_push($tmp, $ic);
                    }
                    $overtime = Core::GET_TIME_TOTAL($tmp);
                }
                $weekdayhrs = Core::GET_TIME_TOTAL($arr_weekdayhrs);
                $weekendhrs = Core::GET_TIME_TOTAL($arr_weekendhrs);

                if ($r->gtype == "OVERTIME") {
                    $totaldays = $totalovertime;
                    $weekdayhrs = Core::GET_TIME_TOTAL([$overtime, $weekendhrs]);
                    $totalabsent = 0;
                }
            } catch (\Exception $e) {
                ErrorCode::Generate('controller', 'GenerateDTRController', '00003', $e->getMessage());
                return "error";
            }

            $record = null;
            if (DB::table('hr_dtr_sum_hdr')->where('empid', $employee->empid)->where('date_from', $pp->from)->where('date_to', $pp->to)->first()!=null) {
                $record = 1;
            }

            $flag = EmployeeFlag::chk_flagged($r->code);


            /**
            * @return :
            *    workdays
            *    daysworked
            *    absences
            *    late
            *    undertime
            *    weekdayhrs
            *    isgenerated
            *    flag
            *    date_from2
            *    date_to2
            */
            $data = [
                // 'employee'=>$employee,
                'empname'=>$name,
                'flag' => $flag,
                'date_from2'=>date('M d, Y', strtotime($pp->from)),
                'date_to2'=> date('M d, Y', strtotime($pp->to)),
                'req_hrs' => Core::ToHours($req_hrs2),

                'empid'=>$employee->empid,
                'ppid' => $r->pp,
                'date_from'=>$pp->from,
                'date_to'=> $pp->to,
                // date_generated
                // time_generated
                // code
                // generatedby
                'generateType' => $r->gtype,

                // empid
                // dtr_sum_id
                'isgenerated'=>$record,
                // cancel
                'workdays'=> $workdays,
                'weekends'=> $totalweekend,
                'daysworked'=> $totaldays,
                'days_worked_arr'=> $arr_daysworked,
                'absences'=> $totalabsent,
                'late'=> $late,
                'late_arr'=> $arr_late,
                'undertime'=> $undertime,
                'undertime_arr'=> $arr_undertime,
                'overtime'=>$overtime,
                'total_overtime_arr'=> $arr_overtime,
                'weekdayhrs' => $weekdayhrs,
                'weekendhrs' => $weekendhrs,
                'holidays'=>0,
                // holiday_dates
                // holiday_arr
                // lnsum_no


                'errors'=>$errors,
                '_errors2'=>$errors2,
                '_trash' => [$tl_in_trsh, $tl_out_trsh]
            ];

            if ($flag) {
                $data['daysworked'] = $data['workdays'];
                $data['absences'] = 0;
                $data['holidays'] = 0;
                $data['late'] = "00:00:00";
                $data['undertime'] = "00:00:00";
                $data['overtime'] = "00:00:00";
            }

            if ($r->gtype == "OVERTIME") {
                $data['req_hrs'] = null;
            }

            // Session::forget('dtr_summary');
            // Session::put('dtr_summary', $data);

            return json_encode($data);
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00000', $e->getMessage());
            // return $e->getMessage();
            return "error";
        }
    }

    public function SaveDTR(Request $r)
    {
        return [$this->Save($r), "indv"];
    }

    public function Save($r)
    {
        /**
        * @param $r->dtrs
        * @param $r->empid
        * @param $r->ppid
        * @param $r->month
        * @param $r->year
        */
        try {
            if (isset($r->dtrs['errors'])) {
                if (count($r->dtrs['errors']) > 0) {
                    return "existing-error";
                }
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
                            'generatedby' => Account::ID()
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
                        $pp = Payroll::PayrollPeriod2($r->month, $dh[$i]->ppid, $r->year);
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

    public function GenerateByEmployee(Request $r) //continue here
    {
        /**
        * From request
        * @param $r->dtrs
        * @param $r->ppid
        * @param $r->ofc_id
        * @param $r->month
        * @param $r->year
        */
        
        // return dd($r->all());
        try {
            $employees = Office::OfficeEmployees($r->ofc_id);
            $arr = [];
            $employees = json_decode($employees);
            if (count($employees) > 0) {
                for ($i=0; $i < count($employees); $i++) {
                    $emp = $employees[$i];
                    $pp = Payroll::PayrollPeriod2($r->month, $r->ppid, $r->year);
                    $s = (object)[];
                    $s->code = $emp->empid;
                    $s->pp = $r->ppid;
                    $s->month = $r->month;
                    $s->year = $r->year;
                    $t = (object)[];
                    $t->dtrs = (array)json_decode($this->Generate($s));
                    $t->empid = $emp->empid;
                    $t->ppid = $r->ppid;
                    $t->month = $r->month;
                    $t->year = $r->year;
                    array_push($arr, $this->Save($t));
                }
                return [$arr, "group"];
            } else {
                return "no employees";
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', '00006', $e->getMessage());
            return "error";
        }
    }

    public function CheckDTR($empid, $pp, $from, $to)
    {
        $select = "SELECT b.code, b.ppid, b.date_from, b.date_to, b.date_generated, b.time_generated, a.* FROM hris.hr_dtr_sum_employees a INNER JOIN hris.hr_dtr_sum_hdr b ON a.dtr_sum_id = b.code";
        $con = " WHERE a.isgenerated = 1 AND b.empid = '".$empid."' AND b.ppid = '".$pp."' AND b.date_from = '".$from."' AND b.date_to = '".$to."' LIMIT 1";
        return Core::sql($select.$con);
    }
}
