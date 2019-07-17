<?php

namespace App\Http\Controllers\Payroll;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Core;
use ErrorCode;
use PayrollPeriod;
use Employee;
use Holiday;
use Timelog;
use Leave;
use Loan;
use Payroll;
use DTR;

class GeneratePayrollController extends Controller
{
    // Values that are called within this controller
    public function __construct()
    {
    	$this->ghistory = DB::table('hr_emp_payroll2')->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'DESC')->get();
        $this->dtrsum = DB::table('hr_dtr_sum_hdr')->join('hr_dtr_sum_employees', 'hr_dtr_sum_hdr.code', '=', 'hr_dtr_sum_employees.dtr_sum_id')->where('isgenerated', '=', 0)->orderBy('date_generated', 'DESC')->orderBy('time_generated', 'ASC')->get();
    }

    // Redirects to blade
    public function view()
    {
    	$data = [$this->dtrsum,$this->ghistory];
        // dd($data);
    	return view('pages.payroll.generate_payroll', compact('data'));
    }

    // Find dtr function
    public function find_dtr(Request $r)
    {
        try {
            $return_val = (object)[];
            $pp = Payroll::PayrollPeriod2($r->month, $r->payroll_period);
            $return_val->search = date('Y-m-d', strtotime($pp->from))." to ".date('Y-m-d', strtotime($pp->to));
            return json_encode( $return_val);
        } catch (\Exception $e) {
            return "error". $e->getMessage();
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
        try {
            $results = [];
            // $errors = [];
            $test_arr = [];
            $return_val = (object)[];

            // Get records that is not generated yet
            $pp = Payroll::PayrollPeriod2($r->month,$r->payroll_period); if ($pp =="error") { return "no pp"; }
            $dtr_summaries = DB::table('hr_dtr_sum_employees')->where('isgenerated', 0)->get();
            if (count($dtr_summaries)>0) {
                for ($i=0; $i < count($dtr_summaries); $i++) {
                    try {
                        $dtr_summary = $dtr_summaries[$i];
                        $hdr = DTR::Get_HDR($dtr_summary->dtr_sum_id);
                        $empid = $dtr_summary->empid;
                        
                        /*Employee Info*/
                        $emp_info = Employee::GetEmployee($empid); //object
                        $rate = $emp_info->pay_rate; //string
                        $rate_type = $emp_info->rate_type;

                        /* Shift Computation */
                        $shift_hours = Timelog::ShiftHours($empid); //float
                        $covered_days = Core::CoveredDates($pp->from, $pp->to); //array
                        $total_days = count($covered_days); //array
                        $dtr_hdr = DB::table('hr_dtr_sum_hdr')->where('empid', $dtr_summary->empid)->where('ppid', $r->payroll_period)->where('date_from', date('Y-m-d', strtotime($pp->from)))->where('date_to', date('Y-m-d', strtotime($pp->to)))->orderBy('date_generated', 'ASC')->get(); //array

                        /* -Rate breakdown by time- */
                        $daily_rate = Payroll::GetDailyRate($rate, $rate_type);
                        $hourly_rate = Payroll::ConvertRate($daily_rate, $shift_hours); // $hourly_rate = $daily_rate / $shift_hours;
                        $minute_rate = Payroll::ConvertRate($hourly_rate, 60); // $minute_rate = $hourly_rate / 60;

                        /* -Leave- */
                        $leave_info = Leave::GetLeaveInfo($empid, $rate, $pp->from, $pp->to);
                        $leave_total = 0;
                        $leave_amt = 0;
                        if (count($leave_info) > 0) {
                            for ($j=0; $j < count($leave_info); $j++) { 
                                $li = $leave_info[$j];
                                if ($li->lvpay == 1) {
                                    if (count($li->lvdates) > 0) {
                                        for ($k=0; $k < count($li->lvdates); $k++) {
                                            $leave_total += 1;
                                            $leave_amt += $daily_rate;
                                        }
                                    }
                                }
                            }
                        }

                        /* -Paywork Details- */
                        $work_days = $dtr_summary->days_worked;
                        $regular_pay = 0; 
                        if ($rate_type == "D") {
                            $regular_pay = $daily_rate * ($total_days - $leave_total);
                        } else {
                            $regular_pay = $rate / 2;
                        }
                        $amt_overtime = Core::ToMinutes($dtr_summary->total_overtime) * $minute_rate;
                        $amt_absent = $dtr_summary->absences * $daily_rate;
                        $amt_late = Core::ToMinutes($dtr_summary->late) * $minute_rate;
                        $amt_undertime = Core::ToMinutes($dtr_summary->undertime) * $minute_rate;

                        $holiday_info = $this->GetHolidayAmt($empid, $covered_days, $daily_rate);

                        /* -Gross Pay- */
                        $pera = 2000;
                        $basic_pay = ($regular_pay + $amt_overtime) - ($amt_absent + $amt_late + $amt_undertime);

                        /* -Personal Deductions- */
                        $witholding_tax = $this->Get_WitholdingTax($regular_pay, $pp->id);
                        $philhealth = $this->Get_PhilHealth_Deduction($empid, $pp->id);
                        $gsis = $this->Get_GSIS_Deduction($regular_pay, $pp->id);
                        $pag_ibig = $this->Get_PagIbig_Deduction($regular_pay, $pp->id);
                        
                        $other_deductions = $this->Get_Other_Deductions($empid);
                        $loans = $this->Get_Loans_Amt($empid, $covered_days);

                        $total_pd = Core::GetTotal([
                            $witholding_tax,
                            $philhealth,
                            $pag_ibig['pd_pagibig_a'],
                            $pag_ibig['pd_pagibig_b'],
                            $pag_ibig['pd_pagibig_c'],
                            $gsis['pd_gsis_a'],
                            $gsis['pd_gsis_b'],
                            $gsis['pd_gsis_c'],
                            $gsis['pd_gsis_d'],
                            $gsis['pd_gsis_e'],
                            $gsis['pd_gsis_f'],
                            $gsis['pd_gsis_g'],
                            $gsis['pd_gsis_h'],
                            $gsis['pd_gsis_i'],
                            $gsis['pd_gsis_j'],
                        ]);
                        

                        /* -Government Shares- */
                        $life_ins = $regular_pay * 0.12;

                        /* -Net- */
                        $net_amt = ($basic_pay + $pera) - $total_pd[0];

                        /* Needs to be displayed */
                        /*
                        | Basic Salary
                        | Overtime
                        | Other Tx Inc
                        | Others
                        | Gross Taxable
                        | Less W/H Tax
                        | Goss After Tax
                        | Less:
                        |   GSIS
                        |   Philhealth 2%
                        |   Pag-ibig
                        |   Loan Payments
                        |   Other Deduc.
                        | Add:
                        |   No Tax Inc(Pmt)
                        | Net Pay
                        */

                        /* To Database */
                        $cp = 
                        [
                            [
                                'empid' => $empid,
                                'pp_id' => $pp->id,
                                'time_generated' => date('H:i:s'),
                                'date_generated' => date('Y-m-d'),
                                'date_from' => $pp->from,
                                'date_to' => $pp->to,
                                'rate' => $regular_pay,
                                'absences_wo_pay' => $dtr_summary->absences,
                                'computed_rate' => 0,
                                'pera' => $pera,
                                'hazard_duty_pay' => 0,
                                'alw_laundry' => 0,
                                'alw_sub_leave' => 0,
                                'alw_sub_travel' => 0,
                                'alw_sub_total' => 0,
                                'amt_earned' => $basic_pay,
                                'pd_w_tax' => $witholding_tax,
                                'pd_philhealth' => $philhealth,
                                'pd_pagibig_a' => $pag_ibig['pd_pagibig_a'],
                                'pd_pagibig_b' => $pag_ibig['pd_pagibig_b'],
                                'pd_pagibig_c' => $pag_ibig['pd_pagibig_c'],
                                'pd_jgm' => 0,
                                'pd_lbp' => 0,
                                'pd_cfi' => 0,
                                'pd_dccco' => 0,
                                'pd_gsis_a' => $gsis['pd_gsis_a'],
                                'pd_gsis_b' => $gsis['pd_gsis_b'],
                                'pd_gsis_c' => $gsis['pd_gsis_c'],
                                'pd_gsis_d' => $gsis['pd_gsis_d'],
                                'pd_gsis_e' => $gsis['pd_gsis_e'],
                                'pd_gsis_f' => $gsis['pd_gsis_f'],
                                'pd_gsis_g' => $gsis['pd_gsis_g'],
                                'pd_gsis_h' => $gsis['pd_gsis_h'],
                                'pd_gsis_i' => $gsis['pd_gsis_i'],
                                'pd_gsis_j' => $gsis['pd_gsis_j'],
                                'pd_pei_refund' => 0,
                                'pd_ca_refund' => 0,
                                'pd_total_deductions' => $total_pd[0],
                                'gs_philhealth' => $philhealth,
                                'gs_life_ins' => $life_ins,
                                'gs_pagibig_hdmf' => 100,
                                'gs_state_ins' => ($regular_pay < 10000) ? $regular_pay - ($regular_pay * 0.01) : 100,
                                'net_amt' => $net_amt,
                                'amt_paid' => $net_amt,
                                'a_overtime' => $amt_overtime,
                                'a_undertime' => $amt_undertime,
                                'a_late' => $amt_late,
                                'a_holiday' => $holiday_info->amt
                            ],
                            'dtr_sum_id' => $dtr_summary->dtr_sum_id
                        ];

                        if ($this->find_payroll($empid, $pp->id, $pp->from, $pp->to)) {
                            array_push($results, "attempt ".($i+1).":Already Generated");
                        } else {
                            array_push($results, "attempt ".($i+1).":".$this->UpdatePayroll($cp));
                        }
                    } catch (\Exception $e) {
                        array_push($results, "Error on attempt no.".$i." (".$e->getMessage().")");
                    }
                }
                $return_val->results = $results;
                $return_val->ghistory = $this->ghistory;
                $return_val->dtrsum = $this->dtrsum;
                return $return_val;
            } else {
                return "no record";
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GeneratePayrollController', '00002', $e->getMessage());
            return "error";
        }
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

    public function GetOtherEarnings($ppid, $empid)
    {
    	try {
    		$amt = 0.00;
    		$table = DB::table('hr_earning_entry')->where('payroll_period', $ppid)->where('emp_no', $empid)->get();
    		if ($table!=null) {
    			for ($i=0; $i < count($table); $i++) { 
    				$amt += $table[$i]->amount;
    			}
    		}
    		return $amt;
    	} catch (\Exception $e) {
    		return 0.00;
    	}
    }

    public function Get_GSIS_Deduction($regular_pay, $pp)
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
                $data['pd_gsis_a'] = $regular_pay * 0.09;
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

    public function Get_PhilHealth_Deduction($empid, $pp)
    {
    	try {
            $amt = 0.00;
            $et = DB::table('hr_employee')->select('rate_type', 'pay_rate')->where('empid', $empid)->first();
            if (Payroll::IfWithContributions($pp)) {
                if ($et!=null) {
                    $table = DB::table('hr_philhealth')->orderBy('bracket1', 'ASC')->get();
                    if (count($table)!=0) {
                        foreach ($table as $a) {
                            if ((double)$et->pay_rate > (double)$a->bracket1 && (double)$et->pay_rate <= (double)$a->bracket2) {
                                $amt = $a->emp_er;
                            }
                        }
                    }
                }
            }
            return $amt;
        } catch (\Exception $e) {
            return 0.00;
        }
    }

    public function Get_PagIbig_Deduction($regular_pay, $pp)
    {
    	try {
            $data = [
                'pd_pagibig_a' => 0,
                'pd_pagibig_b' => 0,
                'pd_pagibig_c' => 0,
            ];
            if (Payroll::IfWithContributions($pp)) {
                $data['pd_pagibig_a'] = $regular_pay * 0.02;
            }

            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }

    public function Get_WitholdingTax($regular_pay, $pp)
    {
    	try {
            $amt = 0;
            $wtax_table = [
                '0|250000|0|0', // 0%
                '250000|400000|0.2|0', // 20% of excess over P250,000
                '400000|800000|0.25|30000', // P30,000 + 25% of excess over P400,000
                '800000|2000000|0.3|130000', // P130,000 + 30% of excess over P800,000
                '2000000|8000000|0.32|490000', // P490,000 + 32% of excess over P2 Million
                '8000000|Z|0.35|2410000', // P2,410,000 + 35% of excess over P8 Million
            ];
            if (Payroll::IfWithContributions($pp)) {
                for ($i=0; $i < count($wtax_table); $i++) { 
                    $wtax = $wtax_table[$i];
                    list($over, $notover, $factor, $addon) = explode("|", $wtax);
                    if ($regular_pay > (double)$wtax && $regular_pay <= $notover) {
                        $amt = (($regular_pay - $over) * $factor) + $addon;
                    }
                }
            }
    		return $amt;
    	} catch (\Exception $e) {
    		return $amt;
    	}
    }

    public function Get_Other_Deductions($empid)
    {
    	try {
    		return 0.00;
    	} catch (\Exception $e) {
    		return 0.00;
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
