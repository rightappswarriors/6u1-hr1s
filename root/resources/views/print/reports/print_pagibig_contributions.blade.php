@extends('layouts.print_layout')

@section('head')
	<style>
    body{
      font-size: 11px;
    }
    .border-ltr{
        border-left: 1px solid !important;
        border-top: 1px solid !important;
        border-right: 1px solid !important;
        text-align: center;
    }
    .border-lr{
      border-left: 1px solid !important;
      border-right: 1px solid !important;
      text-align: center;
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
<div class="container">  
	<div class="table-responsive table-borderless">
	    <table class="table mb-0 pb-0" style="width: 99.99%;">
	        <thead>
	            <tr>
	                <th rowspan="3"><img src="{{ asset('images/logos/pagibig.svg.png') }}" alt="" width="200"></th>
	                <td colspan="6" style="font-weight: bold; font-size: 25px;">MEMBERSHIP REGISTRATION/REMITTANCE FORM</td>
	                <td style="font-weight: bold;">HDMF M1-1</td>
	            </tr>
	            <tr>

	                <td style="font-size: 16px;" colspan="2"><i class="fa fa-window-close-o" aria-hidden="true"></i> PRIVATE EMPLOYER</td>
	                <td style="font-size: 16px;"><i class="fa fa-square-o" aria-hidden="true"></i> GOVERNMENT CONTROLLED CORP.</td>
	                <td></td>
	                <td></td>
	                <td style="border-left: 1px solid; border-top: 1px solid; text-align: left;">MONTH</td>
	                <td style="border-top: 1px solid; border-right: 1px solid; text-align: right;">YEAR</td>
	            </tr>
	            <tr>
	                <td style="font-size: 16px;" colspan="2"><i class="fa fa-square-o" aria-hidden="true"></i> LOCAL GOVERNMENT UNIT</td>
	                <td style="font-size: 16px;"><i class="fa fa-square-o" aria-hidden="true"></i> NATIONAL GOVERNMENT AGENCY</td>
	                <td></td>
	                <td></td>
	                @isset($arrRet)
	                @php
	                	$month = date('F');
	                	$year = date('Y');
	                @endphp
		                <td style="border-left: 1px solid; border-bottom: 1px solid;">{{ $month }}</td>
		                <td style="text-align: right; border-right:1px solid; border-bottom: 1px solid;">{{ $year }}</td>
		                <td></td>
	                @endif
	            </tr>
	            <tr>
	                <td colspan="2" style="border-left: 1px solid; border-top: 1px solid; border-right: 1px solid;">NAME OF EMPLOYER</td>
	                <td colspan="2" style="border-left: 1px solid; border-top: 1px solid; border-right: 1px solid; text-align: center;">EMPLOYER SSS NO.</td>
	                <td style="border-top: 1px solid;"></td>
	                <td style="border-top: 1px solid;">AGENCY CODE</td>
	                <td>BRANCH CODE</td>
	                <td style="border-right: 1px solid;">REGION CODE</td>
	            </tr>
	            <tr>
	                <td colspan="2" style="padding-left: 20px; font-weight: bold; border-left: 1px solid; border-right: 1px solid"> ELEGANT CIRCLE INN</td>
	                <td colspan="2" style="border-right: 1px solid; font-weight: bold;">FOR PRIVATE EMPLOYER</td>
	                <td>FOR GOV'T EMPLOYER</td>
	                <td style="border-right: 1px solid;"></td>
	                <td style="border-right: 1px solid;"></td>
	                <td style="border-right: 1px solid;"></td>
	            </tr>
	        </thead>
	        <thead>
	            <tr>
	                <th colspan="2" style="text-align: center; border-top: 1px solid; border-left: 1px solid;  vertical-align: middle;" rowspan="2">TIN DATE OF BIRTH</th>
	                <th class="border-ltr" colspan="3" style="border-bottom: 1px solid;" >NAME OF EMPLOYEES</th>
	        <th style="text-align: center; " colspan="3 " class="border-ltr ">CONTRIBUTIONS</th>
	      </tr>
	      <tr>
	        <th style="font-weight:normal;border-bottom: 1px solid; ">Family Name</th>
	        <th style="font-weight:normal;border-bottom: 1px solid; ">First Name</th>
	        <th style="font-weight:normal;border-bottom: 1px solid; border-right: 1px solid; ">Middle Name</th>
	        <th style="font-weight:normal; border: 1px solid; ">EMPLOYEE</th>
	        <th style="font-weight:normal; border: 1px solid; ">EMPLOYER</th>
	        <th style="font-weight:normal; border: 1px solid; ">TOTAL</th>
	      </tr>
	    </thead>
	    <tbody>
	    @foreach ($arrRet as $arr)	
	      <tr>
	        <td colspan="2 " class="border-ltr ">APPLIED</td>
	        <td>1. {{ ($arr[0]->lastname ?? null) }}</td>
	        <td>{{ ($arr[0]->firstname ?? null) }}</td>
	        <td style="border-right: 1px solid; ">{{ ($arr[0]->mi ?? null) }}</td>
	        @php
	        	$emp = (int)($arr[1][0]->empshare_sc ?? 0);
	        	$emp2 = (int)($arr[1][0]->empshare_ec ?? 0);
	        @endphp
	        <td style="border-right: 1px solid; ">{{ number_format($emp,2, '.', ',') }}</td>
	        <td style="border-right: 1px solid; ">{{ number_format($emp2,2, '.', ',') }}</td>
	        @php
	        	{{ 
	        		$var1 = (int)($arr[1][0]->empshare_sc ?? null);
	        		$var2 = (int)($arr[1][0]->empshare_ec ?? null);
	        		$sum =  $var1 + $var2;
	        		
	        	}}
	        @endphp
	        <td style="border-right: 1px solid; ">{{ number_format($sum,2, '.', ',') }}</td>
	      </tr>
	    @endforeach  
	    </tbody>
	  </table>
	</div>
</div>

<div class="container">  
	<div class="table-responsive table-borderless">
    	<div class="row" style="width: 99.9%;">
    		<div class="col-6">
    			<table class="table mb-0 pb-0" style="width: 99.40%; border: 1px solid;">
					<tbody>
	                    <tr>
	                        <td style="border-left: 1px solid; border-top: 1px solid;">No. of Employees</td>
	                        @if(count($arrRet) > 0)
	                        @php
	                        	$counting = count($arrRet);
	                        @endphp
	                        <td rowspan="2" style="border-top: 1px solid; font-weight: bold; font-size: 25px; vertical-align: middle; text-align: center;">{{ $counting }}</td>
	                        <td style="border-left: 1px solid; border-top: 1px solid;">Total no. of Employees</td>
	                        <td rowspan="2" style="border-top: 1px solid; border-right: 1px solid; font-weight: bold; font-size: 25px; vertical-align: middle; text-align: center;">{{ $counting }}</td>
	                        @endif
	                    </tr>
	                    <tr>
	                        <td style="border-left: 1px solid;">on this page</td>
	                        <td style="border-left: 1px solid;">if last page</td>
	                    </tr>
	                    <tr>
	                        <td colspan="4" style="border:1px solid;text-align: center; font-weight: bold;">FOR Pag-IBIG USE ONLY</td>
	                    </tr>
	                    <tr>
	                        <td style="border-right: 1px solid; border-left: 1px solid; width: 30%;">PER VALIDATION NO.</td>
	                        <td colspan="2" style="border-right: 1px solid; text-align: left">DATE</td>
	                        <td style="border-right: 1px solid; width: 25%;">AMOUNT</td>
	                    </tr>
	                    <tr>
	                        <td class="border-lr" style="border-bottom: 1px solid;"></td>
	                        <td colspan="2" class="border-lr" style="border-bottom: 1px solid;"></td>
	                        <td style="border-left: 1px solid; border-right: 1px solid; border-bottom: 1px solid;">&#8369;</td>
	                    </tr>
	                    <tr>
	                        <td colspan="2" style="border: 1px solid;">COLLECTING BANK</td>
	                        <td colspan="2" style="border: 1px solid;">REMARKS</td>
	                    </tr>
	                    <tr>
	                        <td colspan="1" style="border-left: 1px solid; border-right: 1px solid;">TICKET DATE</td>
	                        <td colspan="1" style="border-left: 1px solid; border-right: 1px solid;">RECONCILED BY</td>
	                        <td colspan="2" style="border-left: 1px solid; border-right: 1px solid;">CHECKED BY</td>
	                    </tr>
	                    <tr>
	                        <td style="border-bottom: 1px solid; border-left: 1px solid;"></td>
	                        <td style="border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid;"></td>
	                        <td colspan="2" style="border-bottom: 1px solid; border-right: 1px solid;"></td>
	                    </tr>
	                </tbody>
    			</table>
    		</div>
    		<div class="col-6">
    			<table class="table mb-0 pb-0" style="width: 101.5%; border: 1px solid;">
					<tr>
						@if(count($arrRet) > 0)
						@php
							$sum = 0;
							$sum2 = 0;
							$sum3 = 0;
						@endphp
						@foreach($arrRet as $arr)
							@php
								
								$var1 = (int)($arr[1][0]->empshare_sc ?? null);
				        		$sum +=  $var1;

				        		
								$var2 = (int)($arr[1][0]->empshare_ec ?? null);
				        		$sum2 +=  $var2;

				        		$add = $sum + $sum2;
							@endphp
							@php
								$sum3 = $sum + $sum2;	
							@endphp
						@endforeach	
						    <td style="border-left: 1px solid; border-top: 1px solid; width: 38%">TOTAL No. FOR THIS PAGE <span style="visibility: hidden;">asdasd</span></td>
						    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; height: 120%;">&#8369; {{ number_format($sum, 2, '.', ',') }}0</td>
						    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; !important">&#8369; {{ number_format($sum2, 2, '.', ',') }}</td>
						    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; !important">&#8369; {{ number_format($sum3, 2, '.', ',') }}</td>
					    @else
					    <td style="border-left: 1px solid; border-top: 1px solid; width: 38%">TOTAL No. FOR THIS PAGE <span style="visibility: hidden;">asdasd</span></td>
					    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; height: 120%;">&#8369; NO DATA</td>
					    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; !important">&#8369; NO DATA</td>
					    <td rowspan="2" style="border: 1px solid; vertical-align: middle; text-align: center; !important">&#8369; NO DATA</td>
					    @endif
					</tr>
					<tr>
					    <td style="border-left: 1px solid;">THIS PAGE</td>
					</tr>
					<tr>
					    @if(count($arrRet) > 0)
						@php
							$sum = 0;
							$sum2 = 0;
							$sum3 = 0;
						@endphp
						@foreach($arrRet as $arr)
							@php
								
								$var1 = (int)($arr[1][0]->empshare_sc ?? null);
				        		$sum +=  $var1;

				        		
								$var2 = (int)($arr[1][0]->empshare_ec ?? null);
				        		$sum2 +=  $var2;

				        		$add = $sum + $sum2;
							@endphp
							@php
								$sum3 = $sum + $sum2;	
							@endphp
						@endforeach	
						    <td style="border-left: 1px solid; border-top: 1px solid;">GRAND TOTAL</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; {{ number_format($sum, 2, '.', ',') }}</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; {{ number_format($sum2, 2, '.', ',') }}</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; {{ number_format($sum3, 2, '.', ',') }}</td>
					    @else
						    <td style="border-left: 1px solid; border-top: 1px solid;">GRAND TOTAL</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; NO DATA</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; NO DATA</td>
						    <td rowspan="2" style="border: 1px solid;vertical-align: middle; text-align: center;">&#8369; NO DATA</td>
					    @endif
					</tr>
					<tr>
					    <td style="border-left: 1px solid; border-bottom: 1px solid;">(if last page)</td>
					</tr>
					<tr>
					    <td colspan="4" style="text-align: center; font-weight: bold; border: 1px solid;">CERTIFIED CORRECT BY</td>
					</tr>
					<tr>
					    <td colspan="3" style="border:1px solid; !important"></td>
					    <td style="border:1px solid; !important">DATE</td>
					</tr>
					<tr>
					    <td colspan="2" style="border-left:1px solid; border-right:1px solid; !important"></td>
					    <td style="border-left:1px solid; border-right:1px solid; !important">PAGE NO <span style="visibility: hidden;">asdasdasdasdasdas</span></td>
					    <td style="border-left:1px solid; border-right:1px solid; !important">NO. OF PAGES</td>
					</tr>
					<tr>
					    <td style="border-left:1px solid; border-bottom: 1px solid; !important"></td>
					    <td style="border-bottom: 1px solid;"></td>
					    <td style="border-left:1px solid; border-right:1px solid; border-bottom: 1px solid; !important"></td>
					    <td style="border-left:1px solid; border-right:1px solid; border-bottom: 1px solid; !important"></td>
					</tr>
    			</table>
    		</div>
    	</div>    
	</div>
</div>



{{-- <div class="container">
	<div class="row">
	    <div class="col-sm-4">
	        <div class="table-responsive table-borderless">
	            <table class="table" style="width: 99.99%;">
	                
	            </table>
	        </div>
	    </div>
	</div>
</div>  	 --}}
    {{--  --}}
    {{-- <div class="col-sm-6">
      <div class="table-responsive table-borderless">
        <table class="table" style="width: 50%;">
          
        </table>
      </div>
    </div> --}}
  
@endsection

@section('script-body')
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection