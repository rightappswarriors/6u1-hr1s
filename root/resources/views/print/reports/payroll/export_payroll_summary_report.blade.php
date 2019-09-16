{{-- @extends('layouts.print_layout')

@section('body')  --}}
	<table>
		<thead>
			<tr><th colspan="44">GENERAL PAYROLL</th></tr>
			<tr><th colspan="44">{{Core::company_name()}}</th></tr>
			<tr><th colspan="44">{{$data->ofc}}</th></tr>
			<tr><th colspan="44">{{date('F j, Y', strtotime($data->pp->from))}} - {{date('F j, Y', strtotime($data->pp->to))}}</th></tr>
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
				<th rowspan="2">EDU. ASSIST- ANCE</th>
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
			@for($i=0;$i<count($data->rsr);$i++)
			@php
				$row = $data->rsr[$i];
				$no = $i+1;

				$emp = Employee::GetEmployee($row->empid);
			@endphp
			<tr>
				<td>ACC-{{$row->item_no}}</td>
				<td>{{Employee::Name($row->empid, 'complete')}}</td>
				<td>{{$no}}</td>
				<td>{{JobTitle::Get_JobTitle($emp->positions)}}</td>
				<td>{{($row->rate!=0) ? $row->rate : "-"}}</td>
				<td>{{($row->absences_wo_pay!=0) ? $row->absences_wo_pay : "-"}}</td>
				<td>{{($row->computed_rate!=0) ? $row->computed_rate : "-"}}</td>
				<td>{{($row->pera!=0) ? $row->pera : "-"}}</td>
				<td>{{($row->hazard_duty_pay!=0) ? $row->hazard_duty_pay : "-"}}</td>
				<td>{{($row->alw_laundry!=0) ? $row->alw_laundry : "-"}}</td>
				<td>{{($row->alw_sub_leave!=0) ? $row->alw_sub_leave : "-"}}</td>
				<td>{{($row->alw_sub_travel!=0) ? $row->alw_sub_travel : "-"}}</td>
				<td>{{($row->alw_sub_total!=0) ? $row->alw_sub_total : "-"}}</td>
				<td>{{($row->amt_earned!=0) ? $row->amt_earned : "-"}}</td>
				<td>{{($row->pd_w_tax!=0) ? $row->pd_w_tax : "-"}}</td>
				<td>{{($row->pd_philhealth!=0) ? $row->pd_philhealth : "-"}}</td>
				<td>{{($row->pd_pagibig_a!=0) ? $row->pd_pagibig_a : "-"}}</td>
				<td>{{($row->pd_pagibig_b!=0) ? $row->pd_pagibig_b : "-"}}</td>
				<td>{{($row->pd_pagibig_c!=0) ? $row->pd_pagibig_c : "-"}}</td>
				@php
					$pd_content = [$row->pd_jgm, $row->pd_lbp, $row->pd_lbp, $row->pd_dccco, $row->pd_pei_refund, $row->pd_ca_refund];
				@endphp
				@if(count($pd_content) > 0)
					@for($j = 0; $j < count($pd_content); $j++)
						<td>{{($pd_content[$j]!=0) ? $pd_content[$j] : "-"}}</td>
					@endfor
				@endif
				<td>{{($row->pd_gsis_a!=0) ? $row->pd_gsis_a : "-"}}</td>
				<td>{{($row->pd_gsis_b!=0) ? $row->pd_gsis_b : "-"}}</td>
				<td>{{($row->pd_gsis_c!=0) ? $row->pd_gsis_c : "-"}}</td>
				<td>{{($row->pd_gsis_d!=0) ? $row->pd_gsis_d : "-"}}</td>
				<td>{{($row->pd_gsis_e!=0) ? $row->pd_gsis_e : "-"}}</td>
				<td>{{($row->pd_gsis_f!=0) ? $row->pd_gsis_f : "-"}}</td>
				<td>{{($row->pd_gsis_g!=0) ? $row->pd_gsis_g : "-"}}</td>
				<td>{{($row->pd_gsis_h!=0) ? $row->pd_gsis_h : "-"}}</td>
				<td>{{($row->pd_gsis_i!=0) ? $row->pd_gsis_i : "-"}}</td>
				<td>{{($row->pd_gsis_j!=0) ? $row->pd_gsis_j : "-"}}</td>
				<td>{{($row->pd_total_deductions!=0) ? $row->pd_total_deductions : "-"}}</td>
				<td>{{($row->gs_philhealth!=0) ? $row->gs_philhealth : "-"}}</td>
				<td>{{($row->gs_life_ins!=0) ? $row->gs_life_ins : "-"}}</td>
				<td>{{($row->gs_pagibig_hdmf!=0) ? $row->gs_pagibig_hdmf : "-"}}</td>
				<td>{{($row->gs_state_ins!=0) ? $row->gs_state_ins : "-"}}</td>
				<td>{{$no}}</td>
				<td>{{($row->net_amt!=0) ? $row->net_amt : "-"}}</td>
				<td>{{($row->amt_paid!=0) ? $row->amt_paid : "-"}}</td>
			</tr>
			@endfor
		</tbody>
	</table>
{{-- @endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection --}}

