<?php

namespace App\Http\Controllers\Timekeeping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MFile\OfficeController;
use App\Http\Controllers\Biometrics\BiometricsController;
use Core;
use DB;
use Account;
use Employee;
use EmployeeStatus;
use EmployeeFlag;
use ErrorCode;
use Holiday;
use Leave;
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


    public function getEmployeeWithGenerated(Request $r){
        $sql = Core::sql("SELECT DISTINCT a.empid, a.empname, a.jobtitle, COALESCE(b.cc_desc, 'no-assigned-office') cc_desc FROM (SELECT empid, firstname, lastname, mi, CONCAT(lastname, ', ',firstname) AS empname, section, CAST(positions AS INTEGER) positions, picture, CAST(department AS INTEGER) department, date_hired, contractual_date, prohibition_date, date_regular, date_resigned, date_terminated, CAST(empstatus AS INTEGER) empstatus, contract_days, prc, ctc, rate_type, pay_rate, biometric, sss, pagibig, philhealth, payroll_account, tin, tax_bracket, dayoff1, dayoff2, sex, birth, civil_status, religion, height, weight, father, father_address, father_contact, father_job, mother, mother_address, mother_contact, mother_job, emp_contact, home_tel, email, home_address, emergency_name, emergency_contact, em_home_address, relationship, shift_sched_from, shift_sched_sat_from, shift_sched_to, shift_sched_sat_to, fixed_rate, graduate, primary_ed, tertiary_ed, secondary_ed, post_graduate, pagibig_bracket, philhealth_bracket, shift_sched, shift_sched_sat, sss_bracket, fixed_sched, accountnumber, COALESCE(b.jtitle_name, 'no-jobtitle-assigned') jobtitle, COALESCE(c.description, 'no-employee-status') empstatus_desc FROM (SELECT * FROM hris.hr_employee WHERE cancel IS NULL ORDER BY lastname ASC) a LEFT JOIN (SELECT * FROM hris.hr_jobtitle WHERE cancel IS NULL ) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id) a LEFT JOIN (SELECT cc_code, cc_desc, active, funcid, cc_id FROM rssys.m08 WHERE active IS TRUE) b ON a.department = b.cc_id WHERE cc_id = '".$r->ofc_id."' AND empstatus = '".$r->emp_status."'");
        if(isset($sql)){
            foreach($sql as $key => $value){
                $value->isgenerated = Employee::isGeneratedOnDTR($value->empid, Date('Y-m-d',strtotime($r->monthFrom)), Date('Y-m-d',strtotime($r->monthTo)), $r->gtype);
            }
        }
        return json_encode($sql);

    }

    public function LoadDTRHistory()
    {
        // return DB::table('hr_dtr_sum_hdr')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'ASC')->get();
        return Core::sql("SELECT b.empid , ppid, date_from, date_to, CONCAT(date_from, ' to ', date_to) AS pp, date_generated, time_generated, code, generatedby, generationtype, empname,  mi, positions, jobtitle FROM hris.hr_dtr_sum_hdr a INNER JOIN (SELECT empid, CONCAT(firstname, ' ',lastname) AS empname, mi, CAST(positions AS INTEGER) positions, COALESCE(b.jtitle_name, 'office-not-found') jobtitle, CAST(department AS INTEGER) department, rate_type, pay_rate, biometric, empstatus, COALESCE(c.description, 'employee status-not-found') empstatus_desc, sss, sss_bracket, pagibig, pagibig_bracket, philhealth, philhealth_bracket, payroll_account, tin, tax_bracket, accountnumber, emptype, fixed_sched FROM hris.hr_employee a LEFT JOIN (SELECT jtid, jtitle_name, jt_cn FROM hris.hr_jobtitle WHERE cancel IS NULL) b ON a.positions = b.jt_cn LEFT JOIN (SELECT statcode, description, CAST(status_id AS TEXT) status_id, type FROM hris.hr_emp_status WHERE cancel IS NULL) c ON a.empstatus = c.status_id WHERE a.cancel IS NULL ORDER BY empname ASC) b ON a.empid = b.empid");
    }

    public function GenerateDTR(Request $r)
    {
        return $this->Generate($r);
    }

    public function Generate($r)
    {
        
        // return $r->all();
        /*
        * Retrieves timelog info (time in / time out of selected employee)
        * Computes the total time of late, overtime, and undertime. Also sorts the records for other reports to use
        * Some values are from different models so be wary when changing values that came from other models/controllers for some of them are ALSO connected to other models/controllers as well
        */
        /**
        * @param $r->code
        * @param $r->pp
        * @param $r->month
        * @param $r->year
        * @param $r->gtype
        */
        try {
            // $bio = new BiometricsController();
            $req_hrs = Timelog::ReqHours();
            $req_hrs2 = Timelog::ReqHours2(true);
            $employee = Employee::GetEmployee($r->code);
            $name = Employee::Name($r->code);
            // $pp = Payroll::PayrollPeriod2($r->month,$r->pp, $r->year);
            // $covereddates = Core::CoveredDates($pp->from, $pp->to);

            /*
            * For Delay periods
            *
            */
            $origMonthFrom = $r->monthFrom;
            $origMonthTo = $r->monthTo;

            // $r->monthFrom = Date('Y-m-d',strtotime('-1 month',strtotime($r->monthFrom)));
            // $r->monthTo = Date('Y-m-d',(Date('j',strtotime($r->monthTo)) > Date('j',strtotime('last day of previous month',strtotime($r->monthTo))) ? strtotime(Date('Y-m-d',strtotime('last day of previous month',strtotime($r->monthTo)))) : strtotime('-1 month',strtotime($r->monthTo))));
            $covereddates = Core::CoveredDates($r->monthFrom, $r->monthTo);
            // return $covereddates;
            if ($employee == null) {
                return "noemp";
            }

            $late = "00:00";
            $arr_late = $obArr = [];
            $undertime = "00:00";
            $arr_undertime = $arr_leave_data = $arr_leave_deduction = [];
            $overtime = "00:00";
            $arr_overtime = [];
            $weekdayhrs = "00:00";
            $arr_weekdayhrs = $sample = [];
            $weekendhrs = "00:00";
            $arr_weekendhrs = [];
            $arr_daysworked = [];
            $arr_holidays = [];
            $arr_holidayDates = [];
            $arr_leavedates = [];

            $leaveID = [];
            $totaldays = 0;
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

                $r_time_total = "00:00";
                $r_time_am = "00:00";
                $r_time_pm = "00:00";

                $r_time_ot_total = "00:00";
                $r_time_ot_total_arr = [];
                $r_time_ot_arr = [];

                /**
                * Holiday array format [date, holiday type]
                */
                if (Timelog::IfHoliday($date)) {
                    array_push($arr_holidayDates, [$date, Holiday::HolidayType2($date)]);
                    $totalholiday+=1;
                }

                /**
                * Check Timelogs
                */
                // if (Timelog::IfLeave($employee->empid, $date)) {
                $empleave = Leave::GetLeaveRecordPerMonth($employee->empid,$r->monthFrom, $r->monthTo);
                // return $empleave;
                if (count($empleave) > 0) {
                    /**
                    * Leave array format : [date, leave type]
                    */
                    foreach ($empleave as $key => $value) {
                        if(!in_array($value->lvcode, $leaveID)){
                            array_push($leaveID, $value->lvcode);
                            array_push($arr_leavedates, [$date, $value->leave_type, $value->lvcode]);
                            $totalleave+=1;
                        }
                    }
                } 

                if (count($rec_ti) > 0) {
                    if (count($rec_to) > 0) {
                        $rec_ti = explode(",", $rec_ti[0]->time_log);
                        $rec_to = explode(",", $rec_to[0]->time_log);
                        array_push($sample, [$rec_ti,$rec_to]);
                        try {
                            /**
                            * Time Validating Method
                            */
                            $tl_ti = "";
                            if (count($rec_ti) > 0) {
                                // for ($j=0; $j < count($rec_ti); $j++) { 
                                //     $tl_ti = $rec_ti[$j];
                                //     if (Timelog::ValidateLog_AM($tl_ti) && $tl_in_am == "00:00") {
                                //         $tl_in_am = $tl_ti;
                                //     } elseif (Timelog::ValidateLog_PM($tl_ti) && $tl_in_pm == "00:00") {
                                //         $tl_in_pm = $tl_ti;
                                //     } elseif(Timelog::ValidateLog_OTHrs2($tl_ti)) {
                                //         array_push($tl_in_ot, $j."|".$tl_ti);
                                //     } else {
                                //         array_push($tl_in_trsh, [$date, $tl_ti]);
                                //     }
                                // }
                            }
                            $tl_ti = "";
                            if (count($rec_to) > 0) {
                                for ($j=0; $j < count($rec_to); $j++) { 
                                    $tl_ti = $rec_to[$j];
                                    /*if (Timelog::ValidateLog_AM($tl_ti) && $tl_out_am == "00:00") {
                                        $tl_out_am = $tl_ti;
                                    } elseif (Timelog::ValidateLog_PM($tl_ti) && $tl_out_pm == "00:00") {
                                        $tl_out_pm = $tl_ti;
                                    } elseif(Timelog::ValidateLog_OTHrs2($tl_ti)) {*/
                                    if(Timelog::isOT($tl_ti)){
                                        array_push($tl_out_ot, $j."|".$tl_ti);
                                    } else {
                                        array_push($tl_out_trsh, [$date, $tl_ti]);
                                    }
                                }
                            }
                            $tl_ti = $tl_to = '';
                            if(count($rec_to) == count($rec_ti)){
                                for ($k=0; $k < count($rec_to); $k++) { 
                                    $tl_ti = $rec_ti[$k];
                                    $tl_to = $rec_to[$k];
                                    
                                    if(isset($tl_ti)){
                                        $tl_in_am = ($rec_ti[0] ?? 00.00);
                                        $tl_in_pm = ($rec_ti[1] ?? 00.00);
                                    }

                                    if(isset($tl_to)){
                                        $tl_out_am = ($rec_to[0] ?? 00.00);
                                        $tl_out_pm = ($rec_to[1] ?? 00.00);
                                    }
                                }
                            }



                        } catch (\Exception $e) {
                            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00001', $e->getMessage());
                            return $e;
                            return "error";
                        }

                        $temp_tl = "";
                        try {   
                            /**
                            * Time Sorting Method
                            * -------------------------------------------------------
                            * timelog array format [timein_am, timeout_am, timein_pm, timeout_pm] OR [timein, timeout] OR array of [timein, timeout]
                            * imploded array format : [date, (array)timelog, computed time]
                            *
                            * Holiday array have unique array format due to the date's holiday type that must be included for payroll
                            * holiday array format : [[date, holiday type], timelog, computed time]
                            */
                            if ($tl_in_am != "00:00" && $tl_out_am != "00:00") { // ami = 1, amo = 1
                                if ($tl_in_pm != "00:00" && $tl_out_pm != "00:00") { // pmi = 1, pmo = 1
                                    $r_time_am = Timelog::GetRenHours2($tl_in_am, $tl_out_am);
                                    $r_time_pm = Timelog::GetRenHours($tl_in_pm, $tl_out_pm);
                                    // return [$tl_in_am, $tl_out_am,$r_time_am];
                                    // $r_time_total = Core::GET_TIME_DIFF(Timelog::get_lunch_break(), Core::GET_TIME_TOTAL([$r_time_am, $r_time_pm]));
                                    $r_time_total = Core::GET_TIME_TOTAL([$r_time_am, $r_time_pm]);
                                    $forLeaveDeduction = Core::isEnoughLeave(Core::GET_TIME_DIFF($r_time_total, $req_hrs2),$employee->empid);
                                    // return [$r_time_am,$r_time_pm,$r_time_total,$req_hrs2];
                                    // return [[$r_time_am,$tl_in_am,$tl_out_am],[$r_time_pm,$tl_in_pm,$tl_out_pm],[$r_time_total],[Core::GET_TIME_TOTAL([$r_time_am, $r_time_pm])]];

                                    # If Late
                                    // if (Timelog::IfLate($tl_in_am)) {
                                    if (Timelog::isLate($tl_in_am,'am') || Timelog::isLate($tl_in_pm,'pm')) {
                                        // array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am) ]);
                                        if(!$forLeaveDeduction){
                                            array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_in_am,$tl_in_pm,'late') ]);
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Undertime
                                    // if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                    if(Timelog::isUndertime($tl_out_am,'am') || Timelog::isUndertime($tl_out_pm,'pm')){
                                        // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                        if(!$forLeaveDeduction){
                                            // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2) ]);
                                            array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_out_am,$tl_out_pm) ]); 
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Holiday
                                    if (Timelog::IfHoliday($date)) {
                                        array_push($arr_holidays, [[$date, Holiday::HolidayType2($date)], [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                    } else {
                                        array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                    }
                                } elseif ($tl_in_am != "00:00" && $tl_out_pm != "00:00") { // ami = 1, pmo = 1
                                    $r_time_total = Timelog::GetRenHours($tl_in_am, $tl_out_pm, true);
                                    # If Late
                                    // if (Timelog::IfLate($tl_in_am)) {
                                    if (Timelog::isLate($tl_in_am,'am') || Timelog::isLate($tl_in_pm,'pm')) {
                                        // array_push($arr_late, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am)]);
                                        if(!$forLeaveDeduction){
                                            array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_in_am,$tl_in_pm,'late') ]);
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Undertime
                                    // if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                    if(Timelog::isUndertime($tl_out_am,'am') || Timelog::isUndertime($tl_out_pm,'pm')){
                                        // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                        if(!$forLeaveDeduction){
                                            // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                            array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_out_am,$tl_out_pm) ]); 
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Holiday
                                    if (Timelog::IfHoliday($date)) {
                                        array_push($arr_holidays, [[$date, Holiday::HolidayType2($date)], [$tl_in_am, $tl_out_pm], $r_time_total]);
                                    } else {
                                        array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                    }
                                } else { // ami = 1, amo = 1
                                    $r_time_total = Timelog::GetRenHours($tl_in_am, $tl_out_am);
                                    # If Late
                                    // if (Timelog::IfLate($tl_in_am)) {
                                    if (Timelog::isLate($tl_in_am,'am') || Timelog::isLate($tl_in_pm,'pm')) {
                                        // array_push($arr_late, [$date, [$tl_in_am, $tl_out_am], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am)]);
                                        if(!$forLeaveDeduction){
                                            array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_in_am,$tl_in_pm,'late') ]);
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Undertime
                                    // if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                    if(Timelog::isUndertime($tl_out_am,'am') || Timelog::isUndertime($tl_out_pm,'pm')){
                                        // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                        if(!$forLeaveDeduction){
                                            // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                            array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_out_am,$tl_out_pm) ]); 
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                    }
                                    # If Holiday
                                    if (Timelog::IfHoliday($date)) {
                                        array_push($arr_holidays, [[$date, Holiday::HolidayType2($date)], [$tl_in_am, $tl_out_pm], $r_time_total]);
                                    } else {
                                        array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                    }
                                }
                            } elseif ($tl_in_am != "00:00" && $tl_out_pm != "00:00") { // ami = 1, pmo = 1
                                $r_time_total = Timelog::GetRenHours($tl_in_am, $tl_out_pm, true); 
                                # If Late
                                // if (Timelog::IfLate($tl_in_am)) {
                                if (Timelog::isLate($tl_in_am,'am') || Timelog::isLate($tl_in_pm,'pm')) {
                                    // array_push($arr_late, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF(Timelog::ReqTimeIn(), $tl_in_am)]);
                                    if(!$forLeaveDeduction){
                                            array_push($arr_late, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_in_am,$tl_in_pm,'late') ]);
                                    } else {
                                        if($forLeaveDeduction[1] > 0){
                                            array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                        }
                                        array_push($arr_leave_data, [$forLeaveDeduction]);
                                    }
                                }
                                # If Undertime
                                // if (Timelog::IfUndertime($r_time_total, $req_hrs2)) {
                                if(Timelog::isUndertime($tl_out_am,'am') || Timelog::isUndertime($tl_out_pm,'pm')){
                                    // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]); 
                                    if(!$forLeaveDeduction){
                                            // array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Core::GET_TIME_DIFF($r_time_total, $req_hrs2)]);
                                            array_push($arr_undertime, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], Timelog::computeForDeduction($tl_out_am,$tl_out_pm) ]);  
                                        } else {
                                            if($forLeaveDeduction[1] > 0){
                                                array_push($arr_leave_deduction, [$forLeaveDeduction]);
                                            }
                                            array_push($arr_leave_data, [$forLeaveDeduction]);
                                        }
                                }
                                # If Holiday
                                if (Timelog::IfHoliday($date)) {
                                    array_push($arr_holidays, [[$date, Holiday::HolidayType2($date)], [$tl_in_am, $tl_out_pm], $r_time_total]);
                                } else {
                                    array_push($arr_daysworked, [$date, [$tl_in_am, $tl_out_am, $tl_in_pm, $tl_out_pm], $r_time_total]);
                                }
                            }

                            # OVERTIME DAYS WORKED
                            // if (count($tl_in_ot) > 0) {
                            //     for ($j=0; $j < count($tl_in_ot); $j++) {
                            //         list($ja, $jb) = explode("|", $tl_in_ot[$j]);
                            //         for ($k=0; $k < count($tl_out_ot); $k++) {
                            //             list($ka, $kb) = explode("|", $tl_out_ot[$k]);
                            //             if ($ja == $ka) {
                            //                 $jk = Timelog::GetRenHours($jb, $kb);
                            //                 if (Timelog::IfOvertime($jk)) {
                            //                     array_push($r_time_ot_arr, [$jb, $kb, $jk]);
                            //                 }
                            //             }
                            //         } 
                            //     }
                            // }
                            if (count($tl_out_ot) > 0) {
                                for ($k=0; $k < count($tl_out_ot); $k++) {
                                    list($ka, $kb) = explode("|", $tl_out_ot[$k]);
                                    $jk = Timelog::GetRenHours(Timelog::adjustFormattedTimestamp(Timelog::ReqTimeOut_2(),0,1), $kb);
                                    if(Timelog::isPassedOnRequiredOT($jk)){
                                        array_push($arr_overtime, [$date, [Timelog::adjustFormattedTimestamp(Timelog::ReqTimeOut_2(),0,1), $kb], $jk]);
                                        $totalovertime+=1;
                                    }
                                }
                            }
                            // if (count($r_time_ot_arr) > 0) {
                            //     $tmp = [];
                            //     for ($j=0; $j < count($r_time_ot_arr); $j++) { 
                            //         list($ja, $jb, $jc) = $r_time_ot_arr[$j];
                            //         array_push($tmp, [[$ja, $jb], $jc]);
                            //     }
                            //     for ($j=0; $j < count($tmp); $j++) { 
                            //         list($ja, $jb) = $tmp[$j];
                            //         array_push($arr_overtime, [$date, $ja, $jb]);
                            //     }
                            //     $totalovertime+=1;
                            // }
                        } catch (\Exception $e) {
                            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00002', $e->getMessage());
                            return $e;
                            return "error";
                        }
                    } else {
                        // missing logs
                        /**
                        * Error array format [date, reason]
                        */
                        array_push($errors, [$date, "Missing Timelogs"]);
                    }
                } else {
                    // absent
                    // array_push($obArr, [$sql_ti,$sql_to]);
                    /*
                    *for checking of OB
                    */
                    $forOB = DB::table('hr_ob')->where([['empid',$employee->empid],['datefrom','>=',$date],['dateto','<=',$date],['active',TRUE]])->first();
                    // return $forOB;
                    if(isset($forOB)){
                        array_push($obArr, json_encode($forOB));
                        array_push($arr_daysworked, [$date, ["OB", "OB", "OB", "OB"], "08:00"]);
                    }

                }

                // Just reference
                array_push($errors2, [
                    $date, 
                    "AM_I" => $tl_in_am, 
                    "AM_O" => $tl_out_am, 
                    "PM_I" => $tl_in_pm, 
                    "PM_O" => $tl_out_pm, 
                    "OT_T" => $r_time_ot_arr, 
                    "OT" => $r_time_ot_total
                ]);


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
                // for pp (not for monthly from and to)
                // $workdays = 22 / 2;
                // $totaldays = count($arr_daysworked);
                // $totalabsent = ($workdays - $totaldays) - count($arr_leavedates);
                $conditionForFirstDay = (Date('j',strtotime($r->monthFrom)) != 1 ? Date('Y-m-d',strtotime('-1 day',strtotime($r->monthFrom))) : $r->monthFrom);
                $workdays = Core::CountWorkingDays($conditionForFirstDay,$r->monthTo);
                $totaldays = count($arr_daysworked);
                // return [$totaldays, ((int)$workdays), count($arr_leavedates)];
                // return $arr_daysworked;
                // $totalabsent = (((int)$workdays)) - $totaldays - count($arr_leavedates);
                $totalabsent = ((((int)$workdays)) - $totaldays - count($arr_leavedates)/* - $totaldays*/);

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
                    $tmp = [];
                    for ($i=0; $i < count($arr_holidays); $i++) { 
                        list($ia, $ib, $ic) = $arr_holidays[$i];
                        array_push($tmp, $ic);
                        $totalovertime+=1;
                    }
                    $totaldays = $totalovertime;
                    $tmp = Core::GET_TIME_TOTAL($tmp);
                    $weekdayhrs = Core::GET_TIME_TOTAL([$overtime/*, $weekendhrs, $tmp*/]);
                    $totalabsent = 0;
                }
            } catch (\Exception $e) {
                ErrorCode::Generate('controller', 'GenerateDTRController', 'A00003', $e->getMessage());
                return $e;
                return "error";
            }

            $record = null;
            if (DB::table('hr_dtr_sum_hdr')->where('empid', $employee->empid)->where('date_from', /*$pp->from*/$r->monthFrom)->where('date_to', /*$pp->to,*/$r->monthTo)->where('generationtype', $r->gtype)->first()!=null) {
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
                'date_from2'=>date('M d, Y', strtotime(/*$pp->from$r->monthFrom$origMonthFrom*/$r->monthFrom)),
                'date_to2'=> date('M d, Y', strtotime(/*$pp->to$r->monthTo$origMonthTo*/$r->monthTo)),
                'req_hrs' => Core::ToHours($req_hrs2),

                'empid'=>$employee->empid,
                'ppid' => $r->pp,
                // 'date_from'=>$r->monthFrom,
                // 'date_to'=> $r->monthTo,
                'date_from'=>$origMonthFrom,
                'date_to'=> $origMonthTo,
                // date_generated
                // time_generated
                // code
                // generatedby
                'generateType' => $r->gtype,

                // empid
                // dtr_sum_id
                'isgenerated'=>$record,
                // cancel
                // lnsum_no
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
                'holidays'=>$totalholiday,
                'holiday_dates' => $arr_holidayDates,
                'holiday_arr' => $arr_holidays,
                'leaves'=> $totalleave,
                'leaves_arr'=> $arr_leavedates,
                'updateToGenerate' => $leaveID,
                'ob' => json_encode($obArr),
                'forDeductOnLeave' => json_encode(($r->gtype == "OVERTIME" ? [] : $arr_leave_data)),
                'forDeductNextPayroll' => json_encode(($r->gtype == "OVERTIME" ? [] : $arr_leave_deduction)),

                'errors'=>$errors,
                '_errors2'=>$errors2,
                '_trash' => [$tl_in_trsh, $tl_out_trsh],
                'try' => $sample
            ];

            if ($flag) {
                $data['daysworked'] = $data['workdays'];
                $data['absences'] = 0;
                $data['late'] = "00:00";
                $data['undertime'] = "00:00";
                $data['overtime'] = "00:00";
            }

            if ($r->gtype == "OVERTIME") {
                $data['req_hrs'] = null;
            }

            // Session::forget('dtr_summary');
            // Session::put('dtr_summary', $data);

            return json_encode($data);
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', 'A00000', $e->getMessage());
            return $e->getMessage();
            return "error";
        }
    }

    public function Save($r)
    {
        /**
        * @param $r->dtrs - From Generate()
        */
        // return $r;
        try {
            if (isset($r->dtrs['errors'])) {
                if (count($r->dtrs['errors']) > 0) {
                    return "existing-error";
                }
            }
            if ($r->dtrs == null) {
                ErrorCode::Generate('controller', 'GenerateDTRController', 'B00001', "Missing Parameter (DTRS)");
                return "error";
            }

            $dtrs = $r->dtrs;
            $message = null;
            // $dtrs = (array)json_decode($this->Generate($r));

            // $record = DB::table('hr_dtr_sum_hdr')->where('empid', $dtrs['empid'])->where('date_from', $dtrs['date_from'])->where('date_to', $dtrs['date_to'])->where('generationtype', $data['generateType'])->first();

            // for removed trap by Syrel
            // to continue history 
            if($dtrs['isgenerated'] != null){
                $codeFromSumHdr = DB::table('hr_dtr_sum_hdr')->where([['empid',$dtrs['empid']],['ppid',$dtrs['ppid']],['generationtype',$dtrs['generateType']],['date_from',$dtrs['date_from']],['date_to',$dtrs['date_to']]])->first();
                $code = $codeFromSumHdr->code;
                DB::table('hr_dtr_sum_hdr')->where([['empid',$dtrs['empid']],['ppid',$dtrs['ppid']],['generationtype',$dtrs['generateType']],['date_from',$dtrs['date_from']],['date_to',$dtrs['date_to']]])->delete();
                DB::table('hr_dtr_sum_employees')->where([['xempid',$dtrs['empid']],['isgenerated',TRUE],['dtr_sum_id',$code]])->delete();
                $dtrs['isgenerated'] = null;
                $message = 'update';
            }
            if (/*$record == null*/ $dtrs['isgenerated'] == null) {
                
                try {
                    $code = Core::getm99('dtr_sum_id');
                    $reply = false;
                    // return $r;
                    try {
                        DB::table('hr_dtr_sum_hdr')->insert([
                            'empid' => $dtrs['empid'],
                            'ppid' => $dtrs['ppid'],
                            'date_from' => $dtrs['date_from'],
                            'date_to' => $dtrs['date_to'],
                            'date_generated' => date('Y-m-d'),
                            'time_generated' => date('H:m:s'),
                            'code'=> $code,
                            'generatedby'=> Account::ID(),
                            'generationtype'=> $dtrs['generateType'],
                            'orig_date_from' => $dtrs['date_from2'],
                            'orig_date_to' => $dtrs['date_to2'],
                        ]);

                        if(isset($dtrs['updateToGenerate'])){
                            DB::table('hr_leaves')->whereIn('lvcode',$dtrs['updateToGenerate'])->update(['isgenerated' => TRUE]);
                        }

                        $reply = true;
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GenerateDTRController', 'B00003', $e->getMessage());
                        return $e;
                    }
                    if ($reply) {
                        Core::updatem99('dtr_sum_id',Core::get_nextincrementlimitchar($code, 8));
                        $sql = DB::table('hr_dtr_sum_employees');
                        $record = $sql->where('xempid', $dtrs['empid'])->where('dtr_sum_id', $code);
                        $data = [
                            'xempid' => $dtrs['empid'],
                            'dtr_sum_id' => $code,
                            'isgenerated' => 0,
                            // 'cancel' => , // Set to blank
                            // 'lnsum_no', // Auto increment
                            'workdays' => $dtrs['workdays'],
                            'weekends' => $dtrs['weekends'],
                            'days_worked' => $dtrs['daysworked'],
                            'days_worked_arr' => ((isset($dtrs['days_worked_arr'])) ? json_encode($dtrs['days_worked_arr']) : null),
                            'days_absent' => $dtrs['absences'],
                            'late' => $dtrs['late'],
                            'late_arr' => ((isset($dtrs['late_arr'])) ? json_encode($dtrs['late_arr']) : null),
                            'undertime' => $dtrs['undertime'],
                            'undertime_arr' => ((isset($dtrs['undertime_arr'])) ? json_encode($dtrs['undertime_arr']) : null),
                            'total_overtime' => $dtrs['overtime'],
                            'total_overtime_arr' => ((isset($dtrs['total_overtime_arr'])) ? json_encode($dtrs['total_overtime_arr']) : null),
                            'weekdayhrs' => $dtrs['weekdayhrs'],
                            'weekendhrs' => $dtrs['weekendhrs'],
                            'holiday' => $dtrs['holidays'],
                            'holiday_dates' => ((isset($dtrs['holiday_dates'])) ? json_encode($dtrs['holiday_dates']) : null),
                            'holiday_arr' => ((isset($dtrs['holiday_arr'])) ? json_encode($dtrs['holiday_arr']) : null),
                            'leaves' => $dtrs['leaves'],
                            'leaves_arr' => ((isset($dtrs['leaves_arr'])) ? json_encode($dtrs['leaves_arr']) : null),
                            'ob_arr' => $dtrs['ob'],
                            'arr_leave' => $dtrs['forDeductOnLeave'],

                        ];
                        if ($record->first()==null) {
                            try {
                                $sql->insert($data);
                                if(isset($dtrs['forDeductOnLeave'])){
                                    $decoded = json_decode($dtrs['forDeductOnLeave']);
                                    foreach ($decoded as $key => $value) {
                                        if($value[0][1] <= 0){
                                            DB::table('hr_emp_leavecount')->where('elccode',$value[0][2])->update(['count' => abs($value[0][4])]);
                                        }
                                    }
                                }
                                if(isset($dtrs['forDeductNextPayroll'])){
                                    DB::table('hr_leave_deduct_next')->insert(['leavedata' => $dtrs['forDeductNextPayroll'], 't_date' => Date('Y-m-d'), 't_time' => Date('H:i:s'), 'dtr_id' => $code ]);
                                }
                                return $message;
                            } catch (\Exception $e) {
                                ErrorCode::Generate('controller', 'GenerateDTRController', 'B00004', $e->getMessage());
                                return $e;
                            }
                        } else {
                            if ($record->first()->isgenerated==0) {
                                try {
                                    $record->update($data);
                                } catch (\Exception $e) {
                                    ErrorCode::Generate('controller', 'GenerateDTRController', 'B00005', $e->getMessage());
                                    return $e;
                                }
                            } else {
                                return "isgenerated";
                            }
                        }
                    }
                    return [json_encode($this->LoadDTRHistory()), "ok"];
                } catch (\Exception $e) {
                    ErrorCode::Generate('controller', 'GenerateDTRController', 'B00002', $e->getMessage());
                    return $e;
                }
            } else {
                return "max";
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', 'B00000', $e->getMessage());
            return $e;
        }
    }

    public function SaveDTR(Request $r)
    {
        $a = (object)[];
        $a->dtrs = (array)json_decode($this->Generate($r));
        return [$this->Save($a), "indv"];
    }

    public function GenerateByEmployee(Request $r)
    {
        /**
        * From request
        * @param $r->ppid
        * @param $r->ofc_id
        * @param $r->month
        * @param $r->year
        * @param $r->gtype
        * @param $r->empstat
        */
        try {
            $reply = "error";
            $employees = Office::OfficeEmployees_byEmpStat($r->ofc_id, $r->empstat);
            $return_arr = [];
            $employees = json_decode($employees);
            if (count($employees) > 0) {
                // $pp = Payroll::PayrollPeriod2($r->month, $r->ppid, $r->year);
                for ($i=0; $i < count($employees); $i++) {
                    try {
                        $emp = $employees[$i];
                        $s = (object)[];
                        $s->code = $emp->empid;
                        $s->pp = $r->ppid;
                        // $s->month = $r->month;
                        $s->monthFrom = $r->monthFrom;
                        $s->monthTo = $r->monthTo;
                        // $s->year = $r->year;
                        $s->gtype = $r->gtype;
                        $t = (object)[];
                        $t->dtrs = (array)json_decode($this->Generate($s));
                        array_push($return_arr, $this->Save($t));
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GenerateDTRController', 'C00001', $e->getMessage());
                        array_push($return_arr, $e->getMessage());
                    }
                }
                $reply = $return_arr;
            } else{
                $reply = "no-employees";
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GenerateDTRController', 'C00000', $e->getMessage());
            $reply = $e;
        }
        return [$reply, "group"];
    }

    public function CheckDTR($empid, $pp, $from, $to)
    {
        $select = "SELECT b.code, b.ppid, b.date_from, b.date_to, b.date_generated, b.time_generated, a.* FROM hris.hr_dtr_sum_employees a INNER JOIN hris.hr_dtr_sum_hdr b ON a.dtr_sum_id = b.code";
        $con = " WHERE a.isgenerated = 1 AND b.empid = '".$empid."' AND b.ppid = '".$pp."' AND b.date_from = '".$from."' AND b.date_to = '".$to."' LIMIT 1";
        return Core::sql($select.$con);
    }
}
