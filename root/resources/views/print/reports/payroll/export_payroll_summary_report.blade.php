{{-- @extends('layouts.print_layout')


@section('body')  --}}
@php
	$inf = $data['inf'];
	$record = $data['record'];
	//initialization of array for hardcoded assigning of value by Syrel
	$statIns = 100;
	$runningRowTotal = [];
	$initilize = [
		//for gsis
		[1,2,3,4,5,6,7,8,9],
		//for other deductions
		['PD-1','PD-2','PD-3','PD-4','PD-5','PD-6'],
		[2,3]
	];
@endphp
	<table border="1">
		<thead>
			<tr><th colspan="44">{{strtoupper(($inf->title ?? ''))}}</th></tr>
			<tr><th colspan="44">{{Core::company_name()}}</th></tr>
			<tr><th colspan="44">{{isset($inf->ofc) ? $inf->ofc->cc_desc : ""}}</th></tr>
			<tr><th colspan="44">{{($inf->payroll_period ?? '')}}</th></tr>
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
				<td>{{strtoupper(($row->empname ?? ''))}}</td> {{-- Name --}}
				<td>{{$no}}</td> {{-- No. --}}
				<td>{{ucfirst(($row->position ?? ''))}}</td> {{-- Position --}}
				<td>{{($row->rate!=0) ? number_format($row->rate,2) : "-"}} <?php $runningRowTotal['rate'] = isset($runningRowTotal['rate']) ? ($row->rate !=0 ? $row->rate : 0) + $runningRowTotal['rate'] : ($row->rate !=0 ? $row->rate : 0) ?></td> {{-- Rate --}}
				<td>{{($row->abcences!=9) ? $row->abcences : "-"}}</td> {{-- No. of Absence w/o Pay --}}
				<td>{{($row->basic_pay) ? number_format($row->basic_pay,2) : "-"}}</td> {{-- Rate Computed Absences --}}
				<td>{{number_format($pera,2)}}</td><?php $runningRowTotal['pera'] = isset($runningRowTotal['pera']) ? $pera + $runningRowTotal['pera'] : $pera ?> {{-- PERA --}}
				<td>{{number_format($hazard_duty_pay,2)}}<?php $runningRowTotal['hazard_duty'] = isset($runningRowTotal['hazard_duty']) ? $hazard_duty_pay + $runningRowTotal['hazard_duty'] : $hazard_duty_pay ?></td> {{-- Hazard Duty Pay --}}
				<td>{{number_format($allowance_laundry,2)}}<?php $runningRowTotal['allowance_laundry'] = isset($runningRowTotal['allowance_laundry']) ? $allowance_laundry + $runningRowTotal['allowance_laundry'] : $allowance_laundry ?></td> {{-- Allowance - Laundry --}}
				<td>-</td> {{-- Allowance - Subsistence - Leave , not sure as of Paolo--}}
				<td>-</td> {{-- Allowance - Subsistence - Travel , not sure as of Paolo --}}
				<td>{{number_format($allowance,2)}}<?php $runningRowTotal['allowance'] = isset($runningRowTotal['allowance']) ? $allowance + $runningRowTotal['allowance'] : $allowance ?></td> {{-- Allowance - Subsistence - Total --}}
				<td>{{number_format($row->rate - $record[$i]->net_pay,2)}}<?php $runningRowTotal['amount_earned'] = isset($runningRowTotal['amount_earned']) ? ($row->rate - $record[$i]->net_pay) + $runningRowTotal['amount_earned'] : ($row->rate - $record[$i]->net_pay) ?></td> {{-- Amount Earned --}}
				<td>{{number_format($record[$i]->w_tax,2)}}<?php $runningRowTotal['withholding_tax'] = isset($runningRowTotal['withholding_tax']) ? $record[$i]->w_tax + $runningRowTotal['withholding_tax'] : $record[$i]->w_tax ?></td> {{-- Personal Deductions - Withholding Tax --}}
				<td>{{$record[$i]->philhealth_cont_b}}<?php $runningRowTotal['pphilhealth'] = isset($runningRowTotal['pphilhealth']) ? $record[$i]->philhealth_cont_b + $runningRowTotal['pphilhealth'] : $record[$i]->philhealth_cont_b ?></td> {{-- Personal Deductions - Philhealth --}}
				<td>{{$record[$i]->pagibig_cont_b}}<?php $runningRowTotal['pphilhealthhdmf'] = isset($runningRowTotal['pphilhealthhdmf']) ? $record[$i]->pagibig_cont_b + $runningRowTotal['pphilhealthhdmf'] : $record[$i]->pagibig_cont_b ?></td> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}

				<?php 
					$pagIbigDeduction = 0;
					$otherDeduction = json_decode($record[$i]->pagibig_cont_a);
					foreach($initilize[2] as $ini){
						if(isset($otherDeduction)){
							foreach($otherDeduction as $od){ 
								if($ini === $od[0]){
									$pagIbigDeduction = $od[2];
								}
							}
						}
						?>
						{{-- Personal Deductions - Pag-ibig - MPL. --}}
						{{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
						<td>{{number_format($pagIbigDeduction,2)}}</td>
						<?php
						$runningRowTotal['pagibigDeductionLoop'][$ini] = (isset($runningRowTotal['pagibigDeductionLoop'][$ini]) ? $pagIbigDeduction + $runningRowTotal['pagibigDeductionLoop'][$ini] : $pagIbigDeduction);
						$pagIbigDeduction = 0;
					}
				?>

				<?php 
					$otherDeductionValue = 0;
					$otherDeduction = json_decode($record[$i]->other_deduction);
					foreach($initilize[1] as $ini){
						if(isset($otherDeduction)){
							foreach($otherDeduction as $od){ 
								if($ini === $od[0]){
									$otherDeductionValue = $od[2];
								}
							}
						}

						?>
						{{-- Personal Deductions - JGM --}}
						{{-- Personal Deductions - LBP --}}
						{{-- Personal Deductions - CFI --}}
						{{-- Personal Deductions - DCCCO --}}
						{{-- Personal Deductions - PEI 2014 Refund --}}
						{{-- Personal Deductions - Refund for cash advance --}}
						<td>{{number_format($otherDeductionValue,2)}}</td>
						<?php
						$runningRowTotal['otherDeductionLoop'][$ini] = (isset($runningRowTotal['otherDeductionLoop'][$ini]) ? $otherDeductionValue + $runningRowTotal['otherDeductionLoop'][$ini] : $otherDeductionValue);
						$otherDeductionValue = 0;
					}
				?>

				<td>{{$record[$i]->sss_cont_b}}<?php $runningRowTotal['gsisretirement'] = isset($runningRowTotal['gsisretirement']) ? $record[$i]->sss_cont_b + $runningRowTotal['gsisretirement'] : $record[$i]->sss_cont_b ?></td> {{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}

				<?php 
					$gsisValue = 0;
					$gsis = json_decode($record[$i]->sss_cont_a);
					foreach($initilize[0] as $ini){
						if(isset($gsis)){
							foreach($gsis as $sss){ 
								if($ini === $sss[0]){
									$gsisValue = $sss[2];
								}
							}
						}

						?>
						{{-- Personal Deductions - GSIS - Edu. Asstance --}}
						{{-- Personal Deductions - GSIS - Emergency Loan --}}
						{{-- Personal Deductions - GSIS - Combo Loan --}}
						{{-- Personal Deductions - GSIS - Policy Loan Reg --}}
						{{-- Personal Deductions - GSIS - Optional Policy Loan --}}
						{{-- Personal Deductions - GSIS - Ouli Permium --}}
						{{-- Personal Deductions - GSIS - UMID E-Card Plus --}}
						{{-- Personal Deductions - GSIS - GSIS H/L --}}
						<td>{{number_format($gsisValue,2)}}</td>
						<?php
						$runningRowTotal['gsisLoop'][$ini] = (isset($runningRowTotal['gsisLoop'][$ini]) ? $gsisValue + $runningRowTotal['gsisLoop'][$ini] : $gsisValue);
						$gsisValue = 0;
					}
				?>
				<td>{{number_format($record[$i]->total_deductions,2)}}<?php $runningRowTotal['total_deductions'] = isset($runningRowTotal['total_deductions']) ? $record[$i]->total_deductions + $runningRowTotal['total_deductions'] : $record[$i]->total_deductions ?></td> {{-- Total Deductions --}}
				<td>{{$record[$i]->philhealth_cont_c}}<?php $runningRowTotal['gphilhealth'] = isset($runningRowTotal['gphilhealth']) ? $record[$i]->philhealth_cont_c + $runningRowTotal['gphilhealth'] : $record[$i]->philhealth_cont_c ?></td> {{-- Government Shares - Philhealth --}}
				<td>{{$record[$i]->sss_cont_c}}<?php $runningRowTotal['retirement'] = isset($runningRowTotal['retirement']) ? $record[$i]->sss_cont_c + $runningRowTotal['retirement'] : $record[$i]->sss_cont_c ?></td> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<td>{{$record[$i]->pagibig_cont_c}}<?php $runningRowTotal['pagibighdmf'] = isset($runningRowTotal['pagibighdmf']) ? $record[$i]->pagibig_cont_c + $runningRowTotal['pagibighdmf'] : $record[$i]->pagibig_cont_c ?></td> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<td>{{number_format($statIns,2)}}<?php $runningRowTotal['statin'] = isset($runningRowTotal['statin']) ? $statIns + $runningRowTotal['statin'] : $statIns ?></td> {{-- Government Shares - State Ins. --}}
				<td>{{$i+1}}</td> {{-- No. --}} {{-- running increment --}}
				<td>{{number_format($record[$i]->net_pay,2)}}<?php $runningRowTotal['netamount'] = isset($runningRowTotal['netamount']) ? $record[$i]->net_pay + $runningRowTotal['netamount'] : $record[$i]->net_pay ?></td> {{-- Net Amount Received --}}
				<td>{{number_format($record[$i]->net_pay,2)}}<?php $runningRowTotal['amountpaid'] = isset($runningRowTotal['amountpaid']) ? $record[$i]->net_pay + $runningRowTotal['amountpaid'] : $record[$i]->net_pay ?></td> {{-- Amount Paid --}}
				<td>-</td> {{-- Signature of Payee --}}
			</tr>
			@endfor @endif
		</tbody>
		<tfoot>
			{{-- {{dd($runningRowTotal)}} --}}
			<tr>
				<th colspan="4">Total</th>
				<th>{{number_format(($runningRowTotal['rate'] ?? 0),2)}}</th> {{-- Rate --}}
				<th>-</th> {{-- No. of Absence w/o Pay --}}
				<th>-</th> {{-- Rate Computed Absences --}}
				<th>{{number_format(($runningRowTotal['pera'] ?? 0),2)}}</th> {{-- PERA --}}
				<th>{{number_format(($runningRowTotal['hazard_duty'] ?? 0),2)}}</th> {{-- Hazard Duty Pay --}}
				<th>{{number_format(($runningRowTotal['allowance_laundry'] ?? 0),2)}}</th> {{-- Allowance - Laundry --}}
				<th>-</th> {{-- Allowance - Subsistence - Leave --}}
				<th>-</th> {{-- Allowance - Subsistence - Travel --}}
				<th>{{number_format(($runningRowTotal['allowance'] ?? 0),2)}}</th> {{-- Allowance - Subsistence - Total --}}
				<th>{{number_format(($runningRowTotal['amount_earned'] ?? 0),2)}}</th> {{-- Amount Earned --}}
				<th>{{number_format(($runningRowTotal['withholding_tax'] ?? 0),2)}}</th> {{-- Personal Deductions - Withholding Tax --}}
				<th>{{number_format(($runningRowTotal['pphilhealth'] ?? 0),2)}}</th> {{-- Personal Deductions - Philhealth --}}
				<th>{{number_format(($runningRowTotal['pphilhealthhdmf'] ?? 0),2)}}</th> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}


				{{-- Personal Deductions - Pag-ibig - MPL. --}}
				{{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
				@isset($runningRowTotal['pagibigDeductionLoop'])
				@foreach($runningRowTotal['pagibigDeductionLoop'] as $thisloop)
				<th>{{number_format($thisloop,2)}}</th>
				@endforeach 
				@endisset

				{{-- Personal Deductions - JGM --}}
				{{-- Personal Deductions - LBP --}}
				{{-- Personal Deductions - CFI --}}
				{{-- Personal Deductions - DCCCO --}}
				{{-- Personal Deductions - PEI 2014 Refund --}}
				{{-- Personal Deductions - Refund for cash advance --}}
				@isset($runningRowTotal['otherDeductionLoop'])
				@foreach($runningRowTotal['otherDeductionLoop'] as $thisloop)
				<th>{{number_format($thisloop,2)}}</th>
				@endforeach 
				@endisset

				<th>{{number_format(($runningRowTotal['gsisretirement'] ?? 0),2)}}</th>{{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}
				{{-- Personal Deductions - GSIS - Edu. Asstance --}}
				{{-- Personal Deductions - GSIS - CEAP --}}
				{{-- Personal Deductions - GSIS - Emergency Loan --}}
				{{-- Personal Deductions - GSIS - Combo Loan --}}
				{{-- Personal Deductions - GSIS - Policy Loan Reg --}}
				{{-- Personal Deductions - GSIS - Optional Policy Loan --}}
				{{-- Personal Deductions - GSIS - Ouli Permium --}}
				{{-- Personal Deductions - GSIS - UMID E-Card Plus --}}
				 {{-- Personal Deductions - GSIS - GSIS H/L --}}
				@isset($runningRowTotal['gsisLoop'])
				@foreach($runningRowTotal['gsisLoop'] as $thisloop)
				<th>{{number_format($thisloop,2)}}</th>
				@endforeach 
				@endisset

				<th>{{number_format(($runningRowTotal['total_deductions'] ?? 0),2)}}</th> {{-- Total Deductions --}}
				<th>{{number_format(($runningRowTotal['gphilhealth'] ?? 0),2)}}</th> {{-- Government Shares - Philhealth --}}
				<th>{{number_format(($runningRowTotal['retirement'] ?? 0),2)}}</th> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<th>{{number_format(($runningRowTotal['pagibighdmf'] ?? 0),2)}}</th> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<th>{{number_format(($runningRowTotal['statin'] ?? 0),2)}}</th> {{-- Government Shares - State Ins. --}}
				<th>-</th> {{-- No. --}}
				<th>{{number_format(($runningRowTotal['netamount'] ?? 0),2)}}</th> {{-- Net Amount Received --}}
				<th>{{number_format(($runningRowTotal['amountpaid'] ?? 0),2)}}</th> {{-- Amount Paid --}}
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

