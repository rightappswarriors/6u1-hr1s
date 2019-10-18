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
            $pp = Payroll::PayrollPeriod2($r->month, $r->payroll_period, $r->year);
            $sql = "SELECT dtr.*, emp.* FROM (SELECT * FROM hris.hr_dtr_sum_hdr a LEFT JOIN (SELECT * FROM hris.hr_dtr_sum_employees WHERE isgenerated IS FALSE) b ON a.code = b.dtr_sum_id) dtr INNER JOIN (".Employee::$emp_sql.") emp ON dtr.empid = emp.empid";
                $con = " WHERE date_from >= '".date('Y-m-d', strtotime($pp->from))."' AND date_to <= '".date('Y-m-d', strtotime($pp->to))."' AND empstatus = '".$r->empstatus."' AND generationtype = '".$r->gen_type."' AND department = '".$r->ofc."'";
            $return_val->search = date('Y-m-d', strtotime($pp->from))." to ".date('Y-m-d', strtotime($pp->to));
            // $return_val->parameters = $r->all();
            $return_val->dtr_summaries = Core::sql($sql.$con);
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
            $pp = Payroll::PayrollPeriod2($r->month,$r->payroll_period); if ($pp =="error") { return "no pp"; }
            if (count($dtr_summaries) > 0) {
                for ($i=0; $i < count($dtr_summaries); $i++) {
                    $tmp = "";
                    try {
                        /*Payroll Info*/
                        $d = $dtr_summaries[$i];
                        $rate = $d->pay_rate;
                        $rate_type = $d->rate_type;
                        $workdays = (float)$d->workdays;
                        $emp_pay_code = Core::getm99('emp_pay_code');
                        $pay_period = Payroll::PayPeriods();
                        $tax_bracket = $d->tax_bracket;
                        
                        /* Shift Info */
                        $shift_hours = Timelog::ShiftHours(); # returns float
                        $covered_days = Core::CoveredDates($pp->from, $pp->to); # returns array
                        $total_days = count($covered_days); # returns array

                        /* -Regular Pay- */
                        $regular_pay = 0;
                        if ($rate_type == "D") {
                            $regular_pay = $rate * $total_days;
                        } else {
                            $regular_pay = $rate / $pay_period;
                        }
                        
                        /* -Rate breakdown by time- */
                        $daily_rate = $regular_pay / $total_days;
                        $hourly_rate = Payroll::ConvertRate($daily_rate, $shift_hours); # $hourly_rate = $daily_rate / $shift_hours;
                        $minute_rate = Payroll::ConvertRate($hourly_rate, 60); # $minute_rate = $hourly_rate / 60;

                        /* -Basic Pay- */
                            /* -Days Worked- */
                            $days_worked = (float)$d->days_worked;
                            $days_worked_amt = 0; $days_worked_amt = $days_worked * $daily_rate;

                            /* -Absent- */
                            $days_absent = $d->days_absent;
                            $days_absent_amt = 0; $days_absent_amt = $days_absent * $daily_rate;

                            /* -Late- */
                            $late = Core::ToMinutes($d->late);
                            $late_amt = 0; $late_amt = $late * $minute_rate;

                            /* -Leave- */
                            $leaves = json_decode($d->leaves_arr);
                            $leave_count = $d->leaves;
                            $leave_amt = 0;
                            if (count($leaves) > 0) {
                                for ($j=0; $j < count($leaves); $j++) { 
                                    $l = $leaves[$j];
                                    $leave_amt += (float)$l[2];
                                };
                            }
                        $basic_pay = $days_worked_amt + $days_absent_amt + $late_amt + $leave_amt;

                        /* -Gross Pay Computation- */
                            /* -Regular Overtime- */
                            $regular_ot = 0;
                            $regular_ot_amt = 0;
                            $dayoff_ot = 0;
                            $dayoff_ot_amt = 0;
                            $overtime_arr = json_decode($d->total_overtime_arr);
                            if (count($overtime_arr) > 0) {
                                for ($j=0; $j < count($overtime_arr); $j++) { 
                                    list($ota_date, $ota_timelogs, $ota_rhrs) = $overtime_arr[$j];
                                    $ota_rvalue = Core::ToHours($ota_rhrs) * $hourly_rate;
                                    if (Timelog::IfWorkdays($ota_date)) {
                                        $regular_ot++;
                                        $regular_ot_amt += $ota_rvalue;
                                    } else {
                                        $dayoff_ot++;
                                        $dayoff_ot_amt += $ota_rvalue;
                                    }
                                }
                            }

                            /* -Holiday- */
                                /* -Worked/Holiday OT- */
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

                                /**
                                * Holiday array = [date, timelog]
                                * exempted = complete timelog
                                * no-log = absent
                                */
                                /* -Not worked Holiday- */
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
                                                # Error retrieving leave type
                                                ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00004-HD-'.$d->empid, $e->getMessage());
                                                array_push($errors, 'B00004-HD-'.$d->empid."-".$j.": Unknown holiday type.");
                                                break;
                                        }
                                    }
                                }

                            /* -Other Earnings- */
                            $other_earnings = [];
                            $other_earnings_amt = 0;
                            $earnings_arr = $this->GetOtherEarnings($d->empid, $r->payroll_period, (int)$r->month, (int)$r->year);
                            if (count($earnings_arr) > 0) {
                                for ($j=0; $j < count($earnings_arr); $j++) { 
                                    $ea = $earnings_arr[$j]; $ea_amnt = $ea->amount; $ea_id = $ea->id;
                                    # Other earnings array format = [earnings id, amount]
                                    $other_earnings_amt += $ea_amnt;
                                }
                            }

                            /* -Pera- */
                            $pera = 2000;

                        $gross_pay = $regular_ot_amt + $dayoff_ot_amt + $legal_holiday_pay_amt + $special_holiday_pay_amt + $legal_holiday_ot_amt + $special_holiday_ot_amt + $other_earnings_amt + $pera;
                                    
                        /* -Deductions- */
                        # a = array count/ID; b = employee's share; c = employer's share
                            /* -Personal Deductions- */
                                /* -SSS- */
                                    /* -Contribution- */
                                    $sss_cont_a = '';
                                    $sss_cont_b = 0;
                                    $sss_cont_c = 0;
                                    $sss_arr = $this->Get_SSS_Deduction($rate);
                                    if ($sss_arr != null) {
                                        $sss_cont_a = $sss_arr->code;
                                        $sss_cont_b += $sss_arr->empshare_ec;
                                        $sss_cont_c += $sss_arr->empshare_sc;
                                    }

                                /* -PHILHEALTH- */
                                    /* -Contributions- */
                                    $philhealth_cont_a = '';
                                    $philhealth_cont_b = 0;
                                    $philhealth_cont_c = 0;
                                    $philhealth_arr = $this->Get_PhilHealth_Deduction($rate);
                                    if ($philhealth_arr != null) {
                                        $philhealth_cont_a = $philhealth_arr->code;
                                        $philhealth_cont_b += $philhealth_arr->emp_ee;
                                        $philhealth_cont_c += $philhealth_arr->emp_er;
                                    }

                                /* -PAG-IBIG- */
                                    $pagibig_cont_a = '';
                                    $pagibig_cont_b = 0;
                                    $pagibig_cont_c = 0;
                                    $pagibig_arr = $this->Get_PagIbig_Deduction($rate);
                                    if ($pagibig_arr != null) {
                                        $pagibig_cont_a = $pagibig_arr->code;
                                        $pagibig_cont_b += $pagibig_arr->emp_ee;
                                        $pagibig_cont_c += $pagibig_arr->emp_er;
                                    } 
                            $personal_deductions = $sss_cont_b + $philhealth_cont_b + $pagibig_cont_b;

                            /* -Withholding Tax- */
                            $wtax = Payroll::WithHoldingTax($rate, $tax_bracket);

                            /* -Other Deductions- */
                            $other_deduction = [];
                            $other_deductions_amt = 0;
                            $other_deduction_arr = OtherDeductions::Get_Records($d->empid, $pp->from, $pp->to);
                            if (count($other_deduction_arr) > 0) {
                                for ($j=0; $j < count($other_deduction_arr); $j++) {
                                    $oda = $other_deduction_arr[$j];
                                    array_push($other_deduction, $oda->dedcode);
                                    $other_deductions_amt += (float)$oda->amount;
                                }
                            }

                            /*Loans*/
                            $loans = [];
                            $loans_amt = 0;
                            $loans_arr = Loan::Find_Loan2($d->empid);
                            $updateln_loan = [];
                            if (count($loans_arr) > 0) {
                                for ($j=0; $j < count($loans_arr); $j++) {
                                    $la = $loans_arr[$j];
                                    array_push($loans, $la->loan_code);
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
                                }
                            }

                        $deductions = $personal_deductions + $wtax + $other_deductions_amt + $loans_amt;

                        /* -Net- */
                        $net_pay = ($basic_pay + $gross_pay) - $deductions;

                        /* To Database */
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
                            'late_amt' => $hourly_rate,
                            'leave_cnt' => $leave_count,
                            'leave_amt' => $leave_amt,
                            'basic_pay' => $basic_pay,

                            # Gross Pay
                            'regular_ot' => $regular_ot,
                            'regular_ot_amt' => $regular_ot_amt,
                            'dayoff_ot' => $dayoff_ot,
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

                        $data = ['info' => $info, 'todbs' => $todbs, 'updateLines' => $updateLines]; array_push($asd, $data);
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
        $log = DB::table('hr_emp_payroll_log')->where('cancel', null)->get();
        for ($i=0; $i < count($log); $i++) { 
            $log[$i]->name = Employee::Name($log[$i]->empid);
        }
        $results = $log;
        return [$results, $errors, $asd];
    }

    public function UpdatePayroll(Array $cp)
    {
        try {
            // try {
            //     $new_epc = Core::getm99One('emp_pay_code');
            //     if ($new_epc!=null) {
            //         $new_epc = $new_epc->emp_pay_code;
            //     } else {
            //         return "error";
            //     }
            // } catch (\Exception $e) {
            //     return "error";
            // }
            DB::table('hr_emp_payroll2')->insert($cp[0]);
            DB::table('hr_dtr_sum_employees')->where('empid', $cp[0]['empid'])->where('dtr_sum_id', $cp['dtr_sum_id'])->update(['isgenerated' => 1]);
            return "ok";
        } catch (\Exception $e) { return $e->getMessage(); }
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
                    if (DB::table('hr_emp_payroll_log')->insert($data['info'])) {
                        if (DB::table('hr_emp_payroll3')->insert($data['todbs'])) {
                            Core::updatem99('emp_pay_code',Core::get_nextincrementlimitchar($data['info']['emp_pay_code'], 8));
                        } else {
                            return "Unable to save log. Failed to generate.";
                            $error_count++;
                        }
                    } else {
                        return "Unable to save log header. Failed to generate.";
                        $error_count++;
                    }
                    # Update Lines
                    #Loan
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
                        return "Some lines where unable to save. Failed to generate.";
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

    public function GetDayOfOT($empid, $dateFrom, $dateTo)
    {
    	try {
    		$employee = Employee::GetEmployee($empid);
    		if ($employee!=null) {
    			if ($this->CheckIfSchedIsFixed($employee->fixed_sched)) {
    				$data = $this->GetFixDayOffInfo($empid, $dateFrom, $dateTo);

    			} else {
    				$data = $this->GetShiftDayOffInfo($empid);
    			}
    		} else {
                $data = [
                    'amt' => 0.00,
                    'total' => 0
                ];
            }
            return $data;
    	} catch (\Exception $e) {
    		return $e->getMessage();
    		return 0.00;
    	}
    }

    public function CheckIfSchedIsFixed($val)
    {
    	if (strtoupper($val) == "Y") {
    		return true;
    	}

    	return false;
    }

    public function GetFixDayOffInfo($empid, $dateFrom, $dateTo)
    {
	    $amt = 0.00;
        $total = 0;
        try {
	    	$dayoff = DB::table('hr_employee')->select('dayoff1', 'dayoff2')->where('empid', $empid)->first();
	    	$workdates = Timelog::getWorkdates($empid, $dateFrom, $dateTo, [['status', 'I']]);
	    	$emp = Employee::GetEmployee($empid);
	    	if ($dayoff!=null) {
	    		$dayoff1 = DB::table('hr_days')->select('dayname')->where('day', $dayoff->dayoff1)->first();
	    		$dayoff2 = DB::table('hr_days')->select('dayname')->where('day', $dayoff->dayoff2)->first();
	    		if ($dayoff1!=null) {
	    			// $dayoff1 = Core::WeekValRev($dayoff1);
	    			foreach ($workdates as $wd) {
	    				if (strtoupper(date('l', strtotime($wd->work_date))) == $dayoff1->dayname) {
	    					if ($emp->rate_type == "M") {
	    						$amt += ($emp->pay_rate * 12) / 314;
	    					} else if ($emp->rate_type == "D") {
	    						$amt += $emp->pay_rate;
	    					}
                            $total++;
	    				}
	    			}
	    		}

	    		if ($dayoff2!=null) {
	    			if ($dayoff2!=$dayoff1) {
	    				foreach ($workdates as $wd) {
		    				if (strtoupper(date('l', strtotime($wd->work_date))) == $dayoff2->dayname) {
		    					if ($emp->rate_type == "M") {
		    						$amt += ($emp->pay_rate * 12) / 314;
		    					} else if ($emp->rate_type == "D") {
		    						$amt += $emp->pay_rate;
		    					}
                                $total++;
		    				}
		    			}
	    			}
	    		}
	    	}
	    } catch (\Exception $e) { return $e->getMessage(); }

        $data = [
            'amt' => $amt,
            'total' => $total
        ];
        return $data;
    }

    public function GetShiftDayOffInfo()
    {
    	$amt = 0.00;
        $total = 0;
        $data = [
            'amt' => $amt,
            'total' => $total
        ];
    	return $data;
    }

    public function GetHolidayAmt($empid, array $covered_days, $daily_rate)
    {
    	$amt = 0.00;
        $total = 0;
        $total_time = [];
        $data = (object) [];
        try {
            for ($i=0; $i < count($covered_days); $i++) {
                $h_amt = 0;
                $cd = $covered_days[$i];
                if (Timelog::IfHoliday($cd)) {
                    if (Timelog::IfPresent($empid, $cd)) {
                        $h_info = Holiday::FindHoliday($cd); if($h_info==null) { $h_perc = null; } else { $h_perc =  $h_info->holiday_type; }
                        $h_perc =  Holiday::HolidayPercentage($h_perc);
                        $h_amt = $daily_rate + ($daily_rate * ($h_perc->work / 100));

                        $amt += $h_amt;
                        array_push($total_time, Timelog::RetrieveRenHours($empid, $cd));
                    }
                }
            }
            $data->amt = $amt;
            $data->total = $total;
            $data->total_time = $total_time;
        } catch (\Exception $e) {
            $data->amt = $amt;
            $data->total = $total;
            $data->total_time = $total_time;
        }
        return $data;

    }

    public function CheckDutyOnHol($empid, $date)
    {
	    try {
	    	$data = DB::table('hr_tito2')->where('empid', $empid)->where('work_date', $date)->first();
	    	if ($data == null) {
	    		return false;
	    	} else {
	    		return true;
	    	}
	    } catch (\Exception $e) {
	    	return $e->getMessage();
	    	return false;
	    }

    }

    public function GetSpecialHolidayPay($empid, $dateFrom, $dateTo)
    {
    	$amt = 0.00;
        $total = 0;
            $holidays = PayrollPeriod::GetHolidays($dateFrom, $dateTo, 'S');
        try {
	    	$employee = Employee::GetEmployee($empid);
		    if ($employee!=null) {
		    	if (count($holidays) > 0) {
		    		foreach ($holidays as $day) {
		    			if ($this->CheckDutyOnHol($empid, $day->date_holiday)) {
		    				if ($employee->rate_type == "M") {
		    					$rate = ($employee->pay_rate * 12) / 314;
		    					$amt += $rate;
		    				} elseif ($employee->rate_type == "D") {
		    					$rate = ($employee->pay_rate * 12) / 314;
		    					$amt += $rate;
		    				}
                            $total++;
		    			}
		    		}
		    	}
		    }
    	} catch (\Exception $e) {}

        $data = [
            'amt' => $amt,
            'total' => $total
        ];

        return $data;
    }

    public function GetOtherEarnings($empid, $ppid, $month, $year)
    {
    	try {
    		return DB::table('hr_earning_entry')->where('emp_no', $empid)->where('payroll_period', $ppid)->where('month', $month)->where('year', $year)->where('cancel', null)->get();
    	} catch (\Exception $e) {
    		return [];
    	}
    }

    public function Get_SSS_Deduction($amt)
    {
        try {
            $sql = "SELECT * FROM hris.hr_sss WHERE CANCEL IS NULL ";
            $con = "AND bracket1 <= ".$amt." AND bracket2 > ".$amt." LIMIT 1";
            $result = Core::sql($sql.$con);
            if (count($result) > 0) {
                return $result[0];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
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

    public function Get_PhilHealth_Deduction($amt)
    {
    	try {
            $sql = "SELECT * FROM hris.hr_philhealth WHERE CANCEL IS NULL ";
            $con = "AND bracket1 <= ".$amt." AND bracket2 > ".$amt." LIMIT 1";
            $result = Core::sql($sql.$con);
            if (count($result) > 0) {
                return $result[0];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function Get_PagIbig_Deduction($amt)
    {
    	try {
            $sql = "SELECT * FROM hris.hr_hdmf WHERE CANCEL IS NULL ";
            $con = "AND bracket1 <= ".$amt." AND bracket2 > ".$amt." LIMIT 1";
            $result = Core::sql($sql.$con);
            if (count($result) > 0) {
                return $result[0];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function Get_Loans_Amt($empid, $covered_days)
    {
    	$amt = 0.00;
        try {
            for ($i=0; $i < count($covered_days); $i++) {
                $cd = $covered_days[$i];
                $loan_record = Loan::Find_Loan($empid, $cd);
                if (count($loan_record) > 0) {
                    for ($j=0; $j < count($loan_record); $j++) { 
                        $lr = $loan_record[$i];
                        if ($cd == $lr->deduction_date) {
                            $amt += $lr->loan_deduction;
                        }
                    }
                }
            }
    		return $amt;
    	} catch (\Exception $e) {
    		return $amt;
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
