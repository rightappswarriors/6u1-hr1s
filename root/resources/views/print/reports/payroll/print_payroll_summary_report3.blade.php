@extends('layouts.print_layout')

@section('head')
	<style type="text/css">
		.bordered {
			border-collapse: collapse;
			width: 100%;
		}
		.bordered, .bordered tr > th{
			border: 1px solid black;
		}
		.no-border > tr > th {
			border: 0;
		}

		.edge-border-only tr > td {
			border-right: 1px solid black;
		}
		.center {
			text-align: center;
		}
		.text-right {
			text-align: right;
		}
		.indented{
			margin-right: 10px;
		}
		.indented-long {
			margin-right: 15px;
		}
		.dot-border-right{
			border-right: 1px dashed black;
		}
		.dot-border-bottom{
			border-bottom: 1px dashed black;
		}

		.c-table {
			width: 100%;
			border-collapse: collapse;
		}

		.c-table, th, td {
			padding-right: 10px;
			/*border: 1px solid black;*/
		}
	</style>
	<style type="text/css">
		@media print {
			@page {size: portrait;}
		}
	</style>
@endsection

@php
	# Print Details
	$print_date = date('d/m/Y H:i A');
	$department = ($record != null) ? $record->department : "-Office-Not-Found-";
	$name = ($record != null) ? ucwords(strtolower($record->empname)) : "";
	$payroll_period = ($record != null) ? $record->payroll_period : "";
	$bm_no = ($record != null) ? ($record->biometric != null) ? $record->biometric : "-BMID-NOT-FOUND-" : "";

	# Payroll Details
    $total_workdays_amt = ($record != null) ? number_format($record->total_workdays_amt, 2) : "";
    $days_worked_amt = ($record != null) ? number_format($record->days_worked_amt, 2) : "";
    $abcences_amt = ($record != null) ? number_format($record->abcences_amt, 2) : 0;
    $late_amt = ($record != null) ? number_format($record->late_amt, 2) : 0;
    $leave_amt = ($record != null) ? $record->leave_amt : 0;
	$undertime_amt = ($record != null) ? number_format($record->undertime_amt, 2) : 0;
    $basic_pay = ($record != null) ? $record->basic_pay : "";

	# Gross Pay
		# Overtime
	    $regular_ot_amt = ($record != null) ? $record->regular_ot_amt : 0;
	    $dayoff_ot_amt = ($record != null) ? $record->dayoff_ot_amt : 0;
	    $total_ot_amt = ($record != null) ? number_format($regular_ot_amt + $dayoff_ot_amt, 2) : 0;
	    # Holiday Overtime
	    $legal_holiday_ot_amt = ($record != null) ? number_format($record->legal_holiday_ot_amt, 2) : 0;
	    $special_holiday_ot_amt = ($record != null) ? number_format($record->special_holiday_ot_amt, 2) : 0;
	    $holiday_amt = ($record != null) ? number_format($legal_holiday_ot_amt + $special_holiday_ot_amt, 2) : "";
    $ls_ot_amt = ($record != null) ? number_format($legal_holiday_ot_amt + $special_holiday_ot_amt, 2) : "";
    $legal_holiday_pay_amt = ($record != null) ? number_format($record->legal_holiday_pay_amt, 2) : "";
    $special_holiday_pay_amt = ($record != null) ? number_format($record->special_holiday_pay_amt, 2) : "";
    $other_earnings_amt = ($record != null) ? number_format($record->other_earnings_amt, 2) : "";
    $pera = ($record != null) ? number_format(Payroll::Pera(), 2) : "";
    $gross_pay = ($record != null) ? number_format($record->gross_pay + $basic_pay + $leave_amt, 2) : "";

	# Deduction
    $wtax = ($record != null) ? number_format($record->w_tax, 2) : "";
		# Employee Contribution
	    $sss_cont_b = ($record != null) ? $record->sss_cont_b : 0;
	    $philhealth_cont_b = ($record != null) ? $record->philhealth_cont_b : 0;
	    $pagibig_cont_b = ($record != null) ? $record->pagibig_cont_b : 0;
	    $empcont_amt = ($record != null) ? number_format($sss_cont_b + $philhealth_cont_b + $pagibig_cont_b, 2) : "";
	    # Standard Deduction
	    $other_deductions_amt = ($record != null) ? $record->other_deductions_amt : 0;
	    $loans_amt = ($record != null) ? $record->loans_amt : 0;
	    $stndr_dedctn_amt = ($record != null) ? number_format($other_deductions_amt + $loans_amt, 2) : "";
    $others_amt = ($record != null) ? number_format($record->others_amt, 2) : 0;
    $total_deductions = ($record != null) ? number_format($record->total_deductions, 2) : "";

    $netpay = ($record != null) ? number_format($record->net_pay, 2) : "";
