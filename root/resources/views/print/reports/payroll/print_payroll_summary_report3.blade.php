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
	/*$name = ucwords(strtolower(Employee::Name($p->empid)));
	$pay_code = $p->emp_pay_code;
	$bm_no = Employee::empIDtobmID($p->empid);
	$print_date = date('d/m/Y H:i A');
	$pp_date_from = $log->date_from;
	$pp_date_to = $log->date_to;

	// Payroll Details
	$ttlLeave_amnt = $p->leave_amt;

	// Gross Pay
	$ttlOT_amnt = round($p->regular_ot_amt + $p->dayoff_ot_amt + $p->legal_holiday_ot_amt + $p->special_holiday_ot_amt, 2);
	$ttlholiday_amnt = round($p->legal_holiday_pay_amt + $p->special_holiday_pay_amt, 2);

	// Deductions
	$ttlemp_contr_amnt = round($p->sss_cont_b + $p->philhealth_cont_b +  $p->pagibig_cont_b, 2);
	$stdeductions_amnt = 0;
	$addDeduction_amnt = round($p->other_deductions_amt + $p->others_amt + $p->loans_amt, 2);

	// Total
	$ttl_earnings = round($p->days_worked_amt + $ttlOT_amnt + $ttlholiday_amnt + $ttlLeave_amnt + $p->other_earnings_amt, 2);
	$ttl_deductions = $p->total_deductions;

	$netpay = $p->net_pay;*/
@endphp

