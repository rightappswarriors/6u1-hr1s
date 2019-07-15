@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-users"></i> Employee (New) <button type="button" class="btn btn-danger" onclick="location.href='{{ url('master-file/employee') }}';">Back</button>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card-body">
						{{-- <div class="card-body"> --}}
							<form method="post" action="#" id="frm-pp" data="#" data-parsley-validate novalidate>
								@csrf
								<span class="AddMode">
									<ul class="nav nav-tabs" id="myTab" role="tablist">
									  <li class="nav-item">
									    <a class="nav-link TestTab active" style="font-size: 20px" id="GEN_TAB" data-toggle="tab" href="#GEN_TAB_BDY" role="tab" aria-controls="home" aria-selected="true">General Info <span id="gen_blink" class="exclamPoint" style="display:none">!</span></a>
									  </li>
									  <li class="nav-item ">
									    <a class="nav-link TestTab" style="font-size: 20px" id="ADD_TAB" {{-- data-toggle="tab" --}} href="#" role="tab" aria-controls="contact" aria-selected="false">Additional Info <span id="add_blink" class="exclamPoint" style="display:none">!</span></a>
									  </li>
									  <li class="nav-item">
									    <a class="nav-link TestTab" style="font-size: 20px" id="PERBAC_TAB" {{-- data-toggle="tab" --}} href="#" role="tab" aria-controls="contact" aria-selected="false">Personnal Background <span id="perbac_blink" class="exclamPoint" style="display:none">!</span></a>
									  </li>
									  <li class="nav-item">
									    <a class="nav-link TestTab" style="font-size: 20px" id="CONT_TAB" {{-- data-toggle="tab" --}} href="#" role="tab" aria-controls="contact" aria-selected="false">Contact Info <span id="cont_blink" class="exclamPoint" style="display:none">!</span></a>
									  </li>
									  <li class="nav-item ">
									    <a class="nav-link TestTab" style="font-size: 20px" id="EDU_TAB" {{-- data-toggle="tab" --}} href="#" role="tab" aria-controls="contact" aria-selected="false">Educational Background <span id="edu_blink" class="exclamPoint" style="display:none">!</span></a>
									  </li>
									</ul>

									<div class="tab-content" id="myTabContent">
										{{-- GENNERAL INFORMATION TAB --}}
									  <div class="tab-pane fade active show TestTabDiv" id="GEN_TAB_BDY" role="tabpanel" aria-labelledby="GEN_TAB">
									  	<br>
									  	<div class="row">
									  		<div class="col-sm-6">
										  		<div class="form-group row">
											      <label class="col-sm-4 col-form-label">Employee ID <strong style="color:red;">*</strong></label>
											      <div class="col-sm-8">
											      	<input type="text" name="txt_id" style="text-transform: uppercase;" class="form-control T0r" maxlength="8" placeholder="Employee Number" required>
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Last Name <strong style="color:red">*</strong></label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control T0 T0r" name="txt_lname" placeholder="Last Name" required>
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">First Name <strong style="color:red">*</strong></label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control T0 T0r" name="txt_fname" placeholder="First Name" required>
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Middle Initial</label>
											      <div class="col-sm-2">
											      	<input type="text" maxlength="1" style="text-transform: uppercase;" class="form-control T0" name="txt_mname" placeholder="M.I.">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Office <strong style="color:red">*</strong></label>
											      <div class="col-sm-8">
											      	<select class="form-control T0 T0r" onchange="" name="txt_dept" required>
											      	@isset($office)
											      		<option value="">Select Office...</option>
											      		@foreach ($office as $d)
											      			<option value="{{$d->cc_code}}">{{$d->cc_desc}}</option>
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
											      <label class="col-sm-4 col-form-label">Job Title <strong style="color:red">*</strong></label>
											      <div class="col-sm-8">
											      	<select class="form-control T0 T0r" name="txt_jobdesc" required>
											      		@isset($position)
											      			<option value="">Select Position..</option>
											      			@foreach ($position as $d)
											      				<option value="{{$d->jtid}}">{{$d->jtitle_name}}</option>
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
									  <div class="tab-pane fade TestTabDiv" id="ADD_TAB_BDY" role="tabpanel" aria-labelledby="ADD_TAB">
									  	<br>
									  	<div class="row">
									  		<div class="col-sm-7">
										  		<div class="row">
										  			<div class="col">
										  				<div class="form-group row">
													      <label class="col-sm-4 col-form-label form-check-label">Date Hired<strong style="color:red">*</strong></label>
													      <div class="col-sm-8">
													      	<input type="date" class="form-control T0r" name="txt_hired" required="">
													      </div>
													    </div>
													    <div class="form-group row">
													      <div class="col-sm-6 form-check">
													      	<input class="" type="checkbox" name="txt_con_chk" onclick="ToggleChkBx('txt_con_chk', 'txt_con_dt')">
													      	<small>Contractual Date</small>
													      </div>
													      <div class="col-sm-6">
													      	<input type="date" class="form-control" name="txt_con_dt" disabled="">
													      </div>
													    </div>
													    <div class="form-group row">
													    	<div class="col-sm-6 form-check">
														      	<input class="" type="checkbox" name="txt_prob_chk" onclick="ToggleChkBx('txt_prob_chk', 'txt_prob_dt')">
														      	<small>Probitionary Date</small>
														    </div>
														    <div class="col-sm-6">
														     	<input type="date" class="form-control" name="txt_prob_dt" disabled="">
														    </div>
													    </div>
													    <div class="form-group row">
													      <div class="col-sm-6 form-check">
													      	<input class="" type="checkbox" name="txt_reg_chk" onclick="ToggleChkBx('txt_reg_chk', 'txt_reg_dt')">
													      	<small>Date Regularized</small>
													      </div>
													      <div class="col-sm-6">
													      	<input type="date" class="form-control" name="txt_reg_dt" disabled="">
													      </div>
													    </div>
										  			</div>
										  			<div class="col">
										  				<div class="form-group row">
													      <div class="col-sm-6 form-check">
													      	<input class="" type="checkbox" name="txt_resign_chk" onclick="ToggleChkBx('txt_resign_chk', 'txt_resign_dt')">
													      	<small>Date Resigned</small>
													      </div>
													      <div class="col-sm-6">
													      	<input type="date" class="form-control" name="txt_resign_dt" disabled="">
													      </div>
													    </div>
													    <div class="form-group row">
													      <div class="col-sm-6 form-check">
													      	<input class="" type="checkbox" name="txt_termi_chk" onclick="ToggleChkBx('txt_termi_chk', 'txt_termi_dt')">
													      	<small>Date Terminated</small>
													      </div>
													      <div class="col-sm-6">
													      	<input type="date" class="form-control" name="txt_termi_dt">
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
																<option value="{{$d->statcode}}">{{$d->description}}</option>
															@endforeach
														@else
															<option value="">No Employement Status registered...</option>
											      		@endisset
											      	</select>
											      </div>
											    </div>
											    <div class="row">
											    	<div class="col">
											    		<div class="form-group row">
													      <small class="col-sm-4">Contract Days</small>
													      <div class="col-sm-8">
													      	<input type="number" class="form-control" name="txt_contract">
													      </div>
													    </div>
											    	</div>
											    	<div class="col">&nbsp;</div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-3 col-form-label">PRC Number</label>
											      <div class="col-sm-9">
											      	<input type="text" class="form-control" name="txt_prc" placeholder="PRC Number">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-3 col-form-label">CTC Number</label>
											      <div class="col-sm-9">
											      	<input type="text" class="form-control" name="txt_ctc" placeholder="CTC Number">
											      </div>
											    </div>
											    <div class="row">
											    	<div class="col-sm-8">
											    		<div class="form-group row">
											    			<label class="col-sm-4 col-form-label">Rate Type <strong style="color:red">*</strong></label>
														      <div class="col-sm-8">
														      	<select name="txt_rate_typ" class="form-control T0r" required="">
														      		@isset($rate)
														      			<option value="">Select Rate Type..</option>
														      			@foreach ($rate as $r)
														      				<option value="{{$r->ratecode}}">{{$r->description}}</option>
														      			@endforeach
														      		@else
																		<option value="">No Rate Type registered..</option>
														      		@endisset
														      	</select>
														      </div>
											    		</div>
											    	</div>
											    	<div class="col-sm-4">
											    		<div class="form-group row">
													      <div class="col-sm-12 form-check">
													      	<input type="checkbox"  name="txt_fx_rate" value="true"  aria-label="...">
													      	<label class="col-form-label">Fixed Rate</label>
													      </div>
													    </div>
											    	</div>
											    </div>
											    <div class="row">
											    	<div class="col-sm-8">
											    		<div class="form-group row">
											    			<label class="col-sm-4 col-form-label">Pay Rate <strong style="color:red">*</strong></label>
														      <div class="col-sm-8">
														      	<input type="text" class="form-control T0r" name="txt_py_rate" required="">
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
														      	<input type="text" class="form-control" name="txt_biometric">
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
											      	<input type="text" class="form-control" name="txt_sss">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Pag-ibig #</label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control" name="txt_pagibig">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Philhealth #</label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control" name="txt_philhealth">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Payrol acct. #</label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control" name="txt_payrol">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">TIN</label>
											      <div class="col-sm-8">
											      	<input type="text" class="form-control" name="txt_tin">
											      </div>
											    </div>
											    <div class="form-group row">
											      <label class="col-sm-4 col-form-label">Tax Bracket <strong style="color:red">*</strong></label>
											      <div class="col-sm-8">
											      	<select class="form-control T0r" name="txt_tax_brac" required="">
											      		@isset($tax)
											      			<option value="">Select Tax Bracket..</option>
											      			@foreach ($tax as $r)
											      				<option value="{{$r->code}}">{{$r->description}}</option>
											      			@endforeach
											      		@else
															<option value="">No Tax Bracket registered..</option>
											      		@endisset
											      	</select>
											      </div>
											    </div>
											    <div class="form-group row">
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
											    </div>
											    <div class="form-group row">
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
											    </div>
										  	</div>
									  	</div>
									  </div>
									  	{{-- ADDITIONAL INFORMATION TAB --}}
									  	{{-- PERSONNAL BACKGROUND TAB --}}
									  <div class="tab-pane fade TestTabDiv" id="PERBAC_TAB_BDY" role="tabpanel" aria-labelledby="PERBAC_TAB">
									  	<br>
									  	<div class="row">
									  		<div class="col">
										  		<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Gender <strong style="color:red">*</strong></label>
											      	<div class="col-sm-8">
											      		<select class="form-control T0r" name="txt_gen">
											      			<option value="">Select Gender..</option>
											      			<option value="Male">Male</option>
											      			<option value="Female">Female</option>
											      		</select>
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Date of Birth <strong style="color:red">*</strong></label>
											      	<div class="col-sm-8">
											      		<input type="date" class="form-control navbar-expand-md T0r" name="txt_dt_birth" required="">
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
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Religion</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_reli" class="form-control">
											      	</div>
											    </div>
											    <br>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Height (cm)</label>
											      	<div class="col-sm-3">
											      		<input type="text" name="txt_height" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Weight (kg)</label>
											      	<div class="col-sm-3">
											      		<input type="text" name="txt_weight" class="form-control">
											      	</div>
											    </div>
										  	</div>
										  	<div class="col">
										  		<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Father's Name</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_fath_name" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Address</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_fath_add" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Contact Number</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_fath_contact" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Occupation</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_fath_occu" class="form-control">
											      	</div>
											    </div>
											    <br>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Mother's Name</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_moth_name" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Address</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_moth_add" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Contact Number</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_moth_contact" class="form-control">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Occupation</label>
											      	<div class="col-sm-8">
											      		<input type="text" name="txt_moth_occu" class="form-control">
											      	</div>
											    </div>
										  	</div>
									  	</div>
									  </div>
									  	{{-- PERSONNAL BACKGROUND TAB --}}
									  	{{-- CONTACT INFORMATION TAB --}}
									  <div class="tab-pane fade TestTabDiv" id="CONT_TAB_BDY" role="tabpanel" aria-labelledby="CONT_TAB">
									  	<br>
									  	<div class="row">
									  		<div class="col-sm-6">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Contact No. {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_contact_num">
											      	</div>
											    </div>
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Home Tel No. {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_home_tel" >
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Email Address {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="email" class="form-control" name="txt_email" =>
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Home Address {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_home_add">
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
											      		<input type="text" class="form-control" name="txt_emerg_name">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Contact no. {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_emerg_cont">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Home Address {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_emerg_add">
											      	</div>
											    </div>
											    <div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Relationship {{-- <strong style="color:red">*</strong> --}}</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_emerg_rel">
											      	</div>
											    </div>
									  		</div>
									  	</div>
									  </div>
									  	{{-- CONTACT INFORMATION TAB --}}
									  	{{-- EDUCATIONAL BACKGROUND TAB --}}
									  <div class="tab-pane fade TestTabDiv" id="EDU_TAB_BDY" role="tabpanel" aria-labelledby="EDU_TAB">
									  	<br>
									  	<div class="row">
									  		<div class="col-sm-8">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Primary</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_edu_pri">
											      	</div>
											    </div>
									  		</div>
									  		<div class="col-sm-8">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Secondary</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_edu_sec">
											      	</div>
											    </div>
									  		</div>
									  		<div class="col-sm-8">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Tertiary</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_edu_ter">
											      	</div>
											    </div>
									  		</div>
									  		<div class="col-sm-8">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Graduate</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_edu_grad">
											      	</div>
											    </div>
									  		</div>
									  		<div class="col-sm-8">
									  			<div class="form-group row">
											      	<label class="col-sm-4 col-form-label">Post Graduate</label>
											      	<div class="col-sm-8">
											      		<input type="text" class="form-control" name="txt_edu_post_grad">
											      	</div>
											    </div>
									  		</div>
									  	</div>
									  </div>
									  	{{-- EDUCATIONAL BACKGROUND TAB --}}
									</div>
								</span>
								<span class="DeleteMode">
									<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Employee list?</p>
								</span>
							</form>
						{{-- </div> --}}
						<div class="modal-footer">
						<span class="AddMode">
							{{-- <button type="button" class="btn btn-primary" style="display:none" onclick="InitiateTab(-1)" name="bkBTN">Back</button> --}}
							<button type="button" class="btn btn-primary" onclick="InitiateTab(+1)" name="nxBTN">Next</button>
							<button type="submit" form="frm-pp" class="btn btn-success" {{-- onclick="checkCurrentTab(4)" --}} style="display:none" name="svBTN">Save</button>
							{{-- <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Cancel</button> --}}
						</span>
						<span class="DeleteMode">
							<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
							{{-- <button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button> --}}
						</span>
					</div>
					</div>
				</div>
				{{-- <div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div> --}}
			</div>
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
			0%{		color: red;	}
			49%{	color: transparent;	}
			50%{	color: transparent;	}
			99%{	color:transparent;	}
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
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
		var table = $('#dataTable').DataTable(date_option_min);
	</script>
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
		$( document ).ready(function() {
		    // alert('TEST')
		    AddMode();
		});
	</script>
	<script type="text/javascript">
	var CurrentTab = 0, MaxTab = 4, MinTab = 0;
	// TestTabDiv // TestTab
		function LoadDatable()
		{
			data.row.add([
				data.pay_code,
				data.date_from,
				data.date_to
			]).draw();
		}
		function InitiateTab(num)
		{
			// href="#ADD_TAB_BDY", #PERBAC_TAB_BDY, #CONT_TAB_BDY, #EDU_TAB_BDY
			// data-toggle="tab"
			$('.TestTab').removeClass('active');
			$('.TestTabDiv').removeClass('active');
			$('.TestTabDiv').removeClass('show');
			$('.TestTab').removeAttr('data-toggle');
			$('.TestTab').attr('href', '#');
			$('.TestTab').eq(0).attr('href', '#GEN_TAB_BDY');
			$('.TestTab').eq(0).attr('data-toggle', 'tab');
			if(num == 0)
			{
				CurrentTab = num;
				$('button[name="bkBTN"]').hide();
				$('button[name="nxBTN"]').show();
				$('button[name="svBTN"]').hide();
			}
			else
			{
				if(checkCurrentTab(CurrentTab))
				{
					CurrentTab = CurrentTab + num;
					CurrentTab = (CurrentTab < 0) ? 0 : CurrentTab;
					if(CurrentTab == MinTab)
					{
						$('button[name="bkBTN"]').hide();
						$('button[name="nxBTN"]').show();
						$('button[name="svBTN"]').hide();
					}
					else if(CurrentTab > MinTab && CurrentTab < MaxTab)
					{
						$('button[name="bkBTN"]').show();
						$('button[name="nxBTN"]').show();
						$('button[name="svBTN"]').hide();
					}
					else if (CurrentTab > MinTab && CurrentTab == MaxTab)
					{
						$('button[name="bkBTN"]').show();
						$('button[name="nxBTN"]').hide();
						$('button[name="svBTN"]').show();
						// $('#frm-pp').submit();
					}
				}
			}

			$('.TestTab').eq(CurrentTab).addClass('active');
			$('.TestTabDiv').eq(CurrentTab).addClass('active');
			$('.TestTabDiv').eq(CurrentTab).addClass('show');
		}
		function checkCurrentTab(CurrentTab)
		{
			if(CurrentTab == 0)
			{
				$('input[name="txt_lname"]').parsley().validate();
				$('input[name="txt_fname"]').parsley().validate();
				$('select[name="txt_dept"]').parsley().validate();
				$('input[name="txt_id"]').parsley().validate();
				$('select[name="txt_jobdesc"]').parsley().validate();
				if(
					$('input[name="txt_lname"]').parsley().validate() == true &&
					$('input[name="txt_fname"]').parsley().validate() == true &&
					$('select[name="txt_dept"]').parsley().validate() == true &&
					$('input[name="txt_id"]').parsley().validate() == true &&
					$('select[name="txt_jobdesc"]').parsley().validate() == true
					)
				{
					$('.TestTab').eq(0).attr('href', '#GEN_TAB_BDY');
					$('.TestTab').eq(0).attr('data-toggle', 'tab');
					$('.TestTab').eq(1).attr('href', '#ADD_TAB_BDY');
					$('.TestTab').eq(1).attr('data-toggle', 'tab');
					return true;
				}
			}
			else if(CurrentTab == 1)
			{
				$('input[name="txt_hired"]').parsley().validate();
				$('select[name="txt_emp_stat"]').parsley().validate();
				$('select[name="txt_rate_typ"]').parsley().validate();
				$('input[name="txt_py_rate"]').parsley().validate();
				$('select[name="txt_tax_brac"]').parsley().validate();
				$('select[name="txt_ss_brac"]').parsley().validate();
				if($('input[name="txt_fx_sched"]:checked').val() == 'Yes'){
					$('input[name="txt_sft_1"]').parsley().validate();
					$('input[name="txt_sft_2"]').parsley().validate();
				} else {
					$('input[name="txt_sft_1"]').parsley().destroy();
					$('input[name="txt_sft_2"]').parsley().destroy();
				}
				if(
					$('input[name="txt_hired"]').parsley().validate() == true &&
					$('select[name="txt_emp_stat"]').parsley().validate() == true &&
					$('select[name="txt_rate_typ"]').parsley().validate() == true &&
					$('input[name="txt_py_rate"]').parsley().validate() == true &&
					$('select[name="txt_tax_brac"]').parsley().validate() == true &&
					$('select[name="txt_ss_brac"]').parsley().validate()
					)
				{
					$('.TestTab').eq(0).attr('href', '#GEN_TAB_BDY');
					$('.TestTab').eq(0).attr('data-toggle', 'tab');
					$('.TestTab').eq(1).attr('href', '#ADD_TAB_BDY');
					$('.TestTab').eq(1).attr('data-toggle', 'tab');
					$('.TestTab').eq(2).attr('href', '#PERBAC_TAB_BDY');
					$('.TestTab').eq(2).attr('data-toggle', 'tab');
					return true;
				}
			}
			else if(CurrentTab == 2)
			{
				$('select[name="txt_gen"]').parsley().validate();
				$('input[name="txt_dt_birth"]').parsley().validate();
				$('select[name="txt_civ_stat"]').parsley().validate();
				if(
					$('select[name="txt_gen"]').parsley().validate() == true &&
					$('input[name="txt_dt_birth"]').parsley().validate() == true &&
					$('select[name="txt_civ_stat"]').parsley().validate() == true
					)
				{
					$('.TestTab').eq(0).attr('href', '#GEN_TAB_BDY');
					$('.TestTab').eq(0).attr('data-toggle', 'tab');
					$('.TestTab').eq(1).attr('href', '#ADD_TAB_BDY');
					$('.TestTab').eq(1).attr('data-toggle', 'tab');
					$('.TestTab').eq(2).attr('href', '#PERBAC_TAB_BDY');
					$('.TestTab').eq(2).attr('data-toggle', 'tab');
					$('.TestTab').eq(3).attr('href', '#CONT_TAB_BDY'); //
					$('.TestTab').eq(3).attr('data-toggle', 'tab');
					return true;
				}
			}
			else if(CurrentTab == 3)
			{
				$('input[name="txt_contact_num"]').parsley().validate();
				$('input[name="txt_home_tel"]').parsley().validate();
				$('input[name="txt_email"]').parsley().validate();
				$('input[name="txt_home_add"]').parsley().validate();
				$('input[name="txt_emerg_name"]').parsley().validate();
				$('input[name="txt_emerg_cont"]').parsley().validate();
				$('input[name="txt_emerg_add"]').parsley().validate();
				$('input[name="txt_emerg_rel"]').parsley().validate();
				if(
					$('input[name="txt_contact_num"]').parsley().validate() == true &&
					$('input[name="txt_home_tel"]').parsley().validate() == true &&
					$('input[name="txt_email"]').parsley().validate() == true &&
					$('input[name="txt_home_add"]').parsley().validate() == true &&
					$('input[name="txt_emerg_name"]').parsley().validate() == true &&
					$('input[name="txt_emerg_cont"]').parsley().validate() == true &&
					$('input[name="txt_emerg_add"]').parsley().validate() == true &&
					$('input[name="txt_emerg_rel"]').parsley().validate() == true
					)
				{
					$('.TestTab').eq(0).attr('href', '#GEN_TAB_BDY');
					$('.TestTab').eq(0).attr('data-toggle', 'tab');
					$('.TestTab').eq(1).attr('data-toggle', 'tab');
					$('.TestTab').eq(1).attr('href', '#ADD_TAB_BDY');
					$('.TestTab').eq(2).attr('data-toggle', 'tab');
					$('.TestTab').eq(2).attr('href', '#PERBAC_TAB_BDY');
					$('.TestTab').eq(3).attr('data-toggle', 'tab');
					$('.TestTab').eq(3).attr('href', '#CONT_TAB_BDY');
					$('.TestTab').eq(4).attr('data-toggle', 'tab');
					$('.TestTab').eq(4).attr('href', '#EDU_TAB_BDY');
					return true;
				}
			} else {

				return true;
			}
		}
		function LoadDeptSection()
		{
			if ($('select[name="txt_dept"]').val() != '') {
				$('select[name="txt_deptsec"]').empty();
				$.ajax({
					url : '{{ url('master-file/department-section/getOne') }}',
					method : 'GET',
					data : {'_token' : $('meta[name="csrf-token"]').attr('content'), id : $('select[name="txt_dept"]').val()},
					success : function(response){
						if(response.status == 'OK') {
							// console.log(response.data[0].section_name);
							if(response.data.length > 0){
								$('select[name="txt_deptsec"]').append('<option value="">Select Section..</option>');
								for(var i = 0; i < response.data.length; i++) {
									$('select[name="txt_deptsec"]').append('<option value="'+response.data[i].secid+'">'+response.data[i].section_name+'</option>');
								}
							} else {
								$('select[name="txt_deptsec"]').append('<option value="">No Section registered in the selected Department..</option>');
							}
						} else if (response.status == 'ERROR'){
							console.log(response.status);

						}
					},
					error : function(a, b, c){

					}

				});
			} else {
				$('select[name="txt_deptsec"]').empty();
			}
		}
		function ToggleChkBx(chc, inp)
		{
			$('input[name="'+chc+'"]').is(':checked') ? $('input[name="'+inp+'"]').removeAttr('disabled') : $('input[name="'+inp+'"]').attr('disabled', '');
			$('input[name="'+chc+'"]').is(':checked') ? null : $('input[name="'+inp+'"]').val('');
		}
		function FixSched()
		{
			if ($('input[name="txt_fx_sched"]:checked').val() == 'Yes') 
			 {
				$('.isFix').prop('disabled', false);
				$('.mustReq').attr('required', '');

			} else {
				$('.isFix').prop('disabled', true);
				$('input.isFix').val('');
				$('select.isFix').val('').trigger('change');
				$('.mustReq').removeAttr('required');
			}
		}
		function AddMode() {
		// $('#opt-add').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee')}}');
			InitiateTab(0);
			// $('input[name="txt_id"]').attr('readonly', '');
			$('input[name="txt_code"]').val('');
			$('input[name="txt_name"]').val('');
			$('.T0r').attr('required', '');
			// $('select[name="txt_dept"]').val('').trigger('change');

			$('#TESTDOCU').addClass('mw-100');
			$('#TESTDOCU').addClass('w-80');
			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		// });
		}

		$('#opt-update').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee')}}/update');
			$('.T0r').attr('required', '');
			// $('input[name="txt_id"]').attr('readonly', '');
			$('#TESTDOCU').addClass('mw-100');
			$('#TESTDOCU').addClass('w-80');
			// $('input[name="txt_code"]').val();
			// $('input[name="txt_name"]').val(selected_row.attr('data_name'));
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');
			$.ajax({
				url : '{{ url('master-file/employee/getOne') }}',
				method : 'POST',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : selected_row.attr('data_id')},
				success : function(data){
					var d = JSON.parse(data);
					if(d){
						$('input[name="txt_id"]').val(d.empid);
						$('input[name="txt_lname"]').val(d.lastname);
						$('input[name="txt_fname"]').val(d.firstname);
						$('input[name="txt_mname"]').val(d.mi.charAt(0));
						$('select[name="txt_dept"]').val(d.department).trigger('change');
						setTimeout( function(){ 
						    $('select[name="txt_deptsec"]').val(d.section).trigger('change');
						  }  , 1000 );
						$('select[name="txt_jobdesc"]').val(d.positions).trigger('change');
						$('input[name="txt_hired"]').val(d.date_hired);
						if(d.contractual_date != null){
							$('input[name="txt_con_chk"]').prop('checked', true);$('input[name="txt_con_dt"]').val(d.contractual_date);
							ToggleChkBx('txt_con_chk', 'txt_con_dt');
						}
						if(d.prohibition_date != null){
							$('input[name="txt_prob_chk"]').prop('checked', true);$('input[name="txt_prob_dt"]').val(d.prohibition_date);
							ToggleChkBx('txt_prob_chk', 'txt_prob_dt');
						}
						if(d.date_regular != null){
							$('input[name="txt_reg_chk"]').prop('checked', true);$('input[name="txt_reg_dt"]').val(d.date_regular);
							ToggleChkBx('txt_reg_chk', 'txt_resign_dt');
						}
						if(d.date_resigned != null){
							$('input[name="txt_resign_chk"]').prop('checked', true);$('input[name="txt_resign_dt"]').val(d.date_resigned);
							ToggleChkBx('txt_resign_chk', 'txt_resign_dt');
						}
						$('select[name="txt_emp_stat"]').val(d.empstatus).trigger('change');
						$('input[name="txt_contract"]').val(d.contract_days);
						$('input[name="txt_ctc"]').val(d.ctc);
						$('input[name="txt_prc"]').val(d.prc);
						$('select[name="txt_rate_typ"]').val(d.rate_type);
						$('input[name="txt_py_rate"]').val(d.pay_rate);
						$('input[name="txt_fx_rate"]').prop('checked', ((d.fixed_rate == '1') ? true : false));
						$('input[name="txt_biometric"]').val(d.biometric);
						$('input[name="txt_sss"]').val(d.sss);
						$('input[name="txt_pagibig"]').val(d.pagibig);
						$('input[name="txt_philhealth"]').val(d.philhealth);
						$('input[name="txt_payrol"]').val(d.payroll_account);
						$('input[name="txt_tin"]').val(d.tin);
						$('select[name="txt_tax_brac"]').val(d.tax_bracket).trigger('change');
						$('select[name="txt_ss_brac"]').val(d.tax_bracket).trigger('change');
						if(d.fixed_sched == 'Y'){
							$('#inlineRadio1').prop('checked', true);
							$('input[name="txt_sft_1"]').val(d.shift_sched_from);
							$('input[name="txt_sft_2"]').val(d.shift_sched_to);
							$('input[name="txt_sat_sft_1"]').val(d.shift_sched_sat_from);
							$('input[name="txt_sat_sft_2"]').val(d.shift_sched_sat_to);
							$('select[name="txt_day_off_1"]').val(d.dayoff1).trigger('change');
							$('select[name="txt_day_off_2"]').val(d.dayoff2).trigger('change');
						} else {
							$('#inlineRadio2').prop('checked', true);
						}
						FixSched();
						$('select[name="txt_gen"]').val(d.sex).trigger('change');
						$('input[name="txt_dt_birth"]').val(d.birth);
						$('select[name="txt_civ_stat"]').val(d.civil_status).trigger('change');
						$('input[name="txt_reli"]').val(d.religion);
						$('input[name="txt_height"]').val(d.height);
						$('input[name="txt_weight"]').val(d.weight);
						$('input[name="txt_fath_name"]').val(d.father);
						$('input[name="txt_fath_add"]').val(d.father_address);
						$('input[name="txt_fath_contact"]').val(d.father_contact);
						$('input[name="txt_fath_occu"]').val(d.father_job);
						$('input[name="txt_moth_name"]').val(d.txt_moth_name);
						$('input[name="txt_moth_add"]').val(d.txt_moth_add);
						$('input[name="txt_moth_contact"]').val(d.txt_moth_contact);
						$('input[name="txt_moth_occu"]').val(d.txt_moth_occu);
						$('input[name="txt_contact_num"]').val(d.emp_contact);
						$('input[name="txt_home_tel"]').val(d.home_tel);
						$('input[name="txt_email"]').val(d.email);
						$('input[name="txt_home_add"]').val(d.home_address);
						$('input[name="txt_emerg_name"]').val(d.emergency_name);
						$('input[name="txt_emerg_cont"]').val(d.emergency_contact);
						$('input[name="txt_emerg_add"]').val(d.em_home_address);
						$('input[name="txt_emerg_rel"]').val(d.relationship);
					}
				},
				error : function(a, b, c)
				{
					console.log(c);
				}
			});

			$('.AddMode').show();
			$('.DeleteMode').hide();
			$('#modal-pp').modal('show');
		});

		$('#opt-delete').on('click', function() {
			$('#frm-pp').attr('action', '{{url('master-file/employee')}}/delete');
		
			$('input[name="txt_code"]').attr('readonly', '');
			$('input[name="txt_id"]').val(selected_row.attr('data_id'));
			$('input[name="txt_name"]').val(selected_row.attr('data_name'));
			$('#TESTDOCU').removeClass('mw-100');
			$('#TESTDOCU').removeClass('w-75');
			$('.T0r').removeAttr('required');
			// $('select[name="txt_dept"]').val(selected_row.attr('data_dept')).trigger('change');
			$('#TOBEDELETED').text(selected_row.attr('data_name'));

			$('.AddMode').hide();
			$('.DeleteMode').show();
			$('#modal-pp').modal('show');
		});
		function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
	</script>
@endsection