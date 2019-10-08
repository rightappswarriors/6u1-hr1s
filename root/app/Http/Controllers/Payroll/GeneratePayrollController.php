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
        */

        $errors = [];
        $asd = [];
        $dtr_summaries = json_decode($this->find_dtr($r))->dtr_summaries;

        try {
            // Get records that is not generated yet
            $pp = Payroll::PayrollPeriod2($r->month,$r->payroll_period); if ($pp =="error") { return "no pp"; }
            if (count($dtr_summaries) > 0) {
                for ($i=0; $i < count($dtr_summaries); $i++) { 
                    try {
                        $d = $dtr_summaries[$i];

                        /*Employee Info*/
                        $rate = $d->pay_rate;
                        $rate_type = $d->rate_type;
                        
                        /* Shift Computation */
                        $shift_hours = Timelog::ShiftHours(); // returns float
                        $covered_days = Core::CoveredDates($pp->from, $pp->to); // returns array
                        $total_days = count($covered_days); // returns array
                        
                        /* -Rate breakdown by time- */
                        $daily_rate = Payroll::GetDailyRate($rate, $rate_type);
                        $hourly_rate = Payroll::ConvertRate($daily_rate, $shift_hours); // $hourly_rate = $daily_rate / $shift_hours;
                        $minute_rate = Payroll::ConvertRate($hourly_rate, 60); // $minute_rate = $hourly_rate / 60;

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

                        /* -Paywork Details- */
                        $days_worked = $d->days_worked_arr;
                        $regular_pay = 0; 
                        if ($rate_type == "D") {
                            $regular_pay = $daily_rate * ($total_days - $leave_total);
                        } else {
                            $regular_pay = $rate / 2;
                        }

                        /* -Gross Pay Computation- */
                        $pera = 2000;
                        $basic_pay = ($regular_pay + $amt_overtime) - ($amt_absent + $amt_late + $amt_undertime);

                        /* -Personal Deductions- */
                        /* -Government Shares- */
                        /* -Net- */
                        /* To Database */
                    } catch (\Exception $e) {
                        ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00002-'.$d->empid, $e->getMessage());
                        array_push($errors, $i.":".$e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            ErrorCode::Generate('controller', 'GeneratePayrollController', 'B00001', $e->getMessage());
            array_push($errors, $e->getMessage());
        }

        return [$asd, $errors];
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
