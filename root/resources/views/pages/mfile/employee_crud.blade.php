@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-users"></i> Employee ({{ucfirst($mode)}}) <button type="button" class="btn btn-danger" onclick="location.href='{{ url('master-file/employee') }}';">Back</button>
		</div>
		<div class="card-body">
			<form method="post" action="{{$url}}" id="frm-pp" data="#" data-parsley-validate novalidate>
				<div class="border-bottom mb-3">
					<div class="mb-2 text-right">
						<button type="button" class="btn btn-primary" name="nxBTN" onclick="ChangeTab($('#ADD_TAB'), event)">Next</button>
						<button type="submit" form="frm-pp" class="btn btn-success" {{-- onclick="checkCurrentTab(4)" --}} style="display:none" name="svBTN">Save</button>
					</div>
				</div>
				@csrf
				<ul class="nav nav-tabs" id="myTab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" style="font-size: 20px" id="GEN_TAB" href="#GEN_TAB_BDY" data-next="ADD_TAB" data-num="0" role="tab" aria-selected="true" onclick="ChangeTab(this, event)">General Info <span id="gen_blink" class="exclamPoint" style="display:none">!</span></a>
				  </li>
				  <li class="nav-item ">
				    <a class="nav-link" style="font-size: 20px" id="ADD_TAB" {{-- data-toggle="tab" --}} href="#ADD_TAB_BDY" data-next="PERBAC_TAB" data-num="1" role="tab" aria-selected="false" onclick="ChangeTab(this, event)">Additional Info <span id="add_blink" class="exclamPoint" style="display:none">!</span></a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" style="font-size: 20px" id="PERBAC_TAB" {{-- data-toggle="tab" --}} href="#PERBAC_TAB_BDY" data-next="CONT_TAB" data-num="2" role="tab" aria-selected="false" onclick="ChangeTab(this, event)">Personnal Background <span id="perbac_blink" class="exclamPoint" style="display:none">!</span></a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" style="font-size: 20px" id="CONT_TAB" {{-- data-toggle="tab" --}} href="#CONT_TAB_BDY" data-next="EDU_TAB" data-num="3" role="tab" aria-selected="false" onclick="ChangeTab(this, event)">Contact Info <span id="cont_blink" class="exclamPoint" style="display:none">!</span></a>
				  </li>
				  <li class="nav-item ">
				    <a class="nav-link" style="font-size: 20px" id="EDU_TAB" {{-- data-toggle="tab" --}} href="#EDU_TAB_BDY" data-next="GEN_TAB" data-num="4" role="tab" aria-selected="false" onclick="ChangeTab(this, event)">Educational Background <span id="edu_blink" class="exclamPoint" style="display:none">!</span></a>
				  </li>
				</ul>
				<div class="tab-content" id="myTabContent">
					{{-- GENNERAL INFORMATION TAB --}}
					<div class="tab-pane fade active show" id="GEN_TAB_BDY" role="tabpanel" aria-labelledby="GEN_TAB">
						<br>
						<div class="row">
					  		<div class="col-sm-6">
					  			<div class="form-group row">
							      <label class="col-sm-4 col-form-label">Head Of Facility? <strong style="color:red;">*</strong></label>
							      <div class="col-sm-2">
							      	<input name="isHeadOfFaci" {{($mode == "edit" && $MYDATA->isheadoffacility) ? "checked" : ""}} class="form-control exclusive-check" type="checkbox">
							      </div>
							    </div>
						  		<div class="form-group row">
							      <label class="col-sm-4 col-form-label">Employee ID <strong style="color:red;">*</strong></label>
							      <div class="col-sm-8">
							      	<input type="text" name="txt_id" style="text-transform: uppercase;" class="form-control T0r" maxlength="8" placeholder="Employee Number" value="@isset($MYDATA){{$MYDATA->empid}}@endisset" {{($mode == "edit") ? "readonly" : ""}} required>
							      </div>
							    </div>
							    <!-- Account Number -->
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Account Number {{-- <strong style="color:red">*</strong> --}}</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control T0 T0r" name="txt_accountnumber" placeholder="ACCOUNT NUMBER" value="@isset($MYDATA){{$MYDATA->accountnumber}}@endisset">
							      </div>
							    </div>
							    <!-- Account Number -->
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Last Name <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control T0 T0r" name="txt_lname" placeholder="Last Name" value="@isset($MYDATA){{$MYDATA->lastname}}@endisset" required>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">First Name <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control T0 T0r" name="txt_fname" placeholder="First Name" value="@isset($MYDATA){{$MYDATA->firstname}}@endisset" required>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Middle Initial</label>
							      <div class="col-sm-2">
							      	<input type="text" maxlength="1" style="text-transform: uppercase;" class="form-control T0" name="txt_mname" placeholder="M.I." value="@isset($MYDATA){{substr($MYDATA->mi, 0, 1)}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Office <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<select class="form-control T0 T0r" onchange="" name="txt_dept" required>
							      	@isset($office)
							      		<option value="">Select Office...</option>
							      		@foreach ($office as $d)
							      			<option value="{{$d->cc_id}}" @isset($MYDATA){{($MYDATA->department == $d->cc_id) ? "selected" : ""}}@endisset>{{$d->cc_desc}}</option>
							      		@endforeach
							      	@else
							      		<option value="">No Office registered..</option>
							      	@endisset
							      	</select>
							      </div>
							    </div>
							    {{-- <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Section <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<select class="form-control T0 T0r" name="txt_deptsec" id="" required>
							      	</select>
							      </div>
							    </div> --}}
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Job Title<strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<select class="form-control T0 T0r" name="txt_jobdesc" required>
							      		@isset($position)
							      			<option value="">Select Position..</option>
							      			@foreach ($position as $d)
							      				<option value="{{$d->jt_cn}}" @isset($MYDATA){{($MYDATA->positions == $d->jt_cn) ? "selected" : ""}}@endisset>{{$d->jtitle_name}}</option>
							      			@endforeach
							      		@else
							      			<option value="">No Position registered..</option>
							      		@endisset
							      	</select>
							      </div>
							    </div>
						  	</div>
						  	<div class="col-sm-6">
						  		{{-- <div class="container">
								    <h1>jQuery Image Upload
								        <small>with preview</small>
								    </h1>
								    <div class="avatar-upload">
								        <div class="avatar-edit">
								            <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
								            <label for="imageUpload"></label>
								        </div>
								        <div class="avatar-preview">
								            <div id="imagePreview" style="background-image: url(http://i.pravatar.cc/500?img=7);">
								            </div>
								        </div>
								    </div>
								</div> --}}
						  	</div>
					  	</div>
					</div>
					{{-- GENNERAL INFORMATION TAB --}}
					{{-- ADDITIONAL INFORMATION TAB --}}
					<div class="tab-pane fade" id="ADD_TAB_BDY" role="tabpanel" aria-labelledby="ADD_TAB">
						<br>
					  	<div class="row">
					  		<div class="col-sm-7">
						  		<div class="row">
						  			<div class="col">
						  				<div class="form-group row">
									      <label class="col-sm-4 col-form-label form-check-label">Date Hired<strong style="color:red">*</strong></label>
									      <div class="col-sm-8">
									      	<input type="text" class="form-control T0r" name="txt_hired" placeholder="mm-dd-yyyy"  required="" value="@isset($MYDATA){{$MYDATA->date_hired}} @else {{date('m-d-Y')}} @endisset">
									      </div>
									    </div>
									    <div class="form-group row">
									      <div class="col-sm-6 form-check">
									      	<input class="" type="checkbox" name="txt_con_chk" onclick="ToggleChkBx('txt_con_chk', 'txt_con_dt')" @isset($MYDATA) @if($MYDATA->contractual_date != null) checked="checked" @endif @endisset>
									      	<small>Contractual Date</small>
									      </div>
									      <div class="col-sm-6">
									      	<input type="text" class="form-control" placeholder="mm-dd-yyyy" name="txt_con_dt" @isset($MYDATA) @if($MYDATA->contractual_date != null) value="{{$MYDATA->contractual_date}}" @else disabled=""  @endif @endisset>
									      </div>
									    </div>
									    <div class="form-group row">
									    	<div class="col-sm-6 form-check">
										      	<input class="" type="checkbox" name="txt_prob_chk" onclick="ToggleChkBx('txt_prob_chk', 'txt_prob_dt')" @isset($MYDATA) @if($MYDATA->prohibition_date != null) checked="checked" @endif @endisset>
										      	<small>Probitionary Date</small>
										    </div>
										    <div class="col-sm-6">
										     	<input type="text" class="form-control" placeholder="mm-dd-yyyy" name="txt_prob_dt" @isset($MYDATA) @if($MYDATA->prohibition_date != null) value="{{$MYDATA->prohibition_date}}" @else disabled=""  @endif @endisset>
										    </div>
									    </div>
									    <div class="form-group row">
									      <div class="col-sm-6 form-check">
									      	<input class="" type="checkbox" name="txt_reg_chk" onclick="ToggleChkBx('txt_reg_chk', 'txt_reg_dt')" @isset($MYDATA) @if($MYDATA->date_regular != null) checked="checked" @endif @endisset>
									      	<small>Date Regularized</small>
									      </div>
									      <div class="col-sm-6">
									      	<input type="text" class="form-control" placeholder="mm-dd-yyyy" name="txt_reg_dt" @isset($MYDATA) @if($MYDATA->date_regular != null) value="{{$MYDATA->date_regular}}" @else disabled=""  @endif @endisset>
									      </div>
									    </div>
						  			</div>
						  			<div class="col">
						  				<div class="form-group row">
									      <div class="col-sm-6 form-check">
									      	<input class="" type="checkbox" name="txt_resign_chk" onclick="ToggleChkBx('txt_resign_chk', 'txt_resign_dt')" @isset($MYDATA) @if($MYDATA->date_resigned != null) checked="checked" @endif @endisset>
									      	<small>Date Resigned</small>
									      </div>
									      <div class="col-sm-6">
									      	<input type="text" class="form-control" placeholder="mm-dd-yyyy" name="txt_resign_dt" @isset($MYDATA) @if($MYDATA->date_resigned != null) value="{{$MYDATA->date_resigned}}" @else disabled=""  @endif @endisset>
									      </div>
									    </div>
									    <div class="form-group row">
									      <div class="col-sm-6 form-check">
									      	<input class="" type="checkbox" name="txt_termi_chk" onclick="ToggleChkBx('txt_termi_chk', 'txt_termi_dt')" @isset($MYDATA) @if($MYDATA->date_terminated != null) checked="checked" @endif @endisset>
									      	<small>Date Terminated</small>
									      </div>
									      <div class="col-sm-6">
									      	<input type="text" class="form-control" placeholder="mm-dd-yyyy" name="txt_termi_dt" @isset($MYDATA) @if($MYDATA->date_terminated != null) value="{{$MYDATA->date_terminated}}" @else disabled=""  @endif @endisset>
									      </div>
									    </div>
						  			</div>
						  		</div>
						  		<div class="form-group row">
							      <label class="col-sm-3 col-form-label">Employement Status <strong style="color:red">*</strong></label>
							      <div class="col-sm-9">
							      	<select class="form-control T0r" name="txt_emp_stat" required="">
							      		@isset($emp_status)
											<option value="">Select Employement Status...</option>
											@foreach ($emp_status as $d)
												{{-- @if($d->type=="es") --}}
												<option value="{{$d->status_id}}" @isset($MYDATA){{($MYDATA->empstatus == $d->status_id) ? "selected" : ""}}@endisset>{{$d->description}}</option>
												{{-- @endif --}}
											@endforeach
										@else
											<option value="">No Employement Status registered...</option>
							      		@endisset
							      	</select>
							      </div>
							    </div>
							    {{-- <div class="form-group row">
							      <label class="col-sm-3 col-form-label">Employement Type <strong style="color:red">*</strong></label>
							      <div class="col-sm-9">
							      	<select class="form-control T0r" name="txt_emp_type" required="">
							      		@isset($emp_status)
											<option value="">Select Employement Type...</option>
											@foreach ($emp_status as $d)
												@if($d->type=="et")
												<option value="{{$d->status_id}}" @isset($MYDATA){{($MYDATA->emptype == $d->status_id) ? "selected" : ""}}@endisset>{{$d->description}}</option>
												@endif
											@endforeach
										@else
											<option value="">No Employement Type registered...</option>
							      		@endisset
							      	</select>
							      </div>
							    </div> --}}
							    {{-- <div class="row">
							    	<div class="col">
							    		<div class="form-group row">
									      <small class="col-sm-4">Contract Days</small>
									      <div class="col-sm-8">
									      	<input type="number" class="form-control" name="txt_contract">
									      </div>
									    </div>
							    	</div>
							    	<div class="col">&nbsp;</div>
							    </div> --}}
							    <div class="form-group row">
							      <label class="col-sm-3 col-form-label">PRC Number</label>
							      <div class="col-sm-9">
							      	<input type="text" class="form-control" name="txt_prc" placeholder="PRC Number" value="@isset($MYDATA){{$MYDATA->prc}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-3 col-form-label">CTC Number</label>
							      <div class="col-sm-9">
							      	<input type="text" class="form-control" name="txt_ctc" placeholder="CTC Number" value="@isset($MYDATA){{$MYDATA->ctc}}@endisset">
							      </div>
							    </div>
							    <div class="row">
							    	<div class="col-sm-8">
							    		<div class="form-group row">
							    			<label class="col-sm-4 col-form-label">Rate Type <strong style="color:red">*</strong></label>
										      <div class="col-sm-8">
										      	<select name="txt_rate_type" class="form-control T0r" required="">
										      		@isset($rate)
										      			<option value="" @isset($MYDATA){{'disabled'}} @endisset>Select Rate Type..</option>
										      			@foreach ($rate as $r)
										      				<option value="{{$r->ratecode}}" @isset($MYDATA){{($MYDATA->rate_type == $r->ratecode) ? "selected" : ""}}@endisset>{{$r->description}}</option>
										      			@endforeach
										      		@else
														<option value="">No Rate Type registered..</option>
										      		@endisset
										      	</select>

										      	<!-- <input type="text" placeholder="MONTHLY" class="form-control" name="txt_rate_typ" readonly=""> -->
										      </div>
							    		</div>
							    	</div>
							    	{{-- <div class="col-sm-4">
							    		<div class="form-group row">
									      <div class="col-sm-12 form-check">
									      	<input type="checkbox"  name="txt_fx_rate" value="true"  aria-label="...">
									      	<label class="col-form-label">Fixed Rate</label>
									      </div>
									    </div>
							    	</div> --}}
							    </div>
							    <div class="row">
							    	<div class="col-sm-8">
							    		<div class="form-group row">
							    			<label class="col-sm-4 col-form-label">Pay Rate <strong style="color:red">*</strong></label>
										      <div class="col-sm-8">
										      	<input type="text" class="form-control T0r" name="txt_py_rate" value="@isset($MYDATA){{$MYDATA->pay_rate}}@endisset" required="">
										      </div>
							    		</div>
							    	</div>
							    	<div class="col-sm-4">&nbsp;</div>
							    </div>
							    <div class="row">
							    	<div class="col-sm-8">
							    		<div class="form-group row">
							    			<label class="col-sm-4 col-form-label">Biometric ID</label>
										      <div class="col-sm-8">
										      	<input type="text" class="form-control" name="txt_biometric" value="@isset($MYDATA){{$MYDATA->biometric}}@endisset">
										      </div>
										      <div class="offset-4 col-sm-8" id="bio_message">
							    					<p style="color:red;">This biometric id is taken</p>
							    			  </div>
							    		</div>
							    	</div>
							    	<div class="col-sm-4">&nbsp;</div>
							    </div>
						  	</div>
						  	<div class="col-sm-5">
						  		<div class="form-group row">
							      <label class="col-sm-4 col-form-label">GSIS #</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control" name="txt_sss" value="@isset($MYDATA){{$MYDATA->sss}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Pag-ibig #</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control" name="txt_pagibig" value="@isset($MYDATA){{$MYDATA->pagibig}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Philhealth #</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control" name="txt_philhealth" value="@isset($MYDATA){{$MYDATA->philhealth}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Payrol acct. #</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control" name="txt_payrol" value="@isset($MYDATA){{$MYDATA->payroll_account}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">TIN</label>
							      <div class="col-sm-8">
							      	<input type="text" class="form-control" name="txt_tin" value="@isset($MYDATA){{$MYDATA->tin}}@endisset">
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Tax Bracket <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<select class="form-control T0r" name="txt_tax_brac" required="">
							      		@isset($tax)
							      			<option value="">Select Tax Bracket..</option>
							      			@foreach ($tax as $r)
							      				<option value="{{$r->code}}" @isset($MYDATA){{($MYDATA->tax_bracket == $r->code) ? "selected" : ""}}@endisset>{{$r->description}}</option>
							      			@endforeach
							      		@else
											<option value="">No Tax Bracket registered..</option>
							      		@endisset
							      	</select>
							      </div>
							    </div>
							    {{-- <div class="form-group row">
							      <label class="col-sm-4 col-form-label">GSIS Bracket <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
							      	<select class="form-control T0r" name="txt_ss_brac">
							      		@isset($sss)
							      			<option value="">Select GSIS Bracket..</option>
							      			@foreach ($sss as $r)
							      				<option value="{{$r->code}}">Php {{number_format($r->s_credit, 2, ".", ", ")}}</option>
							      			@endforeach
							      		@else
											<option value="">No Tax Bracket registered..</option>
							      		@endisset
							      	</select>
							      </div>
							    </div> --}}
							    {{-- <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Fix Schedule</label>
							      <div class="col-sm-8">
							      	<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" onclick="FixSched()" name="txt_fx_sched" id="inlineRadio1" value="Yes">
										<label class="form-check-label" for="inlineRadio1">Yes</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" onclick="FixSched()" name="txt_fx_sched" id="inlineRadio2" value="No" checked="checked">
										<label class="form-check-label" for="inlineRadio2">No</label>
									</div>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Shift Sched <strong style="color:red">*</strong></label>
							      <div class="col-sm-8">
										<div class="row">
											<div class="col-sm-5">
												<input type="time" class="form-control isFix mustReq" name="txt_sft_1" disabled="">
											</div>
											<div class="col-sm-2"><center>-</center></div>
											<div class="col-sm-5">
												<input type="time" class="form-control isFix mustReq" name="txt_sft_2" disabled="">
											</div>
										</div>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Shift Sched (SAT)</label>
							      <div class="col-sm-8">
										<div class="row">
											<div class="col-sm-5">
												<input type="time" class="form-control isFix" name="txt_sat_sft_1" disabled="">
											</div>
											<div class="col-sm-2"><center>-</center></div>
											<div class="col-sm-5">
												<input type="time" class="form-control isFix" name="txt_sat_sft_2" disabled="">
											</div>
										</div>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Day Off 1</label>
							      <div class="col-sm-8">
							      	<select class="form-control isFix" name="txt_day_off_1" disabled="">
							      		@isset($day)
											<option value="">Select Day..</option>
											@foreach ($day as $d)
												<option value="{{$d->day}}">{{$d->dayname}}</option>
											@endforeach
							      		@else
							      			<option value="">No Day registered..</option>
							      		@endisset
							      	</select>
							      </div>
							    </div>
							    <div class="form-group row">
							      <label class="col-sm-4 col-form-label">Day Off 2</label>
							      <div class="col-sm-8">
							      	<select class="form-control isFix" name="txt_day_off_2" disabled="">
							      		@isset($day)
											<option value="">Select Day..</option>
											@foreach ($day as $d)
												<option value="{{$d->day}}">{{$d->dayname}}</option>
											@endforeach
							      		@else
							      			<option value="">No Day registered..</option>
							      		@endisset
							      	</select>
							      </div>
							    </div> --}}
						  	</div>
					  	</div>
					</div>
					{{-- ADDITIONAL INFORMATION TAB --}}
					{{-- PERSONNAL BACKGROUND TAB --}}
					<div class="tab-pane fade" id="PERBAC_TAB_BDY" role="tabpanel" aria-labelledby="PERBAC_TAB">
						<br>
						<div class="row">
					  		<div class="col">
						  		<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Gender <strong style="color:red">*</strong></label>
							      	<div class="col-sm-8">
							      		<select class="form-control T0r" name="txt_gen" required="">
							      			<option value="">Select Gender..</option>
							      			<option value="Male">Male</option>
							      			<option value="Female">Female</option>
							      		</select>
							      		@isset($MYDATA)
											<script>$('select[name="txt_gen"]').val('{{$MYDATA->sex}}').trigger('change');</script>
								      	@endisset
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Date of Birth <strong style="color:red">*</strong></label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control navbar-expand-md T0r" placeholder="mm-dd-yyyy" name="txt_dt_birth" required="" value="@isset($MYDATA){{$MYDATA->birth}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Civil Status <strong style="color:red">*</strong></label>
							      	<div class="col-sm-8">
							      		<select class="form-control T0r" name="txt_civ_stat" required="">
							      			@isset ($civil_stat)
							      				<option value="">Select Civil Status..</option>
							      				@foreach ($civil_stat as $c)
							      					<option value="{{$c->code}}">{{$c->description}}</option>
							      				@endforeach
							      			@else
							      				<option value="">No Civil Status registered..</option>
							      			@endisset
							      		</select>
							      		@isset($MYDATA)
											<script>$('select[name="txt_civ_stat"]').val('{{$MYDATA->civil_status}}').trigger('change');</script>
								      	@endisset
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Religion</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_reli" class="form-control" value="@isset($MYDATA){{$MYDATA->religion}}@endisset">
							      	</div>
							    </div>
							    <br>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Height (cm)</label>
							      	<div class="col-sm-3">
							      		<input type="text" name="txt_height" class="form-control" value="@isset($MYDATA){{$MYDATA->height}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Weight (kg)</label>
							      	<div class="col-sm-3">
							      		<input type="text" name="txt_weight" class="form-control" value="@isset($MYDATA){{$MYDATA->weight}}@endisset">
							      	</div>
							    </div>
						  	</div>
						  	<div class="col">
						  		<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Father's Name</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_fath_name" class="form-control" value="@isset($MYDATA){{$MYDATA->father}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Address</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_fath_add" class="form-control" value="@isset($MYDATA){{$MYDATA->father_address}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Contact Number</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_fath_contact" class="form-control" value="@isset($MYDATA){{$MYDATA->father_contact}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Occupation</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_fath_occu" class="form-control" value="@isset($MYDATA){{$MYDATA->father_job}}@endisset">
							      	</div>
							    </div>
							    <br>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Mother's Name</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_moth_name" class="form-control" value="@isset($MYDATA){{$MYDATA->mother}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Address</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_moth_add" class="form-control" value="@isset($MYDATA){{$MYDATA->mother_address}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Contact Number</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_moth_contact" class="form-control" value="@isset($MYDATA){{$MYDATA->mother_contact}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Occupation</label>
							      	<div class="col-sm-8">
							      		<input type="text" name="txt_moth_occu" class="form-control" value="@isset($MYDATA){{$MYDATA->mother_job}}@endisset">
							      	</div>
							    </div>
						  	</div>
					  	</div>
					</div>
					{{-- PERSONNAL BACKGROUND TAB --}}
					{{-- CONTACT INFORMATION TAB --}}
					<div class="tab-pane fade" id="CONT_TAB_BDY" role="tabpanel" aria-labelledby="CONT_TAB">
						<br>
					  	<div class="row">
					  		<div class="col-sm-6">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Contact No. {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_contact_num" value="@isset($MYDATA){{$MYDATA->emp_contact}}@endisset">
							      	</div>
							    </div>
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Home Tel No. {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_home_tel" value="@isset($MYDATA){{$MYDATA->home_tel}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Email Address {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="email" class="form-control" name="txt_email" value="@isset($MYDATA){{$MYDATA->email}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Home Address {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_home_add" value="@isset($MYDATA){{$MYDATA->home_address}}@endisset">
							      	</div>
							    </div>
							    <br>
							    <div class="form-group row">
							      	<label class="col-sm-12 col-form-label">Contact in case of emergency</label>
							    </div>
							    <br>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Name {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_emerg_name" value="@isset($MYDATA){{$MYDATA->emergency_name}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Contact no. {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_emerg_cont" value="@isset($MYDATA){{$MYDATA->emergency_contact}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Home Address {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_emerg_add" value="@isset($MYDATA){{$MYDATA->em_home_address}}@endisset">
							      	</div>
							    </div>
							    <div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Relationship {{-- <strong style="color:red">*</strong> --}}</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_emerg_rel" value="@isset($MYDATA){{$MYDATA->relationship}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  	</div>
					</div>
					{{-- CONTACT INFORMATION TAB --}}
					{{-- EDUCATIONAL BACKGROUND TAB --}}
					<div class="tab-pane fade" id="EDU_TAB_BDY" role="tabpanel" aria-labelledby="EDU_TAB">
						<br>
					  	<div class="row">
					  		<div class="col-sm-8">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Primary</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_edu_pri" value="@isset($MYDATA){{$MYDATA->primary_ed}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  		<div class="col-sm-8">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Secondary</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_edu_sec" value="@isset($MYDATA){{$MYDATA->secondary_ed}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  		<div class="col-sm-8">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Tertiary</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_edu_ter" value="@isset($MYDATA){{$MYDATA->tertiary_ed}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  		<div class="col-sm-8">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Graduate</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_edu_grad" value="@isset($MYDATA){{$MYDATA->graduate}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  		<div class="col-sm-8">
					  			<div class="form-group row">
							      	<label class="col-sm-4 col-form-label">Post Graduate</label>
							      	<div class="col-sm-8">
							      		<input type="text" class="form-control" name="txt_edu_post_grad" value="@isset($MYDATA){{$MYDATA->post_graduate}}@endisset">
							      	</div>
							    </div>
					  		</div>
					  	</div>
					</div>
					{{-- EDUCATIONAL BACKGROUND TAB --}}
				</div>
			</form>
		</div>
	</div>
@endsection

@section('to-bottom')
	<style>
		.exclamPoint {
			color:red;font-weight: bolder;font-size: 20px
		}
		.blinking{
			animation:blinkingText 1s infinite;
		}
		@keyframes blinkingText{
			/*0%{		color: red;	}
			49%{	color: transparent;	}
			50%{	color: transparent;	}
			99%{	color: transparent;	}
			100%{	color: red;	}*/
			0%{		color: red;	}
			50%{	color: #8e0000;	}
			100%{	color: red;	}
		}
		body {
			    background: whitesmoke;
			    font-family: 'Open Sans', sans-serif;
			}

			.container {
			    max-width: 960px;
			    margin: 30px auto;
			    padding: 20px;
			}

			h1 {
			    font-size: 20px;
			    text-align: center;
			    margin: 20px 0 20px;
			    small {
			        display: block;
			        font-size: 15px;
			        padding-top: 8px;
			        color: gray;
			    }
			}

			.avatar-upload {
			    position: relative;
			    max-width: 205px;
			    margin: 50px auto;
			    .avatar-edit {
			        position: absolute;
			        right: 12px;
			        z-index: 1;
			        top: 10px;
			        input {
			            display: none;
			            + label {
			                display: inline-block;
			                width: 34px;
			                height: 34px;
			                margin-bottom: 0;
			                border-radius: 100%;
			                background: #FFFFFF;
			                border: 1px solid transparent;
			                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
			                cursor: pointer;
			                font-weight: normal;
			                transition: all .2s ease-in-out;
			                &:hover {
			                    background: #f1f1f1;
			                    border-color: #d6d6d6;
			                }
			                &:after {
			                    content: "\f040";
			                    font-family: 'FontAwesome';
			                    color: #757575;
			                    position: absolute;
			                    top: 10px;
			                    left: 0;
			                    right: 0;
			                    text-align: center;
			                    margin: auto;
			                }
			            }
			        }
			    }
			    .avatar-preview {
			        width: 192px;
			        height: 192px;
			        position: relative;
			        border-radius: 100%;
			        border: 6px solid #F8F8F8;
			        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
			        > div {
			            width: 100%;
			            height: 100%;
			            border-radius: 100%;
			            background-size: cover;
			            background-repeat: no-repeat;
			            background-position: center;
			        }
			    }

			}
	</style>
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript">
		$('input[name=txt_hired]').datepicker(date_option5);
		$('input[name=txt_con_dt]').datepicker(date_option5);
		$('input[name=txt_prob_dt]').datepicker(date_option5);
		$('input[name=txt_reg_dt]').datepicker(date_option5);
		$('input[name=txt_resign_dt]').datepicker(date_option5);
		$('input[name=txt_termi_dt]').datepicker(date_option5);
		$('input[name=txt_dt_birth]').datepicker(date_option5);
	</script>
	<script type="text/javascript">
		$('#bio_message').hide();
		var tabs = $('#myTabContent');
		var t_selected = null;
		function RemoveBlink()
		{
			$('#myTab > .nav-item > .nav-link').removeClass('blinking');
		}
		function HideTabs()
		{
			for (var i = 0; i < tabs.children().length; i++) {
				tabs.children()[i].classList.remove('active');
				tabs.children()[i].classList.remove('show');
			}
			$('#myTab > .nav-item > .nav-link').removeClass('active');
		}
		function ChangeTab(obj, e)
		{
			e.preventDefault();
			var nb = NavButton($(obj).attr('data-num'));
			if (nb) {
				RemoveBlink();
				HideTabs();
				$('button[name="nxBTN"]').attr('onclick', "ChangeTab($('#"+$(obj).attr('data-next')+"'),event)");
				$($('#myTab > .nav-item > .nav-link#'+$(obj).attr('id'))[0]).addClass('active');
				$($(obj).attr('href')).addClass('active');
				$($(obj).attr('href')).addClass('show');
			} else {
				$('#myTab > .nav-item .active').addClass('blinking');
			}
		}
		function NavButton(trgt)
		{
			ctab = $($('#myTab').find('.active')[0]).attr('id');
			return checkCurrentTab(ctab, trgt);
		}
		var ctab_GEN_TAB = false;
		var ctab_ADD_TAB = false;
		var ctab_PERBAC_TAB = false;
		var ctab_CONT_TAB = false;
		var ctab_EDU_TAB = false;

		function checkCurrentTab(ctab, trgt)
		{
			if (
				ctab_GEN_TAB == true &&
				ctab_ADD_TAB == true &&
				ctab_PERBAC_TAB == true &&
				trgt == 4
			) {
				$('button[name=svBTN]').show();
			} else {
				$('button[name=svBTN]').hide();
			}
			if (ctab == "GEN_TAB") {
				// return true;
				$('input[name="txt_accountnumber"]').parsley().validate();
				$('input[name="txt_lname"]').parsley().validate();
				$('input[name="txt_fname"]').parsley().validate();
				$('select[name="txt_dept"]').parsley().validate();
				$('input[name="txt_id"]').parsley().validate();
				$('select[name="txt_jobdesc"]').parsley().validate();
				if(
					$('input[name="txt_accountnumber"]').parsley().validate() == true &&
					$('input[name="txt_lname"]').parsley().validate() == true &&
					$('input[name="txt_fname"]').parsley().validate() == true &&
					$('select[name="txt_dept"]').parsley().validate() == true &&
					$('input[name="txt_id"]').parsley().validate() == true &&
					$('select[name="txt_jobdesc"]').parsley().validate() == true
					)
				{
					ctab_GEN_TAB = true;
					return true;
				} else {
					ctab_GEN_TAB = false;
					return false;
				}
			}
			else if (ctab == "ADD_TAB") {
				// return true;
				$('input[name="txt_hired"]').parsley().validate();
				$('select[name="txt_emp_stat"]').parsley().validate();
				// $('select[name="txt_emp_type"]').parsley().validate();
				$('select[name="txt_rate_type"]').parsley().validate();
				$('input[name="txt_py_rate"]').parsley().validate();
				$('select[name="txt_tax_brac"]').parsley().validate();
				// $('select[name="txt_ss_brac"]').parsley().validate();
				// if($('input[name="txt_fx_sched"]:checked').val() == 'Yes'){
				// 	$('input[name="txt_sft_1"]').parsley().validate();
				// 	$('input[name="txt_sft_2"]').parsley().validate();
				// } else {
				// 	$('input[name="txt_sft_1"]').parsley().destroy();
				// 	$('input[name="txt_sft_2"]').parsley().destroy();
				// }
				if (
					
					$('input[name="txt_hired"]').parsley().validate() == true &&
					$('select[name="txt_emp_stat"]').parsley().validate() == true &&
					// $('select[name="txt_emp_type"]').parsley().validate() == true &&
					$('select[name="txt_rate_type"]').parsley().validate() == true &&
					$('input[name="txt_py_rate"]').parsley().validate() == true &&
					$('select[name="txt_tax_brac"]').parsley().validate() == true /*&&
					$('select[name="txt_ss_brac"]').parsley().validate()*/
				) {
					//CHECK IF BIOMETRIC ID IS UNIQUE
					var bio = $('input[name="txt_biometric"]').val();

					if(bio == '' || bio == null)
					{
						ctab_ADD_TAB = true;
						return true;
					}
					else
					{
						var data = 
						{
							bio : bio,
							empid: $('[name=txt_id]').val()
						}	
						$.ajax({
							type: "post",
							async: false,
							url: "{{url('master-file/employee/check-biometric')}}",
							data: data,
							success: function(data) 
							{
								ctab_ADD_TAB = (data == 'unique');
								(data == 'unique' ? $('#bio_message').hide() : $('#bio_message').show())
							},
						});	
						return ctab_ADD_TAB
					}
					//END CHECK BIOMETRIC ID
					
				} else {
					ctab_ADD_TAB = false;
					return false;
				}


			}
			else if (ctab == "PERBAC_TAB") {
				// return true;
				$('select[name="txt_gen"]').parsley().validate();
				$('input[name="txt_dt_birth"]').parsley().validate();
				$('select[name="txt_civ_stat"]').parsley().validate();
				if(
					$('select[name="txt_gen"]').parsley().validate() == true &&
					$('input[name="txt_dt_birth"]').parsley().validate() == true &&
					$('select[name="txt_civ_stat"]').parsley().validate() == true
					)
				{
					ctab_PERBAC_TAB = true;
					return true;
				} else {
					ctab_PERBAC_TAB = false;
					return false;
				}
			}
			else if (ctab == "CONT_TAB") {
				ctab_CONT_TAB = true;
				return true;
			}
			else if (ctab == "EDU_TAB") {
				ctab_EDU_TAB = true;
				return true;
			}
			return false;
		}
		function ToggleSaveBtn()
		{
			console.log(t_selected);
			if (t_selected == "EDU_TAB_BDY") {
				$('button[name="svBTN"]').show();
			} else {
				$('button[name="svBTN"]').hide();
			}
		}
		function ToggleChkBx(chc, inp)
		{
			$('input[name="'+chc+'"]').is(':checked') ? $('input[name="'+inp+'"]').removeAttr('disabled') : $('input[name="'+inp+'"]').attr('disabled', '');
			$('input[name="'+chc+'"]').is(':checked') ? null : $('input[name="'+inp+'"]').val('');
		}

	</script>
@endsection