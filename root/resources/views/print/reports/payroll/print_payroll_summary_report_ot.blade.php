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
	</style>
	<style type="text/css">
		@media print {
			@page {size: portrait;}
		}
	</style>
@endsection

@php
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
			<tr style="text-align: center;">
				<td>1</td>
				<td>2</td>
				<td>3</td>
				{{-- # Half --}}
				<td>4</td>
				<td>5</td>
				<td>6</td>
			</tr>
			<tr>
				<td>Name</td>
				<td colspan="2">{{"<Name>"}}</td>
				<td>Tin/Employee No. <br>{{"<TinNumber>"}}</td>
				<td colspan="2">Obligation Request No. <br>{{"<TinNumber>"}}</td>
			</tr>
			<tr>
				<td rowspan="3">Address</td>
				<td rowspan="2" colspan="2">{{"<AddressHere>"}}</td>
				<td colspan="3">Responsibility Center</td>
			</tr>
			<tr>
				<td>Office/Unit/Project</td>
				<td colspan="2">Code</td>
			</tr>
			<tr style="height: 40px;">
				<td colspan="2"></td>
				<td>{{"<OfficeName>"}}</td>
				<td colspan="2">{{"<OfficeCode>"}}</td>
			</tr>
			<tr>
				<td colspan="4">Explanation</td>
				<td colspan="2">Amount</td>
			</tr>
			<tr>
				<td colspan="4">
					<div style="margin: 7px;">
						<p style="text-indent: .3in;text-align: justify;">Payment of overtime pay of {{"<MR/MS/MRS"}}. {{"<NAME>"}} for the month of {{"<MONTH&YEAR"}} in the amount {{"<AMOUNT_IN_WORDS>"}}.</p><br>
						<p><b><u>Regular Days:</u></b></p>
						<p style="text-indent: .3in;"><b><i>Basic Salary: 20,644.00/22days/8days+25%</i></b></p>
						@for($i=6;$i<=9;$i++)
						<p class="timelog">May {{$i}}, 2019 - 6:00pm - 9:00pm 10:00pm - 11:00pm <span>= 4 hours</span></p>
						@endfor
						<p class="timelog">May {{$i}}, 2019 - 6:00pm <span class="last">= 3 hours</span></p>
						
					</div>
				</td>
				<td colspan="2"></td>
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