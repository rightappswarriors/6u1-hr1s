
@php
	$inf = $data['inf'];
	$record = $data['record'];
	$ppType = ($data['payroll_period'] ?? 1);
	$forOthers = '';
	$forImportant = '';
	switch ($ppType) {
		case '1':
			$forOthers = 'style=display:block-inline!important ';
			$forImportant = 'style=display:block-inline!important ';
			break;
		case '2':
			$forOthers = 'style=display:none!important ';
			$forImportant = 'style=display:block-inline!important ';
			break;
	}
	//initialization of array for hardcoded assigning of value by Syrel
	$statIns = 100;
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
			<tr><th style="text-align: center;" colspan="8">{{strtoupper(($inf->title ?? ''))}}</th></tr>
			<tr><th style="text-align: center;" colspan="8">{{Core::company_name()}}</th></tr>
			<tr><th style="text-align: center;" colspan="8">{{isset($inf->ofc) ? $inf->ofc->cc_desc : ""}}</th></tr>
			<tr><th style="text-align: center;" colspan="8">{{($inf->payroll_period ?? '')}}</th></tr>
			<tr>
				<th>ITEM NO.</th>
				<th>NAME</th>
				<th>No.</th>
				<th>POSITION</th>
				<th>Rate</th>
				<th>NET AMOUNT RECEIVED</th>
				<th>AMOUNT PAID</th>
				<th>Signature of Payee</th>
			</tr>
		</thead>
		<tbody>
			
			@if(count($record) > 0) @for($i=0;$i<count($record);$i++)
			@php
				$row = $record[$i];/* dd($row);*/
				$date_from = (is_array($inf) ? $inf['date_from'] : $inf->date_from);
				$date_to = (is_array($inf) ? $inf['date_to'] : $inf->date_to);
				$countworkingminusleave = (int)(Core::CountWorkingDays(Date('Y-m-d',strtotime('-1 day',strtotime($date_from))),$date_to) - $row->leave_amt);
				$totalsubsistence = 1500 - round(($row->leave_amt / $countworkingminusleave) * 1500,2);
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
				$amount_earned = round(($row->rate-($pera))-((($row->rate-$record[$i]->total_deductions)-($pera))/2),2);
				$net_amount_received = round((($pera+$amount_earned)-$record[$i]->total_deductions),2);
			@endphp
			<tr>
				<td >ACC-{{$row->emp_pay_code}}</td> {{-- Item No. --}}
				<td >{{strtoupper(($row->empname ?? ''))}}</td> {{-- Name --}}
				<td >{{$no}}</td> {{-- No. --}}
				<td >{{ucfirst(($row->position ?? ''))}}</td> {{-- Position --}}
				<td >{{($row->rate!=0) ? number_format($row->rate,2) : "-"}} <?php $runningRowTotal['rate'] = isset($runningRowTotal['rate']) ? ($row->rate !=0 ? $row->rate : 0) + $runningRowTotal['rate'] : ($row->rate !=0 ? $row->rate : 0) ?></td> {{-- Rate --}}
				
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
						<?php
						$runningRowTotal['otherDeductionLoop'][$ini] = (isset($runningRowTotal['otherDeductionLoop'][$ini]) ? $otherDeductionValue + $runningRowTotal['otherDeductionLoop'][$ini] : $otherDeductionValue);
						$otherDeductionValue = 0;
					}
				?>

				<?php 
					$gsisValue = 0;
					$gsis = json_decode($record[$i]->sss_cont_a);
					foreach(SSS::Get_All_Sub() as $ini){
						if($ini->id != 1){
							if(isset($gsis)){
								foreach($gsis as $key => $sss){
									if($ini->id === $sss[0]){
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
							<?php
							$runningRowTotal['gsisLoop'][$ini->id] = (isset($runningRowTotal['gsisLoop'][$ini->id]) ? $gsisValue + $runningRowTotal['gsisLoop'][$ini->id] : $gsisValue);
							$gsisValue = 0;
						}
					}
				?>
				<td >{{number_format($row->rate - $net_amount_received,2)}}<?php $runningRowTotal['netamount'] = isset($runningRowTotal['netamount']) ? $row->rate - $net_amount_received + $runningRowTotal['netamount'] : $row->rate - $net_amount_received ?></td> {{-- Net Amount Received --}}
				<td >{{number_format($net_amount_received,2)}}<?php $runningRowTotal['amountpaid'] = isset($runningRowTotal['amountpaid']) ? $net_amount_received + $runningRowTotal['amountpaid'] : $net_amount_received ?></td> {{-- Amount Paid --}}
				<td >-</td> {{-- Signature of Payee --}}
			</tr> 
			@endfor @endif
		</tbody>
		{{-- work here for total --}}

		<tfoot>
			{{-- {{dd($runningRowTotal)}} --}}
			<tr>
				<th colspan="4">Total</th>
				<th >{{number_format(($runningRowTotal['rate'] ?? 0),2)}}</th> {{-- Rate --}}


				{{-- Personal Deductions - Pag-ibig - MPL. --}}
				{{-- Personal Deductions - Pag-ibig - Housing Laon. --}}

				{{-- Personal Deductions - JGM --}}
				{{-- Personal Deductions - LBP --}}
				{{-- Personal Deductions - CFI --}}
				{{-- Personal Deductions - DCCCO --}}
				{{-- Personal Deductions - PEI 2014 Refund --}}
				{{-- Personal Deductions - Refund for cash advance --}}

				{{-- Personal Deductions - GSIS - Edu. Asstance --}}
				{{-- Personal Deductions - GSIS - CEAP --}}
				{{-- Personal Deductions - GSIS - Emergency Loan --}}
				{{-- Personal Deductions - GSIS - Combo Loan --}}
				{{-- Personal Deductions - GSIS - Policy Loan Reg --}}
				{{-- Personal Deductions - GSIS - Optional Policy Loan --}}
				{{-- Personal Deductions - GSIS - Ouli Permium --}}
				{{-- Personal Deductions - GSIS - UMID E-Card Plus --}}
				 {{-- Personal Deductions - GSIS - GSIS H/L --}}

				<th >{{number_format(($runningRowTotal['netamount'] ?? 0),2)}}</th> {{-- Net Amount Received --}}
				<th >{{number_format(($runningRowTotal['amountpaid'] ?? 0),2)}}</th> {{-- Amount Paid --}}
				<th >-</th> {{-- Signature of Payee --}}
			</tr>
		</tfoot>
	</table>