@endphp

@section('body') 
	@if($record == null)
	No record found.
	@else
	<div class="row">
		<div class="col-5 dot-border-right" style="padding-right: 0"> {{-- reciept --}}
			<div>
				<div class="dot-border-bottom">
					<div style="margin: 10px;">
						<center>{{$bm_no}}</center>
						<span class="indented">Print Date</span> {{$print_date}} <br>
						<h5>Payslip</h5>
						<span class="indented"><i>Pay period</i></span> {{$payroll_period}} <br>
						<span class="indented">Name</span> {{$name}}
					</div>
				</div>
				{{-- <label style="">Basic Pay</label> --}}
				<div style="padding-right: 4%;">
					<table class="c-table">
						<tr>
							<td>Basic Pay</td>
							<td class="text-right">{{$total_workdays_amt}}</td>
							<td>Withholding Tax</td>
							<td class="text-right">{{$wtax}}</td>
						</tr>
						<tr>
							<td>Ttl OT Amt</td>
							<td class="text-right">{{$total_ot_amt}}</td>
							<td>Ttl Emp. Contr</td>
							<td class="text-right">{{$empcont_amt}}</td>
						</tr>
						<tr>
							<td>Ttl Leave Amt</td>
							<td class="text-right">{{$leave_amt}}</td>
							<td>Std. Deductions</td>
							<td class="text-right">{{$stndr_dedctn_amt}}</td>
						</tr>
						<tr>
							<td>Ttl Hol Amt</td>
							<td class="text-right">{{$holiday_amt}}</td>
							<td>Additional Deduct</td>
							<td class="text-right">{{$others_amt}}</td>
						</tr>
						<tr>
							<td>Adjustment</td>
							<td class="text-right">0.00</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Total Earnigns</td>
							<td class="text-right">{{$gross_pay}}</td>
							<td>Total Deductions</td>
							<td class="text-right">{{$total_deductions}}</td>
						</tr>
					</table>
					<div style="margin-top: 5%; margin-right: 5%; margin-left: 5%;">
						<center>
							<div class="row">
								<div class="col"><h5>NET PAY</h5></div><div class="col"><h5>{{$netpay}}</h5></div>
							</div>
						</center>
					</div>
					<div style="margin-top: 2%; margin-right: 5%; margin-left: 5%;">
						<center>
							This is acknowledge receipt of my pay for the period {{$payroll_period}}. Furthermore, I acknowledge that in the absence of my written complain within three(3) working days from date of receipt, amount credited is certified to be final.
						</center>
					</div>
					
					<div style="padding: 10%; text-align: center;">
						<div>{{$name}}</div>
						<div style="border-top: 2px solid;">Signature over Printed Name</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-7"> {{-- payslip --}}
			<div style="margin-top: 5px;">
				<h5>Payslip</h5>
				<span class="indented"><i>Pay period</i></span> <strong>{{$payroll_period}}</strong> <br>
				<table class="c-table">
					<tr>
						<td><span class="indented">Biometric No.</span> {{$bm_no}}</td>
						<td><span class="indented">Name</span> <strong>{{$name}}</strong></td>
					</tr>
					<tr>
						<td><span class="indented">Dep.</span> {{strtoupper($department)}}</td>
						<td><span class="indented">Print Date</span> {{$print_date}}</td>
					</tr>
				</table>
				<table class="c-table">
					<thead style="border-top: 2px solid; border-bottom: 2px solid;">
						<tr class="center">
							<th colspan="2">Earnings</th>
							<th colspan="2">Deduction</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><span class="indented-long">Basic Pay</span></td>
							<td class="text-right">{{$total_workdays_amt}}</td>
							<td>Withholding Tax</td>
							<td class="text-right">{{$wtax}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Late/Undertime</span>{{-- {{$p->late}} --}}</td>
							<td class="text-right">{{($late_amt!=0 || $late_amt!=0.00) ? "-".$late_amt : $late_amt}}</td>
							<td>Employee Contribution</td>
							<td class="text-right">{{-- {{$ttlemp_contr_amnt}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Abscences</span>{{-- {{$p->abcences}} --}}</td>
							<td class="text-right">{{($abcences_amt!=0 || $abcences_amt!=0.00) ? "-".$abcences_amt : $abcences_amt}}</td>
							<td class="text-right">SSS</td>
							<td class="text-right">{{number_format($sss_cont_b, 2)}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Net Basic Pay</span></td>
							<td class="text-right">{{number_format($basic_pay, 2)}}</td>
							<td class="text-right">Philhealth</td>
							<td class="text-right">{{number_format($philhealth_cont_b, 2)}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Overtime Amt</span></td>
							<td class="text-right">{{number_format($regular_ot_amt, 2)}}</td>
							<td class="text-right">HDMF/Pag-ibig</td>
							<td class="text-right">{{number_format($pagibig_cont_b, 2)}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Day Off/Regular OT</span></td>
							<td class="text-right">{{number_format($dayoff_ot_amt, 2)}}</td>
							<td>Standard Deductions</td>
							<td class="text-right">{{-- 0.00 --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Legal/Special OT</span>{{-- {{$p->special_holiday_ot}} --}}</td>
							<td class="text-right">{{$ls_ot_amt}}</td>
							<td class="text-right">Advances/Loan</td>
							<td class="text-right">{{number_format($loans_amt, 2)}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Holiday Pay</span></td>
							<td class="text-right">{{-- {{$p->legal_holiday_pay_amt + $p->special_holiday_pay_amt}} --}}</td>
							<td class="text-right">Other Deduction</td>
							<td class="text-right">{{number_format($other_deductions_amt, 2)}}</td>
						</tr>
						<tr>
							<td><span class="indented-long" style="padding-left: 5%">Legal Holiday</span></td>
							<td class="text-right">{{$legal_holiday_pay_amt}}</td>
							{{-- <td>Add. Deductions</td>
							<td class="text-right">0.00</td> --}}
							<td class="text-right">Other</td>
							<td class="text-right">{{$others_amt}}</td>
						</tr>
						<tr>
							<td><span class="indented-long" style="padding-left: 5%">Special Holiday</span></td>
							<td class="text-right">{{$special_holiday_pay_amt}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Leave Amt</span></td>
							<td class="text-right">{{number_format($leave_amt, 2)}}</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Other Earnings</span></td>
							<td class="text-right">{{$other_earnings_amt}}</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">13 Month</span></td>
							<td class="text-right">0.00</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Leave Balance</span></td>
							<td class="text-right">0.00</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Pera</span></td>
							<td class="text-right">{{$pera}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Adjustment</span></td>
							<td class="text-right">0.00</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Total Earnings</span></td>
							<td class="text-right" style="border-bottom: 1px solid;">{{$gross_pay}}</td>
							<td>Total Deductions</td>
							<td class="text-right" style="border-bottom: 1px solid;">{{$total_deductions}}</td>
						</tr>
						<tr>
							<td colspan="2"><strong>NET PAY</strong></td>
							<td colspan="2" class="text-right" style="border-bottom: double;">{{$netpay}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
@endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection