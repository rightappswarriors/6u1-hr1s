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
			{{-- <tr style="text-align: center;">
				<td>1</td>
				<td>2</td>
				<td>3</td>
				
				<td>4</td>
				<td>5</td>
				<td>6</td>
			</tr> --}}
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
						<p style="text-indent: .3in;text-align: justify;">Payment of overtime pay of {{"<MR/MS/MRS"}}. @isset($record[0]) {{ $record[0]->empname }} @endif for the month of {{$month}} {{ $year }} in the amount {{"<AMOUNT_IN_WORDS>"}}.</p><br>
						<p><b><u>Regular Days:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: @isset($record[0]) {{ $record[0]->pay_rate }} @endif {{-- /22days/8days+25% = P146.62 --}}</i></b></p>
						<table class="tbl-no-border">
							<colgroup>
								<col width="20%">
								<col>
								<col width="25%">
								<col width="25%">
								<col width="5%">
								<col>
							</colgroup>
							<tbody>
								@if(count($ot_timelogs) > 0)
									@foreach($ot_timelogs as $ot_timelogs) 
										<tr>
											<td>{{ $ot_timelogs['date'] }}</td>
											<td>-</td>
											<td>{{ $ot_timelogs['timelog1'] }}</td>
											<td>{{ $ot_timelogs['timelog2'] }}</td>
											<td>=</td>
											<td>{{ $ot_timelogs['rendered'] }}</td>
										</tr>
											@php
												$rendered = $ot_timelogs['rendered'];
												$payrate = $record[0]->pay_rate;
												$divide = $payrate / 8;
												$sum = $rendered * $divide;
											@endphp
										<tr>
											<td colspan="3"></td>
											<td style="text-align: right;"><span style="font-weight: bold;">P @isset($record[0]) {{ $divide }} @endif</span> x {{ $ot_timelogs['rendered'] }}</td>
											<td>=</td>
											
											<td>{{ number_format($sum, 2, '.', ',') }}</td>
										</tr>
										<tr>
											<td colspan="3"></td>
											<td style="text-align: right;"><span style="font-weight: bold;">P rendered/60</span>x0</td>
											<td>=</td>
											<td style="border-bottom: 1px solid; border: solid #000;border-width: 0 1px;">0</td>	
										</tr>
										<tr>
											<td colspan="5"></td>
											<td style="font-weight: bold; border-top: 1px solid;">P {{ $sum }}</td>	
										</tr>
									@endforeach
								@else
									<p>-No Overtime-</p>	
								@endif
							</tbody>
						</table>
						<p><b><u>Holidays:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: @isset($record[0]) {{ $record[0]->pay_rate }} @endif {{-- /22days/8days+50% = P175.94 --}}</i></b></p>
						<table class="tbl-no-border">
							<colgroup>
								<col width="20%">
								<col>
								<col width="25%">
								<col width="25%">
								<col width="5%">
								<col>
							</colgroup> 
							<tbody>
								@if(count($legal_timelogs) > 0)
									@foreach($legal_timelogs as $legal_timelogs)	
										<tr>
											<td>{{ $ot_timelogs['date'] }}</td>
											<td>-</td>
											<td>{{ $legal_timelogs['timelog1'] }}</td>
											<td>{{ $legal_timelogs['timelog2'] }} }}</td>
											<td>=</td>
											<td>5 hours</td>
										</tr>	
									@endforeach
									@foreach($special_timelogs as $special_timelogs)
										<tr>
											<td>{{ $special_timelogs['date'] }}</td>
											<td>-</td>
											<td>{{ $special_timelogs['timelog1'] }}</td>
											<td>{{ $special_timelogs['timelog2'] }} }}</td>
											<td>=</td>
											<td>5 hours</td>
										</tr>
									@endforeach	
								@else
									<p>-No Overtime-</p>
								@endif
							</tbody>
								
							
						</table>
						
						{{-- @for($i=6;$i<=9;$i++)
						<p class="timelog">May {{$i}}, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm <span>= 4 hours</span></p>
						@endfor
						<p class="timelog">May {{$i}}, 2019 - 6:00pm <span class="last">= 3 hours</span></p> --}}
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