<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use Account;
use EmployeeStatus;
use ErrorCode;
use PayrollPeriod;
use Employee;
use DTR;
use Holiday;
use Timelog;
use Leave;
use Loan;
use Office;
use OtherDeductions;
use Payroll;
use SSS;
use Philhealth;
use Pagibig;

class GeneratePayrollController extends Controller
{
    // Values that are called within this controller
    public function __construct()
    {
    	$this->ghistory = DB::table('hr_emp_payroll2')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'DESC')->get();
        // $this->dtrsum = DB::table('hr_dtr_sum_hdr')->join('hr_dtr_sum_employees', 'hr_dtr_sum_hdr.code', '=', 'hr_dtr_sum_employees.dtr_sum_id')->where('isgenerated', '=', 0)->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'ASC')->get();
        $this->dtrsum = Core::sql("SELECT a.empid, ppid, date_from, date_to, date_generated, time_generated, dtr_sum_id, generatedby, generationtype, isgenerated, lnsum_no, workdays, days_worked, days_worked_arr, days_absent, undertime, undertime_arr, total_overtime, total_overtime, total_overtime_arr, weekdayhrs, weekendhrs, holiday, holiday_arr, leaves leaves_arr FROM hris.hr_dtr_sum_hdr a INNER JOIN (SELECT * FROM hris.hr_dtr_sum_employees WHERE cancel IS NULL) b ON a.code = b.dtr_sum_id WHERE isgenerated IS FALSE");
        $this->office = Office::get_all();
        $this->empstatus = EmployeeStatus::get_all();
    }

    // Redirects to blade
    public function view()
    {
    	$data = [$this->office, $this->ghistory, $this->empstatus];
        // dd($data);
    	return view('pages.payroll.generate_payroll', compact('data'));
    }

    // Find dtr function
    public function find_dtr(Request $r)
    {
        /**
        * @param $r->month
        * @param $r->payroll_period
        * @param $r->year
        * @param $r->empstatus
        * @param $r->gen_type
        * @param $r->ofc
        */
        try {
            $return_val = (object)[];
            $dtr_summaries = [];
            $emp_sql = Employee::$emp_sql;
            $pp = Payroll::PayrollPeriod2($r->month, $r->payroll_period, $r->year);
            $sql = "SELECT dtr.*, emp.* FROM (SELECT * FROM hris.hr_dtr_sum_hdr a LEFT JOIN hris.hr_dtr_sum_employees b ON a.code = b.dtr_sum_id) dtr INNER JOIN ($emp_sql) emp ON dtr.empid = emp.empid";
                $con = " WHERE isgenerated IS NOT TRUE AND date_from >= '".date('Y-m-d', strtotime($pp->from))."' AND date_to <= '".date('Y-m-d', strtotime($pp->to))."' AND empstatus = '".$r->empstatus."' AND generationtype = '".$r->gen_type."' AND department = '".$r->ofc."'";
            $return_val->search = date('Y-m-d', strtotime($pp->from))." to ".date('Y-m-d', strtotime($pp->to));
            // $return_val->parameters = $r->all();
            $return_val->dtr_summaries = Core::sql($sql.$con);
            $return_val->payroll_history = Core::sql("SELECT pr.*, CONCAT(emp.lastname, ', ', emp.firstname) AS empname FROM (SELECT a.*, b.date_generated, b.time_generated FROM hris.hr_emp_payroll3 a INNER JOIN hris.hr_dtr_sum_hdr b ON a.dtr_sum_id = b.code) pr INNER JOIN ($emp_sql) emp ON pr.empid = emp.empid");
            return json_encode($return_val);
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GeneratePayrollController', 'A00001', $e->getMessage());
            return "error";
        }
    }

    public function find_payroll($empid, $ppid, $date_from, $date_to)
    {
        $db = DB::table('hr_emp_payroll2')->where('empid', '=', $empid)->where('pp_id', '=', $ppid)->where('date_from', '=', $date_from)->where('date_to', '=', $date_to)->first();
        if ($db!=null) {
            return true;
        }
        return false;
    }

    // Generate Payroll function
    public function generate_payroll(Request $r)
    {
        /**
        * @param $r->ofc
        * @param $r->empstatus
        * @param $r->month
        * @param $r->year
        * @param $r->payroll_period
        * @param $r->gen_type
        *
        * Modules within this function is divided by different different methods
        * Please read the comments
        */

        $errors = [];
        $results = [];
        $asd = [];

        # Get generated DTR Summary
        $dtr_summaries = json_decode($this->find_dtr($r))->dtr_summaries;

        # Currenty User Info
        $cur_date = date('Y-m-d');
        $cur_time = date('h:i');
        $isgenerated_by = Account::ID();
        $ip_location = $r->ip();

        try {
            # Get Payroll period
            $pp = Payroll::PayrollPeriod2($r->month,$r->payroll_period, $r->year); if ($pp =="error") { return "no pp"; }
            if (count($dtr_summaries) > 0) {
                for ($i=0; $i < count($dtr_summaries); $i++) {
                    $tmp = "";
                    $withDeductions = false;
                    try {
                        # Payroll Info
                        $d = $dtr_summaries[$i];
                        $rate = $d->pay_rate;
                        $rate_type = $d->rate_type;
                        $tax_bracket = $d->tax_bracket;

                        $workdays = (float)$d->workdays;;
                        $weekends = (float)$d->weekends;

                        $emp_pay_code = Core::getm99('emp_pay_code');
                        $pay_period = Payroll::PayPeriods();


                        if ($r->payroll_period == "15D") {
                            $withDeductions = true;
                        }

                        # Guihulngan formula
                        /**
                        * Daily Rate = Monthly Rate / 22 Days
                        * Hourly Rate = Daily Rate / 8 Hours
                        * Overtime Hourly Rate (Regular Days) = Hourly Rate + 25% of Hourly Rate
                        */
                        
                        # Shift Info
                        /**
                        * returns float
                        * returns array
                        */
                        $shift_hours = Timelog::ShiftHours(); /**  */
                        $covered_days = Core::CoveredDates($pp->from, $pp->to); /**  */
                        $total_days = $workdays + $weekends;

                        # Regular Pay
                        $regular_pay = 0;
                        if ($rate_type == "D") {
                            $regular_pay = $rate * $workdays;
                        } else {
                            $regular_pay = $rate / $pay_period;
                        }
                        
                        # Rate breakdown by time
                        /**
                        * $hourly_rate = $daily_rate / $shift_hours;
                        * $minute_rate = $hourly_rate / 60;
                        */
                        $daily_rate = 0; $daily_rate = $regular_pay / $workdays;
                        $hourly_rate = Payroll::ConvertRate($daily_rate, $shift_hours);
                        $minute_rate = Payroll::ConvertRate($hourly_rate, 60);

                        # Basic Pay
                            ## Days Worked
                            $days_worked = (float)$d->days_worked;
                            $days_worked_amt = 0; $days_worked_amt = $days_worked * $daily_rate;

                            ## Absent
                            $days_absent = $d->days_absent;
                            $days_absent_amt = 0; $days_absent_amt = $days_absent * $daily_rate;

                            ## Late
                            $late = Core::ToMinutes($d->late);
                            $late_amt = 0; $late_amt = $late * $minute_rate;

                            ## Undertime
                            $undertime = Core::ToMinutes($d->undertime);
                            $undertime_amt = 0; $undertime_amt = $undertime * $minute_rate;

                            ## Leave
                            $leaves = json_decode($d->leaves_arr);
                            $leave_count = $d->leaves;
                            $leave_amt = 0;
                            if (count($leaves) > 0) {
                                for ($j=0; $j < count($leaves); $j++) { 
                                    $leave_amt += $daily_rate;
                                };
                            }
                        $basic_pay = $days_worked_amt + $leave_amt;

                        # Gross Pay Computation
                            ## Regular Overtime
                            /**
                            * Array definition respectively: date, timelog, hours
                            */
                            $regular_ot = [];
                            $regular_ot_amt = 0;
                            $dayoff_ot = [];
                            $dayoff_ot_amt = 0;
                            $overtime_arr = json_decode($d->total_overtime_arr);
                            if (count($overtime_arr) > 0) {
                                for ($j=0; $j < count($overtime_arr); $j++) {
                                    list($ota_date, $ota_timelogs, $ota_rhrs) = $overtime_arr[$j];
                                    $ota_rvalue = Core::ToHours($ota_rhrs) * $hourly_rate;
                                    if (Timelog::IfWorkdays($ota_date)) {
                                        $regular_ot_amt += $ota_rvalue;
                                        array_push($regular_ot, [$ota_date, $ota_timelogs, $ota_rhrs]);
                                    } else {
                                        $dayoff_ot_amt += $ota_rvalue;
                                        array_push($dayoff_ot, [$ota_date, $ota_timelogs, $ota_rhrs]);
                                    }
                                }
                            }

                            ## Holiday
                            /**
                            * Holiday array = [date, timelog]
                            * exempted = complete timelog
                            * no-log = absent
                            */
                                ### Worked/Holiday OT
                                $legal_holiday_ot = [];
                                $legal_holiday_ot_amt = 0;
                                $special_holiday_ot = [];
                                $special_holiday_ot_amt = 0;
                                $holiday_arr = json_decode($d->holiday_arr);
                                if (count($holiday_arr) > 0) {
                                    for ($j=0; $j < count($holiday_arr); $j++) { 
                                        list($ha_log, $ha_timelog, $ha_rtime) = $holiday_arr[$j];
                                        list($ha_date, $ha_type) = $ha_log;
                                        $ha_percent = Holiday::HolidayPercentage($ha_type)->work / 100 ;
                                        $ha_amt = 0; $ha_amt = $daily_rate * $ha_percent;
                                        switch ($ha_type) {
                                            case 'RH':
                                                array_push($legal_holiday_ot, [$ha_date, $ha_timelog]);
                                                $legal_holiday_ot_amt += $ha_amt;
                                                break;
                                            case 'SH':
                                                array_push($special_holiday_ot, [$ha_date, $ha_timelog]);
                                                $special_holiday_ot_amt += $ha_amt;
                                                break;
                                            
                                            default:
                                                # Error retrieving leave type
                                                ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00004-HOT-'.$d->empid, $e->getMessage());
                                                array_push($errors, 'B00004-HOT-'.$d->empid."-".$j.": Unknown holiday type.");
                                                break;
                                        }
                                    }
                                }

                                ### Not worked Holiday
                                $legal_holiday_pay = [];
                                $legal_holiday_pay_amt = 0;
                                $special_holiday_pay = [];
                                $special_holiday_pay_amt = 0;
                                $hdays_arr = json_decode($d->holiday_dates);
                                if (count($hdays_arr) > 0) {
                                    for ($j=0; $j < count($hdays_arr); $j++) { 
                                        list($hda_date, $hda_type) = $hdays_arr[$j];
                                        $hda_percent = Holiday::HolidayPercentage($hda_type)->nowork / 100 ;
                                        $hda_amt = 0; $hda_amt = $daily_rate * $hda_percent;
                                        switch ($hda_type) {
                                            case 'RH':
                                                array_push($legal_holiday_pay, [$hda_date, "exempted"]);
                                                $legal_holiday_pay_amt += $hda_amt;
                                                break;
                                            case 'SH':
                                                array_push($special_holiday_pay, [$hda_date, "exempted"]);
                                                $special_holiday_pay_amt += $hda_amt;
                                                break;
                                            
                                            default:
                                                /** Error retrieving leave type */
                                                ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00004-HD-'.$d->empid, $e->getMessage());
                                                array_push($errors, 'B00004-HD-'.$d->empid."-".$j.": Unknown holiday type.");
                                                break;
                                        }
                                    }
                                }

                            ## Other Earnings
                            /** 
                            * Other earnings array format = [earnings id, earning code, amount]
                            * -Temporary IDs:
                            * P1 - PERA = 2000
                            * HP1 - Hazard Pay
                            * A1 - Laundry = 150
                            * A2 - Clothing Allowance = 6000; If Employee is still under 6 years in service
                            */
                            $other_earnings = [];
                            $other_earnings_amt = 0;
                            $earnings_arr = $this->GetOtherEarnings($d->empid, $pp->from, $pp->to);
                            if (count($earnings_arr) > 0) {
                                for ($j=0; $j < count($earnings_arr); $j++) { 
                                    $ea = $earnings_arr[$j];
                                    $ea_amnt = $ea->amount; $ea_id = $ea->id;
                                    $other_earnings_amt += $ea_amnt;
                                    array_push($other_earnings, [$ea->entcode, $ea->earning_code, $ea_amnt]);
                                }
                            }
                                ### PERA
                                $other_earnings_amt += 2000;
                                array_push($other_earnings, ["P1", "PERA", 2000]);
                                ### Hazard Pay
                                $hazard_pay_amt = 0;
                                $hazard_pay = $this->GetHazardPay($d->department);
                                if ($hazard_pay!=null) {
                                    if ($hazard_pay->withpay) {
                                        $hazard_pay_amt = (float)$basic_pay * ((float)$hazard_pay->hp_pct / 100);
                                    }
                                }
                                $other_earnings_amt += $hazard_pay_amt;
                                array_push($other_earnings, ["HP1", "HAZARDPAY", $hazard_pay_amt]);
                                ### ALLOWANCE
                                    #### LAUNDRY
                                    $alw_laundry_amt = 150;
                                    $other_earnings_amt += $alw_laundry_amt;
                                    array_push($other_earnings, ["A1", "ALLOWNC", $alw_laundry_amt]);
                                    #### CLOTHING
                                    $alw_clothing = $this->GetAlw_Clothing($d->empid);
                                    if (count($alw_clothing) > 0) {
                                        if ($alw_clothing[0]->service_overall <= 6) {
                                            $alw_clothing_amt = 6000;
                                            $other_earnings_amt += $alw_clothing_amt;
                                            array_push($other_earnings, ["A2", "ALLOWNC", $alw_clothing_amt]);
                                        }
                                    }

                        $gross_pay = $regular_ot_amt + $dayoff_ot_amt + $legal_holiday_pay_amt + $special_holiday_pay_amt + $legal_holiday_ot_amt + $special_holiday_ot_amt + $other_earnings_amt;
                                    
                        # Deductions
                            ## Personal Deductions
                            /**
                            * a = array count/ID; b = employee's share; c = employer's share
                            */
                                ### SSS
                                    #### Contribution
                                    $sss_cont_a = '';
                                    $sss_cont_b = 0;
                                    $sss_cont_c = 0;
                                    $sss_arr = ($d->sss!=""||$d->sss!=null) ? SSS::Get_SSS_Deduction($rate) : null;
                                    if ($sss_arr != null) {
                                        $sss_cont_a = $sss_arr->code;
                                        $sss_cont_b += $sss_arr->empshare_ec;
                                        $sss_cont_c += $sss_arr->empshare_sc;
                                    }

                                ### PHILHEALTH
                                    #### Contributions
                                    $philhealth_cont_a = '';
                                    $philhealth_cont_b = 0;
                                    $philhealth_cont_c = 0;
                                    $philhealth_arr = ($d->philhealth!=""||$d->philhealth!=null) ? Philhealth::Get_PhilHealth_Deduction($rate) : null;
                                    if ($philhealth_arr != null) {
                                        $philhealth_cont_a = $philhealth_arr->code;
                                        $philhealth_cont_b += $philhealth_arr->emp_ee;
                                        $philhealth_cont_c += $philhealth_arr->emp_er;
                                    }

                                ### PAG-IBIG
                                    $pagibig_cont_a = '';
                                    $pagibig_cont_b = 0;
                                    $pagibig_cont_c = 0;
                                    $pagibig_arr = ($d->pagibig!=""||$d->pagibig!=null) ? Pagibig::Get_PagIbig_Deduction($rate) : null;
                                    if ($pagibig_arr != null) {
                                        $pagibig_cont_a = $pagibig_arr->code;
                                        $pagibig_cont_b += $pagibig_arr->emp_ee;
                                        $pagibig_cont_c += $pagibig_arr->emp_er;
                                    }
                                if ($withDeductions == false) {
                                    $sss_cont_a = '';
                                    $sss_cont_b = 0;
                                    $sss_cont_c = 0;
                                    $philhealth_cont_a = '';
                                    $philhealth_cont_b = 0;
                                    $philhealth_cont_c = 0;
                                    $pagibig_cont_a = '';
                                    $pagibig_cont_b = 0;
                                    $pagibig_cont_c = 0;
                                }
                            $personal_deductions = $sss_cont_b + $philhealth_cont_b + $pagibig_cont_b;

                            ## Withholding Tax
                            $wtax = 0;
                            if ($withDeductions) {
                                $wtax = Payroll::WithHoldingTax($rate, $tax_bracket);
                            }

                            ## Other Deductions
                            /**
                            * Other deduction array format [id, reference code, amount]
                            */
                            $other_deduction = [];
                            $other_deductions_amt = 0;
                            $other_deduction_arr = OtherDeductions::Get_Records($d->empid, $pp->from, $pp->to);
                            if (count($other_deduction_arr) > 0) {
                                for ($j=0; $j < count($other_deduction_arr); $j++) {
                                    $oda = $other_deduction_arr[$j];
                                    $oda_amt = (float)$oda->amount;
                                    $other_deductions_amt += $oda_amt;
                                    array_push($other_deduction, [$oda->dedcode, $oda->deduction_code, $oda_amt]);
                                }
                            }

                            ## Loans
                            /**
                            * Loans array pattern [loan code, loan type, loan sub-typ, amount]
                            */
                            $loans = [];
                            $loans_amt = 0;
                            $loans_arr = Loan::Find_Loan2($d->empid);
                            $updateln_loan = [];
                            if (count($loans_arr) > 0) {
                                for ($j=0; $j < count($loans_arr); $j++) {
                                    $la = $loans_arr[$j];
                                    $loans_amt += (float)$la->loan_deduction;
                                    array_push($updateln_loan, [
                                        'loan_hdr_code' => $la->loan_code,
                                        'transdate' => date('Y-m-d'),
                                        'amt_paid' => (float)$la->loan_deduction,
                                        'month' => $r->month,
                                        'period' => str_replace("D", "", $r->payroll_period),
                                        'year' => $r->year,
                                        'payment_desc' => "Deducted from payroll",
                                        'emp_pay_code' => $emp_pay_code,
                                    ]);
                                    array_push($loans, [$la->loan_code, $la->loan_type, $la->loan_sub_type, (float)$la->loan_deduction]);
                                }
                            }

                        $deductions = $personal_deductions + $wtax + $other_deductions_amt + $loans_amt;

                        # Net
                        $net_pay = ($basic_pay + $gross_pay) - $deductions;

                        # To Database
                        /**
                        * Index names on these arrays must reflects to their respective database tables
                        */
                        $info = [
                            'empid' => $d->empid,
                            'emp_pay_code' => $emp_pay_code,
                            'payroll_version' => 3,
                            'date_from' => $pp->from,
                            'date_to' => $pp->to,
                            'date_generated' => $cur_date,
                            'time_generated' => $cur_time,
                            'isgenerated_by' => $isgenerated_by,
                            'ip_location' => $ip_location,
                        ];
                        $todbs = [
                            # Basic Information
                            'empid' => $info['empid'],
                            'date_from' => $info['date_from'],
                            'date_to' => $info['date_to'],
                            'emp_pay_code' => $info['emp_pay_code'],
                            'rate_type' => $rate_type,
                            'rate' => $rate,
                            'dtr_sum_id' => $d->dtr_sum_id,

                            # Payroll Details
                            'total_workdays' => $workdays,
                            'total_workdays_amt' => $regular_pay,
                            'days_worked' => $days_worked,
                            'days_worked_amt' => $days_worked_amt,
                            'abcences' => $days_absent,
                            'abcences_amt' => $days_absent_amt,
                            'late' => $late,
                            'late_amt' => $late_amt,
                            'leave_cnt' => $leave_count,
                            'leave_amt' => $leave_amt,
                            'undertime' => $undertime,
                            'undertime_amt' => $undertime_amt,
                            'basic_pay' => $basic_pay,

                            # Gross Pay
                            'regular_ot' => json_encode($regular_ot),
                            'regular_ot_amt' => $regular_ot_amt,
                            'dayoff_ot' => json_encode($dayoff_ot),
                            'dayoff_ot_amt' => $dayoff_ot_amt,
                            'legal_holiday_ot' => json_encode($legal_holiday_ot),
                            'legal_holiday_ot_amt' => $legal_holiday_ot_amt,
                            'special_holiday_ot' => json_encode($special_holiday_ot),
                            'special_holiday_ot_amt' => $special_holiday_ot_amt,
                            'legal_holiday_pay' => json_encode($legal_holiday_pay),
                            'legal_holiday_pay_amt' => $legal_holiday_pay_amt,
                            'special_holiday_pay' => json_encode($special_holiday_pay),
                            'special_holiday_pay_amt' => $special_holiday_pay_amt,
                            'other_earnings' => json_encode($other_earnings),
                            'other_earnings_amt' => $other_earnings_amt,
                            'gross_pay' => $gross_pay,

                            # Deductions
                            'sss_cont_a' => $sss_cont_a,
                            'sss_cont_b' => $sss_cont_b,
                            'sss_cont_c' => $sss_cont_c,
                            'philhealth_cont_a' => $philhealth_cont_a,
                            'philhealth_cont_b' => $philhealth_cont_b,
                            'philhealth_cont_c' => $philhealth_cont_c,
                            'pagibig_cont_a' => $pagibig_cont_a,
                            'pagibig_cont_b' => $pagibig_cont_b,
                            'pagibig_cont_c' => $pagibig_cont_c,
                            'w_tax' => $wtax,
                            'other_deduction' => json_encode($other_deduction),
                            'other_deductions_amt' => $other_deductions_amt,
                            'loans' => json_encode($loans),
                            'loans_amt' => $loans_amt,
                            'others' => json_encode([]),
                            'others_amt' => 0,
                            'total_deductions' => $deductions,

                            # Net Pay
                            'net_pay' => $net_pay,
                        ];
                        $updateLines = [
                            'loans' => $updateln_loan,
                        ];

                        $data = ['info' => $info, 'todbs' => $todbs, 'updateLines' => $updateLines];
                        $response = $this->UpdatePayroll2($data, $info['payroll_version']);
                        if ($response!="ok") {
                            array_push($errors, 'B00005-'.$d->empid.":".$response);
                        }
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00003-'.$d->empid, $e->getMessage());
                        array_push($errors, 'B00003-'.$d->empid.":".$e->getMessage());
                    }
                }
            } else {
                array_push($errors, "No generated DTR available.");
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00001', $e->getMessage());
            array_push($errors, 'B00001'.":".$e->getMessage());
        }
        $results = json_decode($this->find_dtr($r))->payroll_history;
        return [$results, $errors, $asd];
    }

    public function UpdatePayroll2(Array $data, $version)
    {
        try {
            switch ($version) {
                case 1:
                    # code...
                    break;

                case 2:
                    # code...
                    break;

                case 3:
                    $error_count = 0;
                    # Save log
                    if (DB::table('hr_emp_payroll_log')->insert($data['info'])) {
                        if (DB::table('hr_emp_payroll3')->insert($data['todbs'])) {
                            if (Core::updatem99('emp_pay_code',Core::get_nextincrementlimitchar($data['info']['emp_pay_code'], 8))) {
                                try {
                                    DB::table('hr_dtr_sum_employees')->where('dtr_sum_id', $data['todbs']['dtr_sum_id'])->update(['isgenerated' => true]);
                                } catch (\Exception $e) {
                                    # Delete log and payroll if failed to update dtr summary
                                    DB::table('hr_emp_payroll_log')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                    DB::table('hr_emp_payroll3')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                    return "Unable to update dtr summary. Failed to generate.";
                                }
                                # Update Lines
                                # Loan
                                if (count($data['updateLines']['loans']) > 0) {
                                    try {
                                        for ($i=0; $i < count($data['updateLines']['loans']); $i++) {
                                            $loanLN = $data['updateLines']['loans'][$i];
                                            try {
                                                DB::table('hr_loanln')->insert($loanLN);
                                            } catch (\Exception $e) {
                                                return $e->getMessage();
                                                $error_count++;
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $error_count++;
                                    }
                                }
                                if ($error_count == 0) {
                                    return "ok";
                                } else {
                                    # Delete log and payroll if any of the line failed to be saved
                                    DB::table('hr_emp_payroll_log')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                    DB::table('hr_emp_payroll3')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                    $no_error = DB::table('hr_loanln')->where('emp_pay_code', $data['info']['emp_pay_code'])->get();
                                    if (count($no_error) > 0) {
                                        DB::table('hr_loanln')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                    }
                                    return "Some lines where unable to save. Failed to generate.";
                                }
                            } else {
                                # Delete log and payroll if emp_pay_code failed to generate new code
                                DB::table('hr_emp_payroll_log')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                DB::table('hr_emp_payroll3')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                                return "Unable to update payroll code. Failed to generate."; 
                            }
                        } else {
                            # Delete log if error in saving payroll
                            DB::table('hr_emp_payroll_log')->where('emp_pay_code', $data['info']['emp_pay_code'])->delete();
                            return "Unable to generate payroll. Failed to generate.";
                        }
                    } else {
                        return "Unable to save payroll log. Failed to generate.";
                    }
                    break;
                
                default:
                    return "invalid-version";
                    break;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function GetOtherEarnings($empid, $date_from, $date_to)
    {
    	try {
    		return DB::table('hr_earning_entry')->where('emp_no', $empid)->where('date_from', $date_from
        )->where('date_to', $date_to)->where('cancel', null)->get();
    	} catch (\Exception $e) {
    		return [];
    	}
    }

    public function Get_SSS_Loans($empid)
    {
        try {
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function Get_GSIS_Deduction($amt, $pp)
    {
    	$data = [
            'pd_gsis_a' => 0,
            'pd_gsis_b' => 0,
            'pd_gsis_c' => 0,
            'pd_gsis_d' => 0,
            'pd_gsis_e' => 0,
            'pd_gsis_f' => 0,
            'pd_gsis_g' => 0,
            'pd_gsis_h' => 0,
            'pd_gsis_i' => 0,
            'pd_gsis_j' => 0,
        ];

        try {
            if (Payroll::IfWithContributions($pp)) {
                $data['pd_gsis_a'] = $amt * 0.09;
                $data['pd_gsis_b'] = 0;
                $data['pd_gsis_c'] = 0;
                $data['pd_gsis_d'] = 0;
                $data['pd_gsis_e'] = 0;
                $data['pd_gsis_f'] = 0;
                $data['pd_gsis_g'] = 0;
                $data['pd_gsis_h'] = 0;
                $data['pd_gsis_i'] = 0;
                $data['pd_gsis_j'] = 0;
            }
            return $data;
        } catch (\Exception $e) {
            return $data;
        }
        return $data;

    }

    public function GetHazardPay($office_id)
    {
        try {
            return DB::table('hr_hazardpay')->where('cc_id', $office_id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function GetAlw_Clothing($empid)
    {
        try {
            return Core::sql("SELECT empid, STRING_AGG(CONCAT(service_from,'|', service_to), ',') service_dates, STRING_AGG((CASE WHEN (service_from IS NULL OR service_to IS NULL) THEN 0 ELSE DATE_PART('month', service_to::date) - DATE_PART('month', service_from::date) END)::character varying, ',') AS service_totals, SUM(CASE WHEN (service_from IS NULL OR service_to IS NULL) THEN 0 ELSE DATE_PART('month', service_to::date) - DATE_PART('month', service_from::date) END) service_overall FROM hris.hr_service_record WHERE empid = '$empid' GROUP BY empid");
        } catch (\Exception $e) {
            return []; 
        }
    }
    public function Monetize($empid, $basic_pay = 0)
    {
        try {
            $amt = 0;
            $a = [];
            $elc = DB::table('hr_emp_leavecount')->where('empid', '=', $empid)->get();
            $employee = Employee::GetEmployee($empid);
            if ($elc!=null) {
                foreach ($elc as $key) {
                    array_push($a, (float)$key->peak - (float)$key->count);
                }
            }
            $leave_credits = Core::GetTotal($a)[0];
            $monetize_perc = (float)Core::getm99('monetize_%');
            $amt = $basic_pay * $leave_credits * $monetize_perc;
            return $amt;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
