@extends('pages.frontend.main_view')

@section('to-body')
	<div class="m-4">
		<div class="card">
			<div class="card-header">
				<div class="form-inline">
					<i class="fa fa-building"></i> Other Earnings - RATA <br>
					<div class="form-group mr-2">
						<button class="btn btn-primary ml-3" onclick="window.location = '{{url()->previous()}}'">Back</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="container"><!-- header -->
					<div class="print_header">GENERAL PAYROLL</div>
					<div class="print_subheader">CITY OF GUIHULNGAN</div>
					<div class="print_subheader2">LGU</div>
					<div class="print_subheader3">RATA FOR THE MONTH OF <span id="print_month"></span></div>
					<div class="print_subheader4">We acknowledge the receipt of the sum shown opposite our names as full compensation for the services rendered for the period stated.</span></div>
				</div>
				<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					@php
						$count = 0;
						$total_monthly_salary = 0;
						$total_montly_ra = 0;
						$total_montly_ta = 0;
						$total_monthly_deduc1 = 0;
						$total_monthly_deduc2 = 0;
						$total_monthly_total_deduc = 0;
						$total_monthly_net_amount = 0;
						$total_monthly_amount_paid = 0;
					@endphp
					<thead>
						<tr>
							<th rowspan="2">ITEM NO.</th>
							<th rowspan="2">NAME</th>
							<th rowspan="2">No.</th>
							<th rowspan="2">POSITION</th>
							<th rowspan="2">MONTHLY <br> SALARY</th>
							<th rowspan="2">MONTHLY RA</th>
							<th rowspan="2">MONTHLY TA</th>
							<th rowspan="2">ABSENCE <br> W/O PAY</th>
							<th colspan="3">DEDUCTION</th>
							<th rowspan="2">No.</th>
							<th rowspan="2">NET <br> AMOUNT <br> RECEIVED</th>
							<th rowspan="2">AMOUNT PAID</th>
							<th rowspan="2" style="min-width: 200px !important">Signature of Payee</th>
						</tr>
						<tr>
							<th rowspan="1"></th>
							<th rowspan="1"></th>
							<th rowspan="1">Total <br> Deduction</th>
						</tr>
						{{-- <tr>
							@for($a=1; $a<=15; $a++)
								<th rowspan="1">{{$a}}</th>
							@endfor
						</tr> --}}
					</thead>
					<tbody>
						@isset($data[0] )
							@foreach($data[0] as $k => $v)
								@php
									if(is_numeric($v->pay_rate)) $total_monthly_salary += $v->pay_rate;
									if(is_numeric($v->monthly_ra)) $total_montly_ra += $v->monthly_ra;
									if(is_numeric($v->monthly_ta)) $total_montly_ta += $v->monthly_ta;
									if(is_numeric($v->deduc_1)) $total_monthly_deduc1 += $v->deduc_1;
									if(is_numeric($v->deduc_2)) $total_monthly_deduc2 += $v->deduc_2;
									if(is_numeric($v->total_deduc)) $total_monthly_total_deduc += $v->total_deduc;
									if(is_numeric($v->net_amount_received)) $total_monthly_net_amount += $v->net_amount_received;
									if(is_numeric($v->amount_paid)) $total_monthly_amount_paid += $v->amount_paid;
								@endphp
								<tr>
									<td>{{$v->empid}}</td>
									<td>{{$v->name}}</td>
									<td>{{++$count}}</td>
									<td>{{$v->position_readable}}</td>
									<td style="text-align: right;">
										@switch($v->rate_type)
											@case('M')
											{{number_format($v->pay_rate, 2)}}
											@break
											@case('D')
											<i>(Employee has daily rate)</i>
										@endswitch
									</td>
									<td style="text-align: right;">{{number_format($v->monthly_ra, 2)}}</td>
									<td style="text-align: right;">{{number_format($v->monthly_ta, 2)}}</td>
									<td>{{$v->absent_wo_pay}}</td>
									<td style="text-align: right;">{{number_format($v->deduc_1, 2)}}</td>
									<td style="text-align: right;">{{number_format($v->deduc_2, 2)}}</td>
									<td style="text-align: right;">{{number_format($v->total_deduc, 2)}}</td>
									<td>{{$count}}</td>
									<td style="text-align: right;">{{number_format($v->net_amount_received, 2)}}</td>
									<td style="text-align: right;">{{number_format($v->amount_paid, 2)}}</td>
									<td></td>
								</tr>
							@endforeach
							<tr>
								@for($a=1; $a<=15; $a++)
									<th rowspan="1"></th>
								@endfor
							</tr>
							<tr>
								<td colspan="4"><b><center>TOTAL</center></b></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_salary, 2)}}</b></td>
								<td style="text-align: right;"><b>{{number_format($total_montly_ra, 2)}}</b></td>
								<td style="text-align: right;"><b><center>{{number_format($total_montly_ta, 2)}}</b></td>
								<td></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_deduc1, 2)}}</b></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_deduc2, 2)}}</b></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_total_deduc, 2)}}</b></td>
								<td></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_net_amount, 2)}}</b></td>
								<td style="text-align: right;"><b>{{number_format($total_monthly_amount_paid, 2)}}</b></td>
								<td></td>
							</tr>
						@endisset
						<tr>
							@for($a=1; $a<=15; $a++)
								<th rowspan="1"></th>
							@endfor
						</tr>
						<tr>
							<td colspan="3">
								<center><b>CERTIFIED Services have been duly rendered as stated.</b></center>
							</td>
							<td colspan="2">
								<center><b>CERTIFICATION</b> <br>
								This is to CERTIFY that the above-lsited employees have enough leave credits in case he/she will be absent from the above specified period.</center>
							</td>
							<td colspan="5">
								<center><b>CERTIFIED Funds available in the amount {!!Core::currSign().trim("")!!}</b></center>
							</td>
							<td colspan="4">
								<center><b>APPROVED FOR PAYMENT</b></center>
							</td>
							<td colspan="1">
								<center>Each employee whose name appears above has been paid the amount indicated oppsite his/her name.</center>
							</td>
						</tr>
						<tr>
							{{-- <td colspan="3">
								<center>
									<div class="row">
										<div class="col-sm-12">HON. CARLO JORGE JOAN L. REYES</div>
										<div class="col-sm-12">NAME & SIGNATURE OF SUPERVISOR</div>
									</div>
								</center>
							</td>
							<td colspan="2">
								<center><b>CERTIFICATION</b> <br>
								This is to CERTIFY that the above-lsited employees have enough leave credits in case he/she will be absent from the above specified period.</center>
							</td>
							<td colspan="5">
								<center><b>CERTIFIED Funds available in the amount {!!Core::currSign().trim("")!!}</b></center>
							</td>
							<td colspan="4">
								<center><b>APPROVED FOR PAYMENT</b></center>
							</td>
							<td colspan="1">
								<center>Each employee whose name appears above has been paid the amount indicated oppsite his/her name.</center>
							</td> --}}
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<style>
		.print_header {
			text-align: center;
			text-decoration: underline;
			font-weight: bold;
			font-size: 2vw;
		}
		.print_subheader {
			text-align: center;
			text-decoration: underline;
			font-size: 1.5vw;
		}
		.print_subheader2 {
			text-align: center;
			font-weight: bold;
			font-size: 1.8vw;
		}
		.print_subheader3 {
			text-align: center;
			text-decoration: underline;
			font-weight: bold;
			font-size: 1.5vw;
		}
		.print_subheader4 {
			margin-top: 30px;
			text-align: center;
			font-weight: bold;
			font-size: 1vw;
		}

		th {
			vertical-align: middle !important;
			text-align: center;
		}
	</style>
@endsection