@section('body') 
	<div class="row">
		<div class="col-5 dot-border-right" style="padding-right: 0"> {{-- reciept --}}
			<div>
				<div class="dot-border-bottom">
					<div style="margin: 10px;">
						<center>{{-- {{$bm_no}} --}}</center>
						<span class="indented">Print Date</span> {{-- {{$print_date}} --}} <br>
						<h5>Payslip</h5>
						<span class="indented"><i>Pay period</i></span> {{-- {{$pp_date_from}} --}} to {{-- {{$pp_date_to}} --}} <br>
						<span class="indented">Name</span> {{-- {{$name}} --}}
					</div>
				</div>
				{{-- <label style="">Basic Pay</label> --}}
				<div style="padding-right: 4%;">
					<table class="c-table">
						<tr>
							<td>Basic Pay</td>
							<td class="text-right">{{-- {{$p->days_worked_amt}} --}}</td>
							<td>Withholding Tax</td>
							<td class="text-right">{{-- {{$p->w_tax}} --}}</td>
						</tr>
						<tr>
							<td>Ttl OT Amt</td>
							<td class="text-right">{{-- {{$ttlOT_amnt}} --}}</td>
							<td>Ttl Emp. Contr</td>
							<td class="text-right">{{-- {{$ttlemp_contr_amnt}} --}}</td>
						</tr>
						<tr>
							<td>Ttl Leave Amt</td>
							<td class="text-right">{{-- {{$ttlLeave_amnt}} --}}</td>
							<td>Std. Deductions</td>
							<td class="text-right">{{-- {{$stdeductions_amnt}} --}}</td>
						</tr>
						<tr>
							<td>Ttl Hol Amt</td>
							<td class="text-right">{{-- {{$ttlholiday_amnt}} --}}</td>
							<td>Additional Deduct</td>
							<td class="text-right">{{-- {{$addDeduction_amnt}} --}}</td>
						</tr>
						<tr>
							<td>Adjustment</td>
							<td class="text-right">0.00</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Total Earnigns</td>
							<td class="text-right">{{-- {{$ttl_earnings}} --}}</td>
							<td>Total Deductions</td>
							<td class="text-right">{{-- {{$ttl_deductions}} --}}</td>
						</tr>
					</table>
					<div style="margin-top: 5%; margin-right: 5%; margin-left: 5%;">
						<center>
							<div class="row">
								<div class="col"><h5>NET PAY</h5></div><div class="col"><h5>{{-- {{$netpay}} --}}</h5></div>
							</div>
						</center>
					</div>
					<div style="margin-top: 2%; margin-right: 5%; margin-left: 5%;">
						<center>
							This is acknowledge receipt of my pay for the period {{-- {{$pp_date_from}} --}} to {{-- {{$pp_date_to}} --}}. Furthermore, I acknowledge that in the absence of my written complain within three(3) working days from date of receipt, amount credited is certified to be final.
						</center>
					</div>
					
					<div style="padding: 10%; text-align: center;">
						<div>{{-- {{$name}} --}}</div>
						<div style="border-top: 2px solid;">Signature over Printed Name</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-7"> {{-- payslip --}}
			<div style="margin-top: 5px;">
				<h5>Payslip</h5>
				<span class="indented"><i>Pay period</i></span> <strong>{{-- {{date('F d, Y', strtotime($pp_date_from))}} to {{date('F d, Y', strtotime($pp_date_to))}} --}}</strong> <br>
				<table class="c-table">
					<tr>
						<td><span class="indented">Biometric No.</span> {{-- {{$bm_no}} --}}</td>
						<td><span class="indented">Name</span> <strong>{{-- {{$name}} --}}</strong></td>
					</tr>
					<tr>
						<td><span class="indented">Dep.</span> {{-- {{strtoupper('inventory')}} --}}</td>
						<td><span class="indented">Print Date</span> {{-- {{$print_date}} --}}</td>
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
							<td><span class="indented-long">Basic Pay</span>{{-- {{$p->days_worked}} --}} (days)</td>
							<td class="text-right">{{-- {{$p->days_worked_amt}} --}}</td>
							<td>Withholding Tax</td>
							<td class="text-right">{{-- {{$p->w_tax}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Late/Undertime</span>{{-- {{$p->late}} --}}</td>
							<td class="text-right">{{-- {{$p->late_amt}} --}}</td>
							<td>Employee Contribution</td>
							<td class="text-right">{{-- {{$ttlemp_contr_amnt}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Abscences</span>{{-- {{$p->abcences}} --}}</td>
							<td class="text-right">-{{-- {{$p->abcences_amt}} --}}</td>
							<td class="text-right">SSS</td>
							<td class="text-right">{{-- {{$p->sss_cont_b}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Net Basic Pay</span></td>
							<td class="text-right">{{-- {{$p->total_workdays_amt - $p->abcences_amt}} --}}</td>
							<td class="text-right">Philhealth</td>
							<td class="text-right">{{-- {{$p->philhealth_cont_b}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Overtime Amt</span></td>
							<td class="text-right">{{-- {{$ttlOT_amnt}} --}}</td>
							<td class="text-right">HDMF/Pag-ibig</td>
							<td class="text-right">{{-- {{$p->pagibig_cont_b}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Day Off/Regular OT</span>{{-- {{$p->regular_ot}} --}}</td>
							<td class="text-right">{{-- {{$p->regular_ot_amt}} --}}</td>
							<td>Standard Deductions</td>
							<td class="text-right">0.00</td>
						</tr>
						<tr>
							<td><span class="indented-long">Legal/Special OT</span>{{-- {{$p->special_holiday_ot}} --}}</td>
							<td class="text-right">{{-- {{$p->special_holiday_ot_amt}} --}}</td>
							<td class="text-right">Advances/Loan</td>
							<td class="text-right">{{-- {{$p->loans_amt}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Holiday Pay</span></td>
							<td class="text-right">{{-- {{$p->legal_holiday_pay_amt + $p->special_holiday_pay_amt}} --}}</td>
							<td class="text-right">Other Deduction</td>
							<td class="text-right">{{-- {{$p->other_deductions_amt}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long" style="padding-left: 5%">Legal Holiday</span></td>
							<td class="text-right">{{-- {{$p->legal_holiday_pay_amt}} --}}</td>
							<td>Add. Deductions</td>
							<td class="text-right">0.00</td>
						</tr>
						<tr>
							<td><span class="indented-long" style="padding-left: 5%">Special Holiday</span></td>
							<td class="text-right">{{-- {{$p->special_holiday_pay_amt}} --}}</td>
							<td class="text-right">Other</td>
							<td class="text-right">{{-- {{$p->others_amt}} --}}</td>
						</tr>
						<tr>
							<td><span class="indented-long">Leave Amt</span></td>
							<td class="text-right">{{-- {{$p->leave_amt}} --}}</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Other Earnings</span></td>
							<td class="text-right">{{-- {{$p->other_earnings_amt}} --}}</td>
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
							<td><span class="indented-long">Adjustment</span></td>
							<td class="text-right">0.00</td>
							<td></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><span class="indented-long">Total Earnings</span></td>
							<td class="text-right" style="border-bottom: 1px solid;">{{-- {{$ttl_earnings}} --}}</td>
							<td>Total Deductions</td>
							<td class="text-right" style="border-bottom: 1px solid;">{{-- {{$ttl_deductions}} --}}</td>
						</tr>
						<tr>
							<td colspan="2"><strong>NET PAY</strong></td>
							<td colspan="2" class="text-right" style="border-bottom: double;">{{-- {{$netpay}} --}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection