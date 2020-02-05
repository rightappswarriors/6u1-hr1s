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
    	return view('pages.payroll.generate_payroll', compact('data'));
    }

    public function previewPayroll(Request $r){
        $r['isForDisplay'] = true;
        $toAddres = [];
        $var = self::generate_payroll($r);
        if(isset($var['todbs'])){
            array_push($toAddres, (object)$var['todbs']);
            $var['info']['title'] = 'Payroll Preview';
            $data = [
                'inf' => $var['info'],
                'record' => $toAddres
            ];
            return view('print.reports.payroll.export_payroll_summary_report',compact('data'));
        }
        return abort(404);
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
            // $sql = "SELECT dtr.*, emp.* FROM (SELECT a.ppid, a.date_from, a.date_to, a.date_generated, a.time_generated, a.code, a.generatedby, a.generationtype, a.empid FROM hris.hr_dtr_sum_hdr a LEFT JOIN hris.hr_dtr_sum_employees b ON a.code = b.dtr_sum_id where b.isgenerated IS NOT TRUE) dtr INNER JOIN ($emp_sql) emp ON dtr.empid = emp.empid";
            // $sql = "SELECT dtr.*, emp.* FROM (SELECT * FROM hris.hr_dtr_sum_hdr a LEFT JOIN hris.hr_dtr_sum_employees b ON a.code = b.dtr_sum_id) dtr INNER JOIN ($emp_sql) emp ON dtr.empid = emp.empid";
            $sql = "SELECT distinct pay_rate, rate_type, tax_bracket, days_absent, late, undertime, leaves_arr, leaves, total_overtime_arr, holiday_arr, holiday_dates, department, sss, philhealth, pagibig, dtr_sum_id, date_generated, time_generated, empname, emp.empid FROM (SELECT * FROM hris.hr_dtr_sum_hdr a LEFT JOIN hris.hr_dtr_sum_employees b ON a.code = b.dtr_sum_id) dtr INNER JOIN ($emp_sql) emp ON dtr.empid = emp.empid";
                // $con = " WHERE date_from >= '".date('Y-m-d', strtotime($pp->from))."' AND date_to <= '".date('Y-m-d', strtotime($pp->to))."' AND empstatus = '".$r->empstatus."' AND generationtype = '".$r->gen_type."' AND department = '".$r->ofc."'";
            $con = " WHERE isgenerated IS NOT TRUE AND date_from >= '".date('Y-m-d', strtotime($pp->from))."' AND date_to <= '".date('Y-m-d', strtotime($pp->to))."' AND empstatus = '".$r->empstatus."' AND generationtype = '".$r->gen_type."' AND department = '".$r->ofc."'";
            $return_val->search = date('Y-m-d', strtotime($pp->from))." to ".date('Y-m-d', strtotime($pp->to));
            // $return_val->parameters = $r->all();
            $return_val->dtr_summaries = Core::sql($sql.$con);
            // $return_val->generateReview = self::generate_payroll($r);
            // $return_val->payroll_history = Core::sql("SELECT pr.*, CONCAT(emp.lastname, ', ', emp.firstname) AS empname FROM (SELECT a.*, b.date_generated, b.time_generated FROM hris.hr_emp_payroll3 a INNER JOIN hris.hr_dtr_sum_hdr b ON a.dtr_sum_id = b.code) pr INNER JOIN ($emp_sql) emp ON pr.empid = emp.empid");
            $return_val->payroll_history = Core::sql("SELECT distinct date_generated, time_generated, empname, pr.empid, CONCAT(emp.lastname, ', ', emp.firstname) AS empname FROM (SELECT a.*, b.date_generated, b.time_generated FROM hris.hr_emp_payroll3 a INNER JOIN hris.hr_dtr_sum_hdr b ON a.dtr_sum_id = b.code) pr INNER JOIN ($emp_sql) emp ON pr.empid = emp.empid");
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
        * @param $r->isForDisplay (boolean)
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
                        $emp_pay_code = Core::getm99('emp_pay_code');
                        $pay_period = Payroll::PayPeriods();
                        $gen_type = $r->gen_type;

                        $d = $dtr_summaries[$i];
                        $rate = (float)$d->pay_rate;
                        $rate_type = $d->rate_type;
                        $tax_bracket = $d->tax_bracket;

                        if ($r->payroll_period == "15D") {
                            $withDeductions = true;
                        }
                        if ($gen_type == "OVERTIME") {
                            $withDeductions = false;
                        }

                        # Guihulngan formula
                        /**
                        * Seperate Overtime From Payroll
                        * Daily Rate = Monthly Rate / 22 Days
                        * Hourly Rate = Daily Rate / 8 Hours
                        * Overtime Hourly Rate (Regular Days) = Hourly Rate + 25% of Hourly Rate
                        * Holiday Overtime Hourly Rate = Hourly Rate + 50% of Hourly
                        * Net Overtime = (Overtime + Holiday Overtime) - Withholding Tax
                        */
                        
                        # Shift Info
                        /**
                        * returns float
                        * returns array
                        */
                        $workdays = /*(float)$d->workdays;*/ 22; # Note: Monthly
                        $weekends = 0; # Not Used
                        $shift_hours = Timelog::ShiftHours();
                        $covered_days = Core::CoveredDates($pp->from, $pp->to); # Not Used
                        $total_days = /*$workdays + $weekends;*/ $workdays / 2;

                        # Regular Pay
                        $regular_pay = 0;
                        $daily_rate = 0;
                        /*if ($rate_type == "D") {
                            $regular_pay = $rate * $workdays;
                        } else {
                            $regular_pay = $rate / $pay_period;
                        }*/
                        if ($rate_type == "D") {
                            $regular_pay = $rate * $workdays;
                            $daily_rate = $rate;
                        } else {
                            $regular_pay =($rate / $workdays) / $pay_period;
                            $daily_rate = $regular_pay;
                        }
                        
                        # Rate breakdown by time
                        /**
                        * Hourly Rate = Daily Rate / Shift Hours
                        * Minute Rate = Hourly Rate / 60
                        */
                        $hourly_rate = 0; $hourly_rate = $daily_rate / $shift_hours;
                        $minute_rate = 0; $minute_rate = $hourly_rate / 60;

                        # Basic Pay
                            ## Absent
                            $days_absent = 0; $days_absent = $d->days_absent;
                            $days_absent_amt = 0; $days_absent_amt = $days_absent * $daily_rate;

                            ## Days Worked
                            $days_worked = 0; $days_worked = $total_days - $days_absent;
                            $days_worked_amt = 0; $days_worked_amt = $regular_pay - $days_absent_amt;

                            ## Late
                            $late = 0; $late = Core::ToMinutes($d->late);
                            $late_amt = 0; $late_amt = $late * $minute_rate;

                            ## Undertime
                            $undertime = Core::ToMinutes($d->undertime);
                            $undertime_amt = 0; $undertime_amt = $undertime * $minute_rate;

                            ## Leave
                            $leaves = json_decode($d->leaves_arr);
                            $leave_count = $d->leaves;
                            $leave_amt = 0;
                            $leave_amt_limit = 1500; /* Note: Monthly Leave Limit */
                            if (count($leaves) > 0) {
                                for ($j=0; $j < count($leaves); $j++) { 
                                    $leave_amt += $daily_rate;
                                };
                            }

                            if ($leave_amt > $leave_amt_limit) {
                                $leave_amt = $leave_amt_limit;
                            }

                            if ($gen_type == "OVERTIME") {
                                $days_absent = '';
                                $days_absent_amt = 0;
                                $days_worked = '';
                                $days_worked_amt = 0;
                                $late = 0;
                                $late_amt = 0;
                                $undertime = '';
                                $undertime_amt = 0;
                                $leaves = '';
                                $leave_count = '';
                                $leave_amt = 0;
                            }

                        $basic_pay = (round($days_worked_amt, 2) + round($leave_amt, 2)) - round($undertime_amt, 2);

                        # Gross Pay Computation
                            ## Regular Overtime
                            /**
                            * Array definition respectively: date, timelog, hours, amount
                            */
                            $regular_ot = [];
                            $regular_ot_amt = 0;
                            $dayoff_ot = [];
                            $dayoff_ot_amt = 0;
                            $overtime_arr = json_decode($d->total_overtime_arr);
                            if (count($overtime_arr) > 0) {
                                for ($j=0; $j < count($overtime_arr); $j++) {
                                    list($ota_date, $ota_timelogs, $ota_rhrs) = $overtime_arr[$j];
                                    $ota_rvalue = 0; $ota_rvalue = Core::ToHourOnly($ota_rhrs) * ($hourly_rate + ($hourly_rate * 0.25));
                                    if (Timelog::IfWorkdays($ota_date)) {
                                        $regular_ot_amt += $ota_rvalue;
                                        array_push($regular_ot, [$ota_date, $ota_timelogs, $ota_rhrs, $ota_rvalue]);
                                    } else {
                                        $dayoff_ot_amt += $ota_rvalue;
                                        array_push($dayoff_ot, [$ota_date, $ota_timelogs, $ota_rhrs, $ota_rvalue]);
                                    }
                                }
                            }

                            ## Holiday
                            /**
                            * Holiday array = [date, timelog]
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
                                        $ha_percent = /*Holiday::HolidayPercentage($ha_type)->work / 100*/$hourly_rate + ($hourly_rate * 0.5);
                                        $ha_amt = 0; $ha_amt = Core::ToHourOnly($ha_rtime) * $ha_percent;
                                        switch ($ha_type) {
                                            case 'RH':
                                                $legal_holiday_ot_amt += $ha_amt;
                                                array_push($legal_holiday_ot, [$ha_date, $ha_timelog, $ha_rtime, number_format($ha_amt, 2)]);
                                                break;
                                            case 'SH':
                                                $special_holiday_ot_amt += $ha_amt;
                                                array_push($special_holiday_ot, [$ha_date, $ha_timelog, $ha_rtime, number_format($ha_amt, 2)]);
                                                break;
                                            
                                            default:
                                                # Error retrieving leave type
                                                ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00004-HOT-'.$d->empid, 'Type not found');
                                                array_push($errors, 'B00004-HOT-'.$d->empid."-".$j.": Holiday type not found.");
                                                break;
                                        }
                                    }
                                }

                                ### Not worked Holiday/Holiday Pay
                                $legal_holiday_pay = [];
                                $legal_holiday_pay_amt = 0;
                                $special_holiday_pay = [];
                                $special_holiday_pay_amt = 0;
                                $hdays_arr = json_decode($d->holiday_dates);
                                if (count($hdays_arr) > 0) {
                                    for ($j=0; $j < count($hdays_arr); $j++) { 
                                        list($hda_date, $hda_type) = $hdays_arr[$j];
                                        $hda_percent = Holiday::HolidayPercentage($hda_type)->nowork / 100 ;
                                        $hda_amt = 0;
                                        switch ($hda_type) {
                                            case 'RH':
                                                $hda_amt = $daily_rate;
                                                $legal_holiday_pay_amt += $hda_amt;
                                                array_push($legal_holiday_pay, [$hda_date, "no-logs-needed", number_format($hda_amt, 2)]);
                                                break;
                                            case 'SH':
                                                $special_holiday_pay_amt += $hda_amt;
                                                array_push($special_holiday_pay, [$hda_date, "no-logs-needed", 0]);
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
                            * P1 - PERA = P2,000.00
                            * HP1 - Hazard Pay
                            * A1 - Laundry = P150.00
                            * A2 - Clothing Allowance = P6,000.00; If Employee is still under 6 months in service
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
                                    $alw_laundry_amt = 150; # Note: Static Value
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

                            if ($gen_type == "OVERTIME") {
                                $legal_holiday_pay_amt = 0;
                                $special_holiday_pay_amt = 0;
                                $other_earnings_amt = 0;
                            } else {
                                $regular_ot_amt = 0;
                                $dayoff_ot_amt = 0;
                                $legal_holiday_ot_amt = 0;
                                $special_holiday_ot_amt = 0;
                            }

                        $gross_pay = round($regular_ot_amt, 2) + round($dayoff_ot_amt, 2) + round($legal_holiday_ot_amt, 2) + round($special_holiday_ot_amt, 2) + round($legal_holiday_pay_amt, 2) + round($special_holiday_pay_amt, 2) + round($other_earnings_amt, 2);
                                    
                        # Deductions
                            ## Personal Deductions
                            /**
                            * _a = array count/ID; _b = employee's share; _c = employer's share
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
                            $personal_deductions = round($sss_cont_b, 2) + round($philhealth_cont_b, 2) + round($pagibig_cont_b, 2);

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
                            if ($gen_type != "OVERTIME") {
                                if (count($other_deduction_arr) > 0) {
                                    for ($j=0; $j < count($other_deduction_arr); $j++) {
                                        $oda = $other_deduction_arr[$j];
                                        $oda_amt = (float)$oda->amount;
                                        $other_deductions_amt += $oda_amt;
                                        array_push($other_deduction, [$oda->dedcode, $oda->deduction_code, $oda_amt]);
                                    }
                                }
                            }

                            ## Loans
                            /**
                            * Loans array format [loan code, loan type, loan sub-typ, amount]
                            */
                            $loans = [];
                            $loans_amt = 0;
                            $loans_arr = Loan::Find_Loan2($d->empid);
                            $updatehdr_loan = [];
                            $updateln_loan = [];
                            if ($gen_type != "OVERTIME") {
                                if (count($loans_arr) > 0) {
                                    for ($j=0; $j < count($loans_arr); $j++) {
                                        $la = $loans_arr[$j];
                                        $loan_balance = 0;
                                        $loan_paid = 0;
                                        $loan_tbp = 0;
                                        $loans_prev = [];
                                        if ($la->status == "unpaid") {
                                            $loans_prev = Loan::PreviousLoanRecords($la->loan_code);
                                            if (count($loans_prev) > 0) {
                                                for ($k=0; $k < count($loans_prev); $k++) { 
                                                    $loan_paid += (float)$loans_prev[$k]->amt_paid;
                                                }
                                            }
                                            $loan_balance = (float)$la->loan_amount - $loan_paid;
                                            if ($loan_balance <= 0) {
                                                array_push($updatehdr_loan, [
                                                    'loan_amount' => $la->loan_amount,
                                                    'status' => "paid",
                                                    'remarks' => "balance:0",
                                                ]);
                                            } else {
                                                $loan_tbp = ((float)$la->loan_amount / 2);
                                                $loans_amt += $loan_tbp;
                                                array_push($updatehdr_loan, [
                                                    'loan_amount' => $loan_tbp,
                                                    'status' => "ongoing",
                                                    'remarks' => "remaining balance:".$loan_balance,
                                                ]);
                                                array_push($updateln_loan, [
                                                    'loan_hdr_code' => $la->loan_code,
                                                    'transdate' => date('Y-m-d'),
                                                    'amt_paid' => $loan_tbp,
                                                    'month' => $r->month,
                                                    'period' => str_replace("D", "", $r->payroll_period),
                                                    'year' => $r->year,
                                                    'payment_desc' => "Deducted from payroll",
                                                    'emp_pay_code' => $emp_pay_code,
                                                ]);
                                                array_push($loans, [$la->loan_code, $la->loan_type, $la->loan_sub_type, $loan_tbp]);
                                            }
                                        }
                                    }
                                }
                            }

                        $deductions = round($personal_deductions, 2) + round($wtax, 2) + round($other_deductions_amt, 2) + round($loans_amt, 2);

                        # Net
                        $net_pay = (round($basic_pay, 2) + round($gross_pay, 2)) - round($deductions, 2);

                        # To Database
                        /**
                        * Index names on these arrays must reflects to their respective columns on the database tables
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
                            'total_workdays' => $total_days,
                            'total_workdays_amt' => round($regular_pay, 2),
                            'days_worked' => $days_worked,
                            'days_worked_amt' => round($days_worked_amt, 2),
                            'abcences' => $days_absent,
                            'abcences_amt' => round($days_absent_amt, 2),
                            'late' => $late,
                            'late_amt' => round($late_amt, 2),
                            'leave_cnt' => $leave_count,
                            'leave_amt' => round($leave_amt, 2),
                            'undertime' => $undertime,
                            'undertime_amt' => round($undertime_amt, 2),
                            'basic_pay' => round($basic_pay, 2),

                            # Gross Pay
                            'regular_ot' => json_encode($regular_ot),
                            'regular_ot_amt' => round($regular_ot_amt, 2),
                            'dayoff_ot' => json_encode($dayoff_ot),
                            'dayoff_ot_amt' => round($dayoff_ot_amt, 2),
                            'legal_holiday_ot' => json_encode($legal_holiday_ot),
                            'legal_holiday_ot_amt' => round($legal_holiday_ot_amt, 2),
                            'special_holiday_ot' => json_encode($special_holiday_ot),
                            'special_holiday_ot_amt' => round($special_holiday_ot_amt, 2),
                            'legal_holiday_pay' => json_encode($legal_holiday_pay),
                            'legal_holiday_pay_amt' => round($legal_holiday_pay_amt, 2),
                            'special_holiday_pay' => json_encode($special_holiday_pay),
                            'special_holiday_pay_amt' => round($special_holiday_pay_amt, 2),
                            'other_earnings' => json_encode($other_earnings),
                            'other_earnings_amt' => round($other_earnings_amt, 2),
                            'gross_pay' => round($gross_pay, 2),

                            # Deductions
                            'sss_cont_a' => $sss_cont_a,
                            'sss_cont_b' => round($sss_cont_b, 2),
                            'sss_cont_c' => round($sss_cont_c, 2),
                            'philhealth_cont_a' => $philhealth_cont_a,
                            'philhealth_cont_b' => round($philhealth_cont_b, 2),
                            'philhealth_cont_c' => round($philhealth_cont_c, 2),
                            'pagibig_cont_a' => $pagibig_cont_a,
                            'pagibig_cont_b' => round($pagibig_cont_b, 2),
                            'pagibig_cont_c' => round($pagibig_cont_c, 2),
                            'w_tax' => round($wtax, 2),
                            'other_deduction' => json_encode($other_deduction),
                            'other_deductions_amt' => round($other_deductions_amt, 2),
                            'loans' => json_encode($loans),
                            'loans_amt' => round($loans_amt, 2),
                            'others' => json_encode([]),
                            'others_amt' => 0,
                            'total_deductions' => round($deductions, 2),

                            # Net Pay
                            'net_pay' => $net_pay,
                        ];
                        $updateLines = [
                            'loans' => $updateln_loan,
                        ];

                        $data = ['info' => $info, 'todbs' => $todbs, 'updateLines' => $updateLines];
                        // for saving 
                        if(!isset($r->isForDisplay)){
                            $response = $this->UpdatePayroll2($data, $info['payroll_version']);
                            if ($response!="ok") {
                                array_push($errors, 'B00005-'.$d->empid.":".$response);
                            }
                        } else {
                            return $data;
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
