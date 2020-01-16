@extends('layouts.user')

@section('to-body')
	@php 
		$current_date = date('Y-m-d');
		$alot_date = [
			date('Y-m-d', strtotime("2019-08-21")),
			date('Y-m-d', strtotime("2019-08-22")),
			date('Y-m-d', strtotime("2019-08-23")),
			date('Y-m-d', strtotime("2019-08-27")),
			date('Y-m-d', strtotime("2019-08-28")),
		];
	@endphp
	@for($i=0;$i<count($alot_date);$i++)
	@if($alot_date[$i] == $current_date)
	<div class="alert alert-success" role="alert">
	    <h4 class="alert-heading">New Update Applied!</h4>
	    <p>A new update is now implemented and we kindly ask you again, dear user to update once again the <u>Designation</u> and <u>Employement Status</u> of each employee because there are features that greatly involves these two fields. If you notice the words <u>"office-not-found"</u> or <u>"employee-status-not-found"</u> in any of the rows, please do update that employee.</p>
	    <hr>
	    <p class="mb-0">Sorry for the inconvenience. Thank you.</p>
	</div>
	@endif
	@endfor
	<div class="card">
		<div class="card-header">
			<i class="fa fa-users"></i> Employee <button type="button" class="btn btn-success mr-1" onclick="location.href='{{ url('master-file/employee/new2') }}';"><i class="fa fa-plus"></i> Add</button> <button type="button" class="btn btn-warning" id="opt-flag"><i class="fa fa-flag"></i> Set Excemptions</button> {{-- <button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button> --}}
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-hover table-bordered" id="dataTable">
									<col>
									<col>
									<col>
									<col>
									<col>
									<col width="15%">
									<thead>
										<tr>
											<th>Employee ID</th>
											<th>Name</th>
											<th>Job Title</th>
											<th>Designation</th>
											<th>Employment Status</th>
											<th>Head of Facility?</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@isset($employee)
											@if(count($employee)>0)
												@foreach($employee as $pp)
												<tr data_id="{{$pp->empid}}" data_name="@if($pp->mi != '')
															{{$pp->firstname}} {{$pp->mi}} {{$pp->lastname}}
														@else
															{{$pp->firstname}} {{$pp->lastname}}
														@endif">
													<td>{{$pp->empid}}</td>
													<td>
														@if($pp->mi != '')
															{{$pp->firstname}} {{$pp->mi}} {{$pp->lastname}}
														@else
															{{$pp->firstname}} {{$pp->lastname}}
														@endif
													</td>
													<td>{{$pp->jobtitle}}</td>
													<td>{{$pp->office}}</td>
													<td>{{$pp->emp_status}}</td>
													<td>{{($pp->isheadoffacility ? 'Yes' : 'No')}}</td>
													<td>
														<button type="button" class="btn btn-primary mr-1" id="opt-update" onclick="row_update(this)"><i class="fa fa-edit"></i></button>
														<button type="button" class="btn btn-danger" id="opt-delete" onclick="row_delete(this)"><i class="fa fa-trash"></i></button>
													</td>
												</tr>
												@endforeach
											@endif
										@endisset
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				{{-- <div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div id="TESTDOCU" class="modal-dialog mw-100 w-75" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#" data-parsley-validate novalidate>
						@csrf
						<span class="AddMode">
							<input type="text" name="txt_id">
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Employee list?</p>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						{{-- <button type="button" class="btn btn-primary" style="display:none" onclick="InitiateTab(-1)" name="bkBTN">Back</button> --}}
						<button type="button" class="btn btn-primary" onclick="InitiateTab(+1)" name="nxBTN">Next</button>
						<button type="submit" form="frm-pp" class="btn btn-success" {{-- onclick="checkCurrentTab(4)" --}} style="display:none" name="svBTN">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="ClearFld()">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="submit" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<!-- Flag Modal -->
	<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal-flag">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Set Flag</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="flag-form">
						<input type="hidden" name="restriction_grpid" value="">
						<div class="modal-body">
							<div class="border-bottom mb-3">
								<b>Check/Uncheck Employees</b> that are exempted for <b>Timekeeping</b> but still have <b>DTR</b> and <b>Payroll</b>
							</div>
							<div class="form-group">
								<select class="form-control" id="flag_office" name="flag_office">
									<option value="" disabled="" selected="">--Select Office--</option>
									@foreach($office as $ofc)
										<option value="{{$ofc->cc_id}}">{{$ofc->cc_desc}}</option>
									@endforeach
								</select>
							</div>
							<div class="table-responsive">
								<table class="table table-hover table-bordered" id="dataTable-flag" width="100%">
									<thead>
										<tr>
											<td>Id</td>
											<td>Registered Employees</td>
											<td>Position</td>
										</tr>
									</thead>
									<tbody>
										{{-- <tr>
											<td>
												<div class="custom-control custom-checkbox">
													<input type="checkbox" class="custom-control-input" id="asd" value="adasd" name="restrictions[]">
													<label class="custom-control-label" for="asd">Empasd</label>
												</div>
											</td>
											<td></td>
										</tr> --}}
									</tbody>
								</table>
							</div>
						</div>
					</form>
				</div>
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
		var table = $('#dataTable').DataTable(dataTable_config);
		var flag_table = $('#dataTable-flag').DataTable(dataTable_short);
	</script>
	<script type="text/javascript">
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
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
		
		$('#opt-flag').on('click', function() {
			$('#modal-flag').modal('show');
		});

		$('#flag_office').on('change', function() {
			flag_table.clear().draw();
			$.ajax({
				url: '{{url('master-file/employee/office-employee')}}',
				method : 'post',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : $(this).val()},
				success : function(data) {
					console.log(data);
					if (data!="empty" && data!="error") {
						for (var i = 0; i < data.length; i++) {
							d = data[i];
							flag_table.row.add([
								'<div class="custom-control custom-checkbox">'+
									'<input type="checkbox" class="custom-control-input" id="'+d.empid+'" oninput="flagged(this)" '+d.flag+'>'+
									'<label class="custom-control-label" for="'+d.empid+'">'+d.empid+'</label>'+
								'</div>',
								d.empname,
								d.jobtitle
							]).draw();
						}
					}
				},
				error : function() {
					alert("An error occured. Please try again or reload  the page.");
				}
			});
		});

		$('#modal-flag').on('hidden.bs.modal', function (e) {
			$('#flag_office').val('');
			flag_table.clear().draw();
		})

		function row_update(obj) {
			var selected_row = $($(obj).parents()[1]);
			$('#frm-pp').attr('action', '{{url('master-file/employee')}}/update');
			$('.T0r').attr('required', '');
			$('input[name="txt_id"]').attr('readonly', '');
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
						location.href='{{ url('master-file/employee/') }}/edit/' + d.empid;
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

			// $('.AddMode').show();
			// $('.DeleteMode').hide();
			// $('#modal-pp').modal('show');
		};

		function row_delete(obj) {
			var selected_row = $($(obj).parents()[1]);
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
		};
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
	<script type="text/javascript">
		function flagged(e)
		{
			var ckb = $(e).is(':checked');
			$.ajax({
				url : '{{url('master-file/employee/upadte-flag')}}',
				method : 'post',
				data : {_token:$('meta[name="csrf-token"]').attr('content'), id : $(e).attr('id'), state : ckb},
				success : function(data) {
					// console.log(data);
					if (data=="error") {
						alert("An error occured");
					} else {
						if (data=="missing") {
							alert("Missing Parameters. Please Try Again.");
						}
					}
				},
				error : function() {
					alert('An error occured while processing your request. Please reload the page.');
				}
			});
		}
	</script>
@endsection