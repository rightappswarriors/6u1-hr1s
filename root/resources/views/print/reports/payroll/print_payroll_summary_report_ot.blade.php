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

		table.c-table,table.c-table th,table.c-table td {
			padding-right: 10px;
			border: 1px solid black;
		}
		p {
			margin: 0;
		}
		.timelog {
			text-indent: .5in;
			padding-right: 100px;
		}
		.timelog > span {
			float: right;
		}

		.timelog > span.last {
			border-bottom: 1px solid;
		}
		.tbl-no-border {
			width: 100%;
			border-collapse: collapse;
		}
		table.tbl-no-border, table.tbl-no-border th,  table.tbl-no-border td {
			padding-right: 10px!important;
			border: none!important;
		}
	</style>
	<style type="text/css">
		@media print {
			@page {size: portrait;}
		}
	</style>
@endsection

@php
	$date = date('m-d-Y');
	$month = date('F');
	$year = date('Y');
	$rate = number_format(($record[0]->pay_rate /2), 2);
	$overtime = json_decode($record[0]->total_overtime_arr);
	$holiday = json_decode($record[0]->holiday_arr);
@endphp

@section('body') 
	@if($record == null)
	No record found.
	@else
	{{-- 
		# Column 6
		# Rows 11
	 --}}

	<div style="width: 100%;">
		<table class="c-table">
			<colgroup>
				<col width="10%">
				<col width="20%">
				<col width="20%">
				<col width="15%">
				<col width="15%">
				<col width="15%">
			</colgroup>
			<tr>
				<td>Name</td>
				<td colspan="2">@isset($record[0]) {{ $record[0]->empname }} @endif</td>
				<td>Tin/Employee No. <br>{{$record[0]->tin}}</td>
				<td colspan="2">Obligation Request No. <br>{{"<TinNumber>"}}</td>
			</tr>
			<tr>
				<td rowspan="3">Address</td>
				<td rowspan="2" colspan="2">{{"Guihulngan, Negros Oriental"}}</td>
				<td colspan="3">Responsibility Center</td>
			</tr>
			<tr>
				<td>Office/Unit/Project</td>
				<td colspan="2">Code</td>
			</tr>
			<tr style="height: 40px;">
				<td colspan="2"></td>
				<td> @isset($record[0]) {{ $record[0]->cc_desc }} @endif</td>
				<td colspan="2">@isset($record[0]) {{ $record[0]->department }} @endif</td>
			</tr>
			<tr>
				<td colspan="4">Explanation</td>
				<td colspan="2">Amount</td>
			</tr>
			<tr>
				<td colspan="4">
					<div style="margin: 7px;">
						<p style="text-indent: .3in;text-align: justify;">Payment of overtime pay of {{"MR/MS/MRS"}}. @isset($record[0]) {{ $record[0]->empname }} @endif for the month of {{$month}} {{ $year }} in the amount {{"<AMOUNT_IN_WORDS>"}}.</p><br>
						@if(isset($overtime))
						<p><b><u>Regular Days:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: @isset($record[0]) {{ $rate }} @endif/22days/8hours+25% = {{$regular_day_rate}}</i></b></p>
						<table class="tbl-no-border" style="margin-left: .5in; width: 93%;">
							<col>
							<col width="15%">
							<col width="10%">
							<tbody>
								@php
									$ot_t_total_rendered = 0.00;
								@endphp
								@foreach($overtime as $ot_t) 
									<tr>
										<td width="45%" style="text-align: right;">{{Date('F j, Y',strtotime($ot_t[0]))}} - {{Date('g:i A',strtotime($ot_t[1][0]))}} - {{Date('g:i A',strtotime($ot_t[1][1]))}} = {{$ot_t[2]}} hours</td>
										<?php 
											$ot_t_total_rendered += Core::ToHours($ot_t[2]);
										?>
										{{-- <td style="text-align: right;">{{ $ot_t['rendered'] }} @if($ot_t['rendered'] <= 1) hour @else hours @endif</td> --}}
										<td></td>
									</tr>
								@endforeach
								<tr>
									<td width="45%" style="text-align: right;">P 146.62 x {{$ot_t_total_rendered}} =</td>
									<td style="text-align: right;">{{146.62 * $ot_t_total_rendered}}</td>
									<td></td>
								</tr>
								<tr>
									<td width="45%" style="text-align: right;">P 146.62/60 x 0 =</td>
									<td style="text-align: right; border-bottom: 1px solid black!important;">0</td>
									<td style="border-bottom: 1px solid black!important;"></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: right;">₱ {{146.62 * $ot_t_total_rendered}}</td>
								</tr>
							</tbody>
						</table>
						@endif


						@if(isset($holiday))
						<p><b><u>Holiday:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: @isset($record[0]) {{ $rate }} @endif/22days/8hours+50% = {{$regular_day_rate}}</i></b></p>
						<table class="tbl-no-border" style="margin-left: .5in; width: 93%;">
							<col>
							<col width="15%">
							<col width="10%">
							<tbody>
								@php
									$ot_t_total_rendered_holiday = 0.00;
								@endphp
								@foreach($holiday as $ot_t) 
									<tr>
										<td width="45%" style="text-align: right;">{{Date('F j, Y',strtotime($ot_t[0]))}} - {{Date('g:i A',strtotime($ot_t[1][0]))}} - {{Date('g:i A',strtotime($ot_t[1][1]))}} = {{$ot_t[2]}} hours</td>
										<?php 
											$ot_t_total_rendered_holiday += Core::ToHours($ot_t[2]);
										?>
										{{-- <td style="text-align: right;">{{ $ot_t['rendered'] }} @if($ot_t['rendered'] <= 1) hour @else hours @endif</td> --}}
										<td></td>
									</tr>
								@endforeach
								<tr>
									<td width="45%" style="text-align: right;">P 146.62 x {{$ot_t_total_rendered_holiday}} =</td>
									<td style="text-align: right;">{{146.62 * $ot_t_total_rendered_holiday}}</td>
									<td></td>
								</tr>
								<tr>
									<td width="45%" style="text-align: right;">P 146.62/60 x 0 =</td>
									<td style="text-align: right; border-bottom: 1px solid black!important;">0</td>
									<td style="border-bottom: 1px solid black!important;"></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: right;">₱ {{146.62 * $ot_t_total_rendered_holiday}}</td>
								</tr>
							</tbody>
						</table>
						@endif


						@if(count($legal_timelogs) > 0 || count($special_timelogs) > 0)
						<p><b><u>Holidays:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: @isset($record[0]) {{ $rate }} @endif/22days/8hours+50% = {{$holiday_rate}}</i></b></p>
						<table class="tbl-no-border" style="margin-left: .5in; width: 93%">
							<col>
							<col width="15%">
							<col width="10%">
							<tbody>
								@php
									$ot_h_total_rendered = 0;
								@endphp
								@if(count($legal_timelogs) > 0)
									@foreach($legal_timelogs as $lt)
									@php
										$ot_h_total_rendered += $lt['rendered'];
									@endphp
										<tr>
											<td width="45%" style="text-align: right;">{{ $lt['date'] }} - {{ $lt['timelog1'] }} {{ $lt['timelog2'] }} =</td>
											<td style="text-align: right;">{{ $lt['rendered'] }} @if($lt['rendered'] <= 1) hour @else hours @endif</td>
											<td></td>
										</tr>
									@endforeach
								@endif
								@if(count($special_timelogs) > 0)
									@foreach($special_timelogs as $st)
									@php
										$ot_h_total_rendered += $st['rendered'];
									@endphp
										<tr>
											<td width="45%" style="text-align: right;">{{ $st['date'] }} - {{ $st['timelog1'] }} {{ $st['timelog2'] }} =</td>
											<td style="text-align: right;">{{ $st['rendered'] }} @if($st['rendered'] <= 1) hour @else hours @endif</td>
											<td></td>
										</tr>
									@endforeach
								@endif
								<tr>
									<td width="45%" style="text-align: right;">P {{$holiday_rate}} x {{$ot_h_total_rendered}} =</td>
									<td style="text-align: right;">{{$ls_holiday_amt}}</td>
									<td></td>
								</tr>
								<tr>
									<td width="45%" style="text-align: right;">P {{$holiday_rate}}/60 x 0 =</td>
									<td style="text-align: right; border-bottom: 1px solid black!important;">0</td>
									<td style="border-bottom: 1px solid black!important;"></td>
								</tr>
								<tr>
									<td colspan="3" style="text-align: right;">P {{$ls_holiday_amt}}</td>
								</tr>
							</tbody>
						</table>
						@endif
					</div>
					
				</td>
				
				<td colspan="2">
					
				</td>

				<tr>
					<td colspan="3" style="border-bottom: 0px solid; font-weight: bold;"><span class="mr-3" style="border-right: 1px solid; border-bottom: 1px solid;">A.</span>Certified</td>
					<td colspan="3" style="border-bottom: 0px; font-weight: bold;"><span class="mr-3" style="border-right: 1px solid; border-bottom: 1px solid;">B.</span>Certified</td>
				</tr>
				<tr>
					<td colspan="3" class="pl-5" style="border-top: 0px solid;">
						<div class="row">
							<div class="col-2 mt-2"><span class="p-2" style="border:1px solid;"></span></div>
							<div class="col-10"><span style="display: block;">Allotment obligated for the purpose</span>indicated above</div>

							<div class="col-2 mt-2 mb-2"><span style="border:1px solid; padding-right: 15px; padding-top: 2px;"></span></div>
							<div class="col-10 mt-1">Supporting documents complete</div>
						</div>
					</td>
					<td colspan="3" class="pl-5" style="border-top: 0px;">
						Funds Avaible
					</td>
				</tr>
				<tr>
					<td colspan="3" class="p-4" style="padding-bottom: 0px; !important">
						<div class="row">
							<div class="col-9">
								<span style="display: block; font-weight: bold; text-align: center;">MARIA JOFERDINE Y. CUI</span>
							</div>
							<div class="col-3">
								<span style="border-bottom: 1px solid; white-space: nowrap;">{{ $date }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-9" style="text-align: center;">
								City Accountant
							</div>
							<div class="col-3">
								<span class="ml-4">Date</span>
							</div>
						</div>
					</td>

					<td colspan="3" class="p-4" style="padding-bottom: 0px; !important">
						<div class="row">
							<div class="col-9">
								<span class=" ml-5 pl-4" style="display: block; font-weight: bold;">PAMELA A. CALIJAN</span>
							</div>
							<div class="col-3">
								<span style="border-bottom: 1px solid; white-space: nowrap;">{{ $date }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-9">
								<span style="white-space: nowrap;">Assistant City Treasure/OIC - City Treasure</span>
							</div>
							<div class="col-3">
								<span class="ml-4" style="text-align: right; white-space: nowrap;">Date</span>
							</div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td colspan="3" style="border-bottom: 0px; font-weight: bold;"><span class="mr-3" style="border-right: 1px solid; border-bottom: 1px solid; border-top:0px;">C.</span>Approved Payment</td>

					<td colspan="3" style="font-weight: bold;"><span class="mr-3" style="border-right: 1px solid; border-bottom: 1px solid;">D.</span>Received Payment</td>
				</tr>
				<tr>
					<td style="border-bottom: 0px; border-top: 0px;" colspan="3"></td>
					<td class="pb-4" style="font-size: 11px;">Check no</td>
					<td class="pb-4" style="font-size: 11px;">Bank Name</td>
					<td class="pb-4" style="font-size: 11px;">Date</td>
				</tr>

				<tr>

					<td colspan="3" class="p-4" style="padding-bottom: 0px; border-top: 0px; border-bottom: 0px; !important">
						<div class="row">
							<div class="col-9">
								<span  style="display: block; font-weight: bold; text-align: center;">CARLO JORGE JOAN L. REYES</span>
							</div>
							<div class="col-3">
								<span style="border-bottom: 1px solid;" style="text-align: right; white-space: nowrap;">{{ $date }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-9" style="text-align: center;">
								City Mayor
							</div>
							<div class="col-3">
								<span class="ml-4">Date</span>
							</div>
						</div>
					</td>

					<td colspan="3" class="p-4" style="padding-bottom: 0px; !important">
						<div class="row">
							<div class="col-9">
								<span class="" style="display: block; font-weight: bold; text-align: center;">@isset($record[0]) {{ $record[0]->empname }} @endif</span>
							</div>
							<div class="col-3">
								<span style="border-bottom: 1px solid; text-align: right; white-space: nowrap;">{{ $date }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-9" style="text-align: center;">
								Printed Name
							</div>
							<div class="col-3">
								<span class="ml-4" style="text-align: center; white-space: nowrap;">Date</span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="border: 0px;"></td>
					<td class="pb-4" style="font-size: 11px;">OR/Other Documents</td>
					<td class="pb-4" style="font-size: 11px;">JEV No.</td>
					<td class="pb-4" style="font-size: 11px;">Date</td>
				</tr>
			</tr>
		</table>
	</div>
	@endif
@endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection