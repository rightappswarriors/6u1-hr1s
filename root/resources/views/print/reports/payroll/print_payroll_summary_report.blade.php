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
	</style>
	<style type="text/css">
		@media print {
			@page {size: landscape}
		}
	</style>
@endsection

@section('body') 
	<table class="bordered" style="widows: 100%;">
		<col width="25%">
		<thead class="no-border" style="text-align: left;">
			<tr>
				<th>ACC NO:</th>
				<th>{{$p->item_no}}</th>
				<th></th>
				<th></th>
				<th></th>
				<th colspan="2">PERIOD START DATE:</th>
				<th></th>
			</tr>
			<tr>
				<th>NAME, FIRST NAME:</th>
				<th>{{Employee::Name($p->empid)}}</th>
				<th colspan="3"></th>
				<th colspan="2">PERIOD ENDING DATE:</th>
				<th></th>
			</tr>
			<tr>
				<th>POSITION:</th>
				<th>{{$jt}}</th>
				<th></th>
				<th></th>
				<th></th>
				<th colspan="2">PAYABLE DATE:</th>
				<th></th>
			</tr>
		</thead>
		<tbody class="edge-border-only">
			<tr>
				<th colspan="2">Earning</th>
				<th>HOURLY RATE</th>
				<th>HOURS</th>
				<th>AMOUNT</th>
				<th>DEDUCTIONS</th>
				<th>CURR. AMOUNTS</th>
				<th>ACC. AMOUNTS</th>
			</tr>
			<tr>
				<td colspan="2">| Monthly Rate:</td>
				<td></td>
				<td></td>
				<td></td>
				<td>PhilHealth</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr>
				<td colspan="2">| Daily Rate:</td>
				<td></td>
				<td></td>
				<td></td>
				<td>GSIS</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr>
				<td colspan="2">Regular Worked</td>
				<td></td>
				<td></td>
				<td></td>
				<td>Pag-IBIG</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr>
				<td colspan="2">OT : Regular Worked</td>
				<td></td>
				<td></td>
				<td></td>
				<td>Withholding Tax</td>
				<td>0</td>
				<td>0</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th colspan="3">REDEEMABLES</th>
				<th colspan="2">ACC. AMOUNTS</th>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="5">-</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th>SUMMARY</th>
				<th>CURR. AMOUNTS</th>
				<th>ACC. AMOUNTS</th>
				<th>BENEFITS DETAIL</th>
				<th>CURR AMOUNTS</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<td>EARNINGS</td>
				<td>0</td>
				<td>0</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>DEDUCTIONS</td>
				<td>0</td>
				<td>0</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
@endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection