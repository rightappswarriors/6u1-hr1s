{{-- @extends('layouts.print_layout')


@section('body')  --}}
@php
	$inf = $data['inf'];
	$record = $data['record'];
	// $ppType = ($data['payroll_period'] ?? 1);
	$forOthers = '';
	$forImportant = '';
	// switch ($ppType) {
	// 	case '1':
	// 		$forOthers = 'style=display:block-inline!important ';
	// 		$forImportant = 'style=display:block-inline!important ';
	// 		break;
	// 	case '2':
	// 		$forOthers = 'style=display:none!important ';
	// 		$forImportant = 'style=display:block-inline!important ';
	// 		break;
	// }
	//initialization of array for hardcoded assigning of value by Syrel
	$runningRowTotal = [];
	$initilize = [
		//for gsis
		[1,2,3,4,5,6,7,8,9],
		//for other deductions
		['PD-1','PD-2','PD-3','PD-4','PD-5','PD-6'],
		// pagibig
		[2,3]
	];
@endphp
	<table border="1">
		<thead>
			<tr><th style="text-align: center;" colspan="44">{{strtoupper(($inf->title ?? ''))}}</th></tr>
			<tr><th style="text-align: center;" colspan="44">{{Core::company_name()}}</th></tr>
			<tr><th style="text-align: center;" colspan="44">{{isset($inf->ofc) ? $inf->ofc->cc_desc : ""}}</th></tr>
			<tr><th style="text-align: center;" colspan="44">{{($inf->payroll_period ?? '')}}</th></tr>
			<tr>
				<th {{$forImportant}} rowspan="4">ITEM NO.</th>
				<th {{$forImportant}} rowspan="4">NAME</th>
				<th {{$forImportant}} rowspan="4">No.</th>
				<th {{$forImportant}} rowspan="4">POSITION</th>
				<th {{$forImportant}} rowspan="4">Rate</th>
				<th {{$forOthers}}rowspan="4">No. of Absences w/o Pay</th>
				<th {{$forOthers}} rowspan="4">Rate- Computed Absences</th>
				<th {{$forOthers}} rowspan="4">ALLOWANCE (PERA)</th>
				<th {{$forOthers}} rowspan="4">HAZARD DUTY PAY</th>
				<th {{$forOthers}} rowspan="2" colspan="4">ALLOWANCE</th>
				<th {{$forOthers}} rowspan="4">AMOUNT EARNED</th>
				<th {{$forOthers}} colspan="22">PERSONAL DEDUCTIONS</th>
				<th {{$forOthers}} colspan="4">GOVERNMENT SHARES</th>
				<th {{$forOthers}} rowspan="4">No.</th>
				<th {{$forImportant}} rowspan="4">NET AMOUNT RECEIVED</th>
				<th {{$forImportant}} rowspan="4">AMOUNT PAID</th>
				<th {{$forImportant}} rowspan="4">Signature of Payee</th>
			</tr>
			<tr>
				<th {{$forOthers}} rowspan="3">Withholding TAX</th>
				<th {{$forOthers}} rowspan="3">PhilHealth</th>

				<th {{$forOthers}} colspan="{{count(Pagibig::Get_All_Sub())}}">PAG-IBIG</th>
				@php
					$pd = ['JGM', 'LBP', 'CFI', 'DCCCO', 'PEI 2014 REFUND', 'REFUND for Cash Advance'];
				@endphp
				@if(count($pd) > 0)
					@for($i=0;$i<count($pd);$i++)
						<th {{$forOthers}} rowspan="3">{{$pd[$i]}}</th>					
					@endfor
				@endif

				{{-- <th colspan="10">GSIS</th> --}}
				<th {{$forOthers}} colspan="{{count(SSS::Get_All_Sub())}}">GSIS</th>
				<th {{$forOthers}} rowspan="3">TOTAL DEDUCTIONS</th>

				<th {{$forOthers}} rowspan="3">PhilHealth</th>
				<th {{$forOthers}} rowspan="3">RETIREMENT &amp; LIFE INSURANCE PREMIUMS</th>
				<th {{$forOthers}} rowspan="3">PAG-IBIG HDMF CONT.</th>
				<th {{$forOthers}} rowspan="3">STATE INS.</th>
			</tr>
			<tr>
				<th {{$forOthers}} rowspan="2">LAUNDRY</th>
				<th {{$forOthers}} colspan="3">SUBSISTENCE</th>
		
				{{-- <th rowspan="2">HDMF CONT.</th>
				<th rowspan="2">MPL</th>
				<th rowspan="2">HOUSING LOAN</th> --}}

				@foreach(Pagibig::Get_All_Sub() as $key => $value)
				<th {{$forOthers}} rowspan="2">{{$value->description}}</th>
				@endforeach

				{{-- <th rowspan="2">RETIREMENT &amp; LIFE INSURANCE PREMIUMS</th>
				<th rowspan="2">EDU. ASSISTANCE</th>
				<th rowspan="2">CEAP</th>
				<th rowspan="2">EMERGENCY LOAN</th>
				<th rowspan="2">COMBO LOAN</th>
				<th rowspan="2">POLICY LOAN REG</th>
				<th rowspan="2">OPTIONAL POLICY LOAN</th>
				<th rowspan="2">OULI PREMIUM</th>
				<th rowspan="2">UMID E-CARD PLUS</th>
				<th rowspan="2">GSIS H/L</th> --}}
				@foreach(SSS::Get_All_Sub() as $key => $value)
				<th {{$forOthers}} rowspan="2">{{$value->description}}</th>
				@endforeach
			</tr>
			<tr {{$forOthers}}>
				<th>LEAVE</th>
				<th>TRAVEL</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody>
			
			@if(count($record) > 0) @for($i=0;$i<count($record);$i++)
			@php
				$row = $record[$i];/* dd($row);*/
				$statIns = ($row->rate <= 10000 ? (Core::getm99One('iraterate')->iraterate / 100) * $row->rate : Core::getm99One('iratemax')->iratemax);
				$date_from = (is_array($inf) ? $inf['date_from'] : $inf->date_from);
				$date_to = (is_array($inf) ? $inf['date_to'] : $inf->date_to);
				$countworkingminusleave = (int)(Core::CountWorkingDays($date_from,$date_to) - $row->leave_amt);
				$totalsubsistence = (DB::table('hr_hazardpay')->where('cc_id', Employee::getOfficeByID($row->empid)->department)->first() != null ? 1500 - round(( ($row->leave_amt + $row->obcount) / $countworkingminusleave) * 1500,2) : 0);
				$rate_computed_absences = json_decode($row->rate_computed_absences);
				// $totalsubsistence = 1500 - round(( ($row->leave_amt + $row->obcount) / $countworkingminusleave ) * 1500,2);
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
							// $hazard_duty_pay += $amt;
							$hazard_duty_pay = ($row->rate >= 9000 ? ($row->rate * .25) : ($row->rate * .01));
							// dd($hazard_duty_pay);
						} elseif ($code == "ALLOWNC") {
							if ($id == "A1") {
								$allowance_laundry += $amt;
							}
							$allowance += $amt;
						}
					}
				}
				// $amount_earned = round(($row->rate-($pera))-((($row->rate-$record[$i]->total_deductions)-($pera))/2),2);
				$amount_earned = $row->days_worked * $rate_computed_absences[2];
				$net_amount_received = round((($pera+$amount_earned)-$record[$i]->total_deductions),2);
			@endphp
			<tr>
				<td {{$forImportant}} >{{$row->empid}}</td> {{-- Item No. --}}
				<td {{$forImportant}} >{{strtoupper(($row->empname ?? ''))}}</td> {{-- Name --}}
				<td {{$forImportant}} >{{$no}}</td> {{-- No. --}}
				<td {{$forImportant}} >{{ucfirst(($row->position ?? ''))}}</td> {{-- Position --}}
				<td {{$forImportant}} >{{($row->rate!=0) ? number_format($row->rate,2) : "-"}} <?php $runningRowTotal['rate'] = isset($runningRowTotal['rate']) ? ($row->rate !=0 ? $row->rate : 0) + $runningRowTotal['rate'] : ($row->rate !=0 ? $row->rate : 0) ?></td> {{-- Rate --}}

				<td {{$forOthers}} >{{($rate_computed_absences[1] > 0 ? $rate_computed_absences[1] : '-')}}</td> {{-- No. of Absence w/o Pay --}}
				<td {{$forOthers}} >{{$rate_computed_absences[0] ? number_format($rate_computed_absences[0],2) : "-"}}</td> {{-- Rate Computed Absences --}}

				<td {{$forOthers}} >{{number_format($pera,2)}}</td><?php $runningRowTotal['pera'] = isset($runningRowTotal['pera']) ? $pera + $runningRowTotal['pera'] : $pera ?> {{-- PERA --}}
				<td {{$forOthers}} >{{number_format($hazard_duty_pay,2)}}<?php $runningRowTotal['hazard_duty'] = isset($runningRowTotal['hazard_duty']) ? $hazard_duty_pay + $runningRowTotal['hazard_duty'] : $hazard_duty_pay ?></td> {{-- Hazard Duty Pay --}}
				<td {{$forOthers}} >{{number_format($allowance_laundry,2)}}<?php $runningRowTotal['allowance_laundry'] = isset($runningRowTotal['allowance_laundry']) ? $allowance_laundry + $runningRowTotal['allowance_laundry'] : $allowance_laundry ?></td> {{-- Allowance - Laundry --}}
				<td {{$forOthers}} >{{$row->leave_amt}}</td> {{-- Allowance - Subsistence - Leave , not sure as of Paolo--}}
				<td {{$forOthers}} >{{$row->obcount}}</td> {{-- Allowance - Subsistence - Travel , not sure as of Paolo --}}
				<td {{$forOthers}} >{{$totalsubsistence}}<?php $runningRowTotal['allowance'] = isset($runningRowTotal['allowance']) ? $totalsubsistence + $runningRowTotal['allowance'] : $totalsubsistence ?></td>{{-- Allowance - Subsistence - Total --}}
				<td {{$forOthers}} >{{number_format($amount_earned,2)}}<?php $runningRowTotal['amount_earned'] = isset($runningRowTotal['amount_earned']) ? ($amount_earned) + $runningRowTotal['amount_earned'] : ($amount_earned) ?></td>
				{{-- Amount Earned --}}
				<td {{$forOthers}} >{{number_format($record[$i]->w_tax,2)}}<?php $runningRowTotal['withholding_tax'] = isset($runningRowTotal['withholding_tax']) ? $record[$i]->w_tax + $runningRowTotal['withholding_tax'] : $record[$i]->w_tax ?></td> {{-- Personal Deductions - Withholding Tax --}}
				<td {{$forOthers}} >{{$record[$i]->philhealth_cont_b}}<?php $runningRowTotal['pphilhealth'] = isset($runningRowTotal['pphilhealth']) ? $record[$i]->philhealth_cont_b + $runningRowTotal['pphilhealth'] : $record[$i]->philhealth_cont_b ?></td> {{-- Personal Deductions - Philhealth --}}
				<td {{$forOthers}} >{{$record[$i]->pagibig_cont_b}}<?php $runningRowTotal['pphilhealthhdmf'] = isset($runningRowTotal['pphilhealthhdmf']) ? $record[$i]->pagibig_cont_b + $runningRowTotal['pphilhealthhdmf'] : $record[$i]->pagibig_cont_b ?></td> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}
				
				<?php 
					$pagIbigDeduction = 0;
					$otherDeduction = json_decode($record[$i]->pagibig_cont_a);
					foreach(Pagibig::Get_All_Sub() as $ini){
						if($ini->id != 1){
							if(isset($otherDeduction)){
								foreach($otherDeduction as $od){
									if($ini->id == $od[2]){
										$pagIbigDeduction = $od[3];
									} 
								}
							}

						
							?>
							{{-- Personal Deductions - Pag-ibig - MPL. --}}
							{{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
							<td {{$forOthers}} >{{number_format($pagIbigDeduction,2)}}</td>
							<?php
							$runningRowTotal['pagibigDeductionLoop'][$ini->id] = (isset($runningRowTotal['pagibigDeductionLoop'][$ini->id]) ? $pagIbigDeduction + $runningRowTotal['pagibigDeductionLoop'][$ini->id] : $pagIbigDeduction);
							$pagIbigDeduction = 0;
						}
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
						<td {{$forOthers}} >{{number_format($otherDeductionValue,2)}}</td>
						<?php
						$runningRowTotal['otherDeductionLoop'][$ini] = (isset($runningRowTotal['otherDeductionLoop'][$ini]) ? $otherDeductionValue + $runningRowTotal['otherDeductionLoop'][$ini] : $otherDeductionValue);
						$otherDeductionValue = 0;
					}
				?>

				<td {{$forOthers}} >{{$record[$i]->sss_cont_b}}<?php $runningRowTotal['gsisretirement'] = isset($runningRowTotal['gsisretirement']) ? $record[$i]->sss_cont_b + $runningRowTotal['gsisretirement'] : $record[$i]->sss_cont_b ?></td> {{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}

				<?php 
					$gsisValue = 0;
					$gsis = json_decode($record[$i]->sss_cont_a);
					foreach(SSS::Get_All_Sub() as $ini){
						if($ini->id != 1){
							if(isset($gsis)){
								foreach($gsis as $key => $sss){
									if($ini->id == $sss[2]){
										$gsisValue = $sss[3];
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
							<td {{$forOthers}} >{{number_format($gsisValue,2)}}</td>
							<?php
							$runningRowTotal['gsisLoop'][$ini->id] = (isset($runningRowTotal['gsisLoop'][$ini->id]) ? $gsisValue + $runningRowTotal['gsisLoop'][$ini->id] : $gsisValue);
							$gsisValue = 0;
						}
					}
				?>
				<td {{$forOthers}} >{{number_format($record[$i]->total_deductions,2)}}<?php $runningRowTotal['total_deductions'] = isset($runningRowTotal['total_deductions']) ? $record[$i]->total_deductions + $runningRowTotal['total_deductions'] : $record[$i]->total_deductions ?></td> {{-- Total Deductions --}}
				<td {{$forOthers}} >{{$record[$i]->philhealth_cont_c}}<?php $runningRowTotal['gphilhealth'] = isset($runningRowTotal['gphilhealth']) ? $record[$i]->philhealth_cont_c + $runningRowTotal['gphilhealth'] : $record[$i]->philhealth_cont_c ?></td> {{-- Government Shares - Philhealth --}}
				<td {{$forOthers}} >{{$record[$i]->sss_cont_c}}<?php $runningRowTotal['retirement'] = isset($runningRowTotal['retirement']) ? $record[$i]->sss_cont_c + $runningRowTotal['retirement'] : $record[$i]->sss_cont_c ?></td> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<td {{$forOthers}} >{{$record[$i]->pagibig_cont_c}}<?php $runningRowTotal['pagibighdmf'] = isset($runningRowTotal['pagibighdmf']) ? $record[$i]->pagibig_cont_c + $runningRowTotal['pagibighdmf'] : $record[$i]->pagibig_cont_c ?></td> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<td {{$forOthers}} >{{number_format($statIns,2)}}<?php $runningRowTotal['statin'] = isset($runningRowTotal['statin']) ? $statIns + $runningRowTotal['statin'] : $statIns ?></td> {{-- Government Shares - State Ins. --}}
				<td {{$forOthers}} >{{$i+1}}</td> {{-- No. --}} {{-- running increment --}}
				<td {{$forImportant}} >{{number_format($net_amount_received,2)}}<?php $runningRowTotal['netamount'] = isset($runningRowTotal['netamount']) ? $net_amount_received + $runningRowTotal['netamount'] : $net_amount_received ?></td> {{-- Net Amount Received --}}
				<td {{$forImportant}} >{{number_format($net_amount_received,2)}}<?php $runningRowTotal['amountpaid'] = isset($runningRowTotal['amountpaid']) ? $net_amount_received + $runningRowTotal['amountpaid'] : $net_amount_received ?></td> {{-- Amount Paid --}}
				<td {{$forImportant}} >-</td> {{-- Signature of Payee --}}
			</tr> 
			@endfor @endif
		</tbody>
		{{-- work here for total --}}

		<tfoot>
			{{-- {{dd($runningRowTotal)}} --}}
			<tr>
				<th colspan="4">Total</th>
				<th {{$forImportant}} >{{number_format(($runningRowTotal['rate'] ?? 0),2)}}</th> {{-- Rate --}}
				<th {{$forOthers}} >-</th> {{-- No. of Absence w/o Pay --}}
				<th {{$forOthers}} >-</th> {{-- Rate Computed Absences --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['pera'] ?? 0),2)}}</th> {{-- PERA --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['hazard_duty'] ?? 0),2)}}</th> {{-- Hazard Duty Pay --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['allowance_laundry'] ?? 0),2)}}</th> {{-- Allowance - Laundry --}}
				<th {{$forOthers}} >-</th> {{-- Allowance - Subsistence - Leave --}}
				<th {{$forOthers}} >-</th> {{-- Allowance - Subsistence - Travel --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['allowance'] ?? 0),2)}}</th> {{-- Allowance - Subsistence - Total --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['amount_earned'] ?? 0),2)}}</th> {{-- Amount Earned --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['withholding_tax'] ?? 0),2)}}</th> {{-- Personal Deductions - Withholding Tax --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['pphilhealth'] ?? 0),2)}}</th> {{-- Personal Deductions - Philhealth --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['pphilhealthhdmf'] ?? 0),2)}}</th> {{-- Personal Deductions - Pag-ibig - HDMF Cont. --}}


				{{-- Personal Deductions - Pag-ibig - MPL. --}}
				{{-- Personal Deductions - Pag-ibig - Housing Laon. --}}
				@isset($runningRowTotal['pagibigDeductionLoop'])
				@foreach($runningRowTotal['pagibigDeductionLoop'] as $thisloop)
				<th {{$forOthers}} >{{number_format($thisloop,2)}}</th>
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
				<th {{$forOthers}} >{{number_format($thisloop,2)}}</th>
				@endforeach 
				@endisset

				<th {{$forOthers}} >{{number_format(($runningRowTotal['gsisretirement'] ?? 0),2)}}</th>{{-- Personal Deductions - GSIS - Retirement & Life Insurance Premiums --}}
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
				<th {{$forOthers}} >{{number_format($thisloop,2)}}</th>
				@endforeach 
				@endisset

				<th {{$forOthers}} >{{number_format(($runningRowTotal['total_deductions'] ?? 0),2)}}</th> {{-- Total Deductions --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['gphilhealth'] ?? 0),2)}}</th> {{-- Government Shares - Philhealth --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['retirement'] ?? 0),2)}}</th> {{-- Government Shares - Retirement & Life Insurance Permiums --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['pagibighdmf'] ?? 0),2)}}</th> {{-- Government Shares - Pag-ibig HDMF Cont. --}}
				<th {{$forOthers}} >{{number_format(($runningRowTotal['statin'] ?? 0),2)}}</th> {{-- Government Shares - State Ins. --}}
				<th {{$forOthers}} >-</th> {{-- No. --}}
				<th {{$forImportant}} >{{number_format(($runningRowTotal['netamount'] ?? 0),2)}}</th> {{-- Net Amount Received --}}
				<th {{$forImportant}} >{{number_format(($runningRowTotal['amountpaid'] ?? 0),2)}}</th> {{-- Amount Paid --}}
				<th {{$forImportant}} >-</th> {{-- Signature of Payee --}}
			</tr>
		</tfoot>
	</table>
{{-- @endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection --}}

