{{-- @extends('layouts.print_layout')


@section('body')  --}}
@php
	$inf = $data['inf'];
	$record = $data['record'];
@endphp
	<table>
		<thead>
			<tr><th colspan="44">{{strtoupper($inf->title)}}</th></tr>
			<tr><th colspan="44">{{Core::company_name()}}</th></tr>
			<tr><th colspan="44">{{isset($inf->ofc) ? $inf->ofc->cc_desc : "OFFICE NOT FOUND"}}</th></tr>
			<tr><th colspan="44">{{$inf->payroll_period}}</th></tr>
			<tr>
				<th rowspan="4">ITEM NO.</th>
				<th rowspan="4">NAME</th>
				<th rowspan="4">No.</th>
				<th rowspan="4">POSITION</th>
				<th rowspan="4">Rate</th>
				<th rowspan="4">No. of Absences w/o Pay</th>
				<th rowspan="4">Rate- Computed Absences</th>
				<th rowspan="4">ALLOWANCE (PERA)</th>
				<th rowspan="4">HAZARD DUTY PAY</th>
				<th rowspan="2" colspan="4">ALLOWANCE</th>
				<th rowspan="4">AMOUNT EARNED</th>
				<th colspan="22">PERSONAL DEDUCTIONS</th>
				<th colspan="4">GOVERNMENT SHARES</th>
				<th rowspan="4">No.</th>
				<th rowspan="4">NET AMOUNT RECEIVED</th>
				<th rowspan="4">AMOUNT PAID</th>
				<th rowspan="4">Signature of Payee</th>
			</tr>
			<tr>
				<th rowspan="3">Withholding TAX</th>
				<th rowspan="3">PhilHealth</th>

				<th colspan="3">PAG-IBIG</th>
				@php
					$pd = ['JGM', 'LBP', 'CFI', 'DCCCO', 'PEI 2014 REFUND', 'REFUND for Cash Advance'];
				@endphp
				@if(count($pd) > 0)
					@for($i=0;$i<count($pd);$i++)
						<th rowspan="3">{{$pd[$i]}}</th>					
					@endfor
				@endif

				<th colspan="10">GSIS</th>
				<th rowspan="3">TOTAL DEDUCTIONS</th>

				<th rowspan="3">PhilHealth</th>
				<th rowspan="3">RETIREMENT &amp; LIFE INSURANCE PREMIUMS</th>
				<th rowspan="3">PAG-IBIG HDMF CONT.</th>
				<th rowspan="3">STATE INS.</th>
			</tr>
			<tr>
				<th rowspan="2">LAUNDRY</th>
				<th colspan="3">SUBSISTENCE</th>

				<th rowspan="2">HDMF CONT.</th>
				<th rowspan="2">MPL</th>
				<th rowspan="2">HOUSING LOAN</th>

				<th rowspan="2">RETIREMENT &amp; LIFE INSURANCE PREMIUMS</th>
				<th rowspan="2">EDU. ASSISTANCE</th>
				<th rowspan="2">CEAP</th>
				<th rowspan="2">EMERGENCY LOAN</th>
				<th rowspan="2">COMBO LOAN</th>
				<th rowspan="2">POLICY LOAN REG</th>
				<th rowspan="2">OPTIONAL POLICY LOAN</th>
				<th rowspan="2">OULI PREMIUM</th>
				<th rowspan="2">UMID E-CARD PLUS</th>
				<th rowspan="2">GSIS H/L</th>
			</tr>
			<tr>
				<th>LEAVE</th>
				<th>TRAVEL</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody>
			@if(count($record) > 0) @for($i=0;$i<count($record);$i++)
			@php
				$row = $record[$i];/* dd($row);*/
				$no = $i+1;
				$pera = 0;
				$hazard_duty_pay = 0;
				$allowance = 0;
				$allowance_laundry = 0;
				$oe = json_decode($row->other_earnings);
				if (count($oe) > 0) {
					for ($j=0; $j < count($oe); $j++) { 
						list($id, $code, $amt) = $oe[$j];
						if ($code == "PERA") {
							$pera += $amt;
						} elseif ($code == "HAZARDPAY") {
							$hazard_duty_pay += $amt;
						} elseif ($code == "ALLOWNC") {
							if ($id == "A1") {
								$allowance_laundry += $amt;
							}
							$allowance += $amt;
						}
					}
				}
			@endphp
			<tr>
				<td>ACC-{{$row->emp_pay_code}}</td> {{-- Item No. --}}
				<td>{{strtoupper($row->empname)}}</td> {{-- Name --}}
				<td>{{$no}}</td> {{-- No. --}}
				<td>-</td> {{-- Position --}}
				<td>{{($row->rate!=0) ? $row->rate : "-"}}</td> {{-- Rate --}}
				<td>{{($row->abcences!=9) ? $row->abcences : "-"}}</td> {{-- No. of Absence w/o Pay --}}
				<td>{{($row->basic_pay) ? $row->basic_pay : "-"}}</td> {{-- Rate Computed Absences --}}
				<td>{{$pera}}</td> {{-- PERA --}}
				<td>{{$hazard_duty_pay}}</td> {{-- Hazard Duty Pay --}}
				<td>{{$allowance_laundry}}</td> {{-- Allowance - Laundry --}}
				<td>-</td> {{-- Allowance - Subsistence - Leave --}}
				<td>-</td> {{-- Allowance - Subsistence - Travel --}}
				<td>{{$allowance}}</td> {{-- Allowance - Subsistence - Total --}}
				<td>-</td> {{-- Amount Earned --}}
				<td>-</td> {{-- Personal Deductions - Withholding Tax --}}
				<td>-</td> {{-- Personal Deductions - Philhealth --}}
				<td>-</td> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}
				<td>-</td> {{-- Personal Deductions - Pag-ibig - MPL. --}}
				<td>-</td> {{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
				<td>-</td> {{-- Personal Deductions - JGM --}}
				<td>-</td> {{-- Personal Deductions - LBP --}}
				<td>-</td> {{-- Personal Deductions - CFI --}}
				<td>-</td> {{-- Personal Deductions - DCCCO --}}
				<td>-</td> {{-- Personal Deductions - PEI 2014 Refund --}}
				<td>-</td> {{-- Personal Deductions - Refund for cash advance --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Edu. Asstance --}}
				<td>-</td> {{-- Personal Deductions - GSIS - CEAP --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Emergency Loan --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Combo Loan --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Policy Loan Reg --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Optional Policy Loan --}}
				<td>-</td> {{-- Personal Deductions - GSIS - Ouli Permium --}}
				<td>-</td> {{-- Personal Deductions - GSIS - UMID E-Card Plus --}}
				<td>-</td> {{-- Personal Deductions - GSIS - GSIS H/L --}}
				<td>-</td> {{-- Total Deductions --}}
				<td>-</td> {{-- Government Shares - Philhealth --}}
				<td>-</td> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<td>-</td> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<td>-</td> {{-- Government Shares - State Ins. --}}
				<td>-</td> {{-- No. --}}
				<td>-</td> {{-- Net Amount Received --}}
				<td>-</td> {{-- Amount Paid --}}
				<td>-</td> {{-- Signature of Payee --}}
			</tr>
			@endfor @endif
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4">Total</th>
				<th>-</th> {{-- Rate --}}
				<th>-</th> {{-- No. of Absence w/o Pay --}}
				<th>-</th> {{-- Rate Computed Absences --}}
				<th>-</th> {{-- PERA --}}
				<th>-</th> {{-- Hazard Duty Pay --}}
				<th>-</th> {{-- Allowance - Laundry --}}
				<th>-</th> {{-- Allowance - Subsistence - Leave --}}
				<th>-</th> {{-- Allowance - Subsistence - Travel --}}
				<th>-</th> {{-- Allowance - Subsistence - Total --}}
				<th>-</th> {{-- Amount Earned --}}
				<th>-</th> {{-- Personal Deductions - Withholding Tax --}}
				<th>-</th> {{-- Personal Deductions - Philhealth --}}
				<th>-</th> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}
				<th>-</th> {{-- Personal Deductions - Pag-ibig - MPL. --}}
				<th>-</th> {{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
				<th>-</th> {{-- Personal Deductions - JGM --}}
				<th>-</th> {{-- Personal Deductions - LBP --}}
				<th>-</th> {{-- Personal Deductions - CFI --}}
				<th>-</th> {{-- Personal Deductions - DCCCO --}}
				<th>-</th> {{-- Personal Deductions - PEI 2014 Refund --}}
				<th>-</th> {{-- Personal Deductions - Refund for cash advance --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Edu. Asstance --}}
				<th>-</th> {{-- Personal Deductions - GSIS - CEAP --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Emergency Loan --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Combo Loan --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Policy Loan Reg --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Optional Policy Loan --}}
				<th>-</th> {{-- Personal Deductions - GSIS - Ouli Permium --}}
				<th>-</th> {{-- Personal Deductions - GSIS - UMID E-Card Plus --}}
				<th>-</th> {{-- Personal Deductions - GSIS - GSIS H/L --}}
				<th>-</th> {{-- Total Deductions --}}
				<th>-</th> {{-- Government Shares - Philhealth --}}
				<th>-</th> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<th>-</th> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<th>-</th> {{-- Government Shares - State Ins. --}}
				<th>-</th> {{-- No. --}}
				<th>-</th> {{-- Net Amount Received --}}
				<th>-</th> {{-- Amount Paid --}}
				<th>-</th> {{-- Signature of Payee --}}
			</tr>
		</tfoot>
	</table>
{{-- @endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection --}}

