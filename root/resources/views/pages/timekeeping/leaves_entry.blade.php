@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar"></i> Leaves Entry
			@if(Account::GET_DATA_FROM_CURRENT('grp_id') == '001')
			<button type="button" class="btn btn-success" id="opt-add">
				<i class="fa fa-plus"></i> Add
			</button>
			@endif

			<button type="button" class="btn btn-success" id="opt-apply">
				<i class="fa fa-plane"></i> Apply For Leave
			</button>
			<button type="button" class="btn btn-info" id="opt-print">
				<i class="fa fa-print"></i> Print List
			</button>
			@if(Account::GET_DATA_FROM_CURRENT('grp_id') == '001')
			<button type="button" class="btn btn-warning text-white" id="opt-override">
				<i class="fa fa-edit"></i> Override Remaining Credit
			</button>
			@endif
			<div class="float-right">
				<a href="{{ url('master-file/leave-types') }}" class="btn btn-default btn-sm"><i class="fa fa-cogs"></i></a>
			</div>
		</div>
		<div class="card-body mb-2">
			<div class="card mb-3">
				<div class="card-body" id="print_hide">
					<div class="row">
						<div class="col">
							<form method="post" action="{{url('timekeeping/leaves-entry/find')}}" id="frm-loaddtr">

								<div class="row">
									<div class="col-sm-7">

										<div class="row p-2">
											<div class="col-3">
												Office:
											</div>
											<div class="col">
												<select class="form-control w-100" name="office" id="office" required>
													<option disabled selected value="">Please select an office</option>
													@if(!empty($data[2]))
													@foreach($data[2] as $off)
													<option value="{{$off->cc_id}}">{{$off->cc_desc}}</option>
													@endforeach
													@endif
												</select>
											</div>
												
										</div>

										<div class="row p-2">
											<div class="col-3">
												Employee:
											</div>
											<div class="col">
												<select class="form-control w-100" name="tito_emp" id="tito_emp" required>
													<option disabled selected value="">---</option>
													{{-- @if(!empty($data[1]))
													@foreach($data[1] as $emp)
													<option value="{{$emp->empid}}">{{$emp->firstname." ".$emp->lastname}}</option>
													@endforeach
													@endif --}}
												</select>
											</div>
												
										</div>
									</div>

									<div class="col-sm-5" >
										<div class="row p-2">
											<div class="col-3">
												From:
											</div>
											<div class="col">
												<input type="text" name="date_from" id="date_from" class="form-control" value="{{date('Y-m-01')}}" required>
											</div>
										</div>

										<div class="row p-2">
											<div class="col-3">
												To:
											</div>
											<div class="col">
												<input type="text" name="date_to" id="date_to" class="form-control" value="{{date('Y-m-d')}}" required>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<div class="col-2">
							{{-- <div class="row p-2">
								<div class="col text-right">
									<button type="button" class="btn btn-success" id="opt-add"><i class="fa fa-plus"></i></button>
								</div>
								<div class="col text-left">
									<button type="button" class="btn btn-primary" id="opt-update"><i class="fa fa-edit"></i></button>
								</div>
							</div>
							
							<div class="row p-2">
								<div class="col text-right">
									<button type="button" class="btn btn-danger" id="opt-delete"><i class="fa fa-trash"></i></button>
								</div>
								<div class="col text-left">
									<button type="button" class="btn btn-info" id="opt-print"><i class="fa fa-print"></i></button>
								</div>
							</div>
							<button type="button" class="btn btn-warning" id="opt-money"><i class="fa fa-money"></i></button> --}}
						</div>
					</div>		
				</div>
			</div>

			<div class="card">
				<div class="card-body">
					<div class="row" id="print_show" hidden>
						<div class="col">
							<b>Employee Name: </b>
							<span id="print_emp_name"></span>
						</div>
					</div>
					<div class="row" id="print_show_name" hidden>
						<div class="col">
							<b>Date: </b>
							<span id="print_date"></span>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="dataTable">
									<thead>
										<tr>
											<th>Code</th>
											<th>Employee</th>
											<th>Leave</th>
											<th>Date Filed</th>
											{{-- <th>Date from</th>
											<th>AM</th>
											<th>PM</th>
											<th>Date to</th>
											<th>AM</th>
											<th>PM</th> --}}
											<th>Leave Date</th>
											<th>No of days</th>
											<th>With pay</th>
											{{-- <th>Amount</th> --}}
											<th></th>
										</tr>
									</thead>
									<tbody class="text-center"></tbody>
								</table>
							</div>
						</div>
						<div class="col-4">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th colspan="2">Remaining credits</th>
											<th>Carry Over</th>
										</tr>
									</thead>
									<tbody id="dataTable-leavecount">
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel">Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" id="frm-pp" action="#" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label>Employee:</label>
										<input type="text" class="form-control" name="cbo_employee_txt" id="cbo_employee_txt" readonly required>
										<input type="hidden" class="form-control" name="cbo_employee" id="cbo_employee" readonly>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col">
												<label>Leave Entry Code:</label>
												<input type="text" name="txt_code" id="txt_code" style="text-transform: uppercase;" class="form-control" maxlength="30" readonly>
											</div>
											<div class="col-4">
												<label>Date Filed:</label>
												<input type="text" name="dtp_filed" id="dtp_filed" class="form-control" id="dtp_filed" value="{{date('Y-m-d')}}" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Leave Type:</label>
										<select name="cbo_leave" id="cbo_leave" style="text-transform: uppercase;" class="form-control">
											<option disabled hidden selected value="">---</option>
											@php
												$LeaveTypes = LeaveType::Load_LeaveTypes();
											@endphp
											@if($LeaveTypes != null)
												@foreach($LeaveTypes as $key => $value)
													<option value="{{$value->code}}">{{$value->description}}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="form-group">
										<div class="row">
											{{-- note: please change to col-4 if AM/PM will be reverted --}}
											<div class="col-6">
												<label>Leave Date From:</label>
											</div>

											<div hidden class="col-2">
												<label>AM:</label>
											</div>
											<div hidden class="col-2">
												<label>PM:</label>
											</div>
											<div class="col-6">
												<label>Number of Days:</label>
											</div>
										</div>
										<div class="row">
											<div class="col-6">
												<input type="date" name="dtp_lfrm" id="dtp_lfrm" class="form-control" value="{{date('Y-m-d')}}">
											</div>
											<div hidden class="col-2">
												<input type="checkbox" class="form-control" name="fam" id="fam">
											</div>
											<div hidden class="col-2">
												<input type="checkbox" class="form-control" name="fpm" id="fpm">
											</div>
											<div class="col-6">
												<input type="number" name="txt_no_of_days" id="txt_no_of_days" class="form-control" step=".01" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											{{-- note: please change to col-4 if AM/PM will be reverted --}}
											<div class="col-6">
												<label>Leave Date To:</label>
											</div>
											<div hidden class="col-2">
												<label>AM:</label>
											</div>
											<div hidden class="col-2">
												<label>PM:</label>
											</div>
											<div class="col-6">
												<label>Leave with pay?</label>
											</div>
										</div>
										<div class="row">
											<div class="col-6">
												<input type="date" name="dtp_lto" id="dtp_lto" class="form-control" value="{{date('Y-m-d')}}">
											</div>
											<div hidden class="col-2">
												<input type="checkbox" class="form-control" name="tam" id="tam">
											</div>
											<div hidden class="col-2">
												<input type="checkbox" class="form-control" name="tpm" id="tpm">
											</div>
											<div class="col-6">
												<select name="cbo_leave_pay" id="cbo_leave_pay" style="text-transform: uppercase;" class="form-control" {{-- onchange="leavewithpay(this)" --}}>
													<option value="NO" >No</option>
													<option value="YES" selected>Yes</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col">
												<label>Reason for Leave:</label>
												<textarea class="form-control" id="txt_reason" name="txt_reason" rows="3"></textarea>
												{{-- <div class="row">
													<div class="col-4">
												
													</div>
													<div class="col">
														<div class="input-group">
														    <div class="input-group-prepend">
														        <div class="input-group-text">{!!Core::currSign()!!}</div>
														    </div>
														    <input type="number" class="form-control" style="text-align: right;" name="txt_amount" id="txt_amount" value="0.00" readonly>
														</div>
													</div>
												</div> --}}
											</div>
										</div>
									</div>
								</div>
							</div>
						</span>
						<span class="DeleteMode">
							<p>Are you sure you want to delete <strong><span id="TOBEDELETED" style="color:red"></span></strong> from Leave Entry list?</p>
							<input type="hidden" name="noofdays">
							<input type="hidden" name="type">
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" form="frm-pp" class="btn btn-success">Save</button>
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


	<div class="modal fade" id="override-pp" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<form method="POST" id="override-submit">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Override Remaining Credits</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th colspan="2">Remaining credits</th>
										<th>Carry Over</th>
									</tr>
								</thead>
								<tbody id="dataTable-leavecount-override">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

@section('to-bottom')
	<style>
		@media print {
			* {
				
			}

			#Header, #Footer { display: none ! important; }	
			#sidebar-parent {
				display: none;
			}

			#print_hide, #print_name_hide, .card-header, #dataTable_info, .pagination, .dataTables_empty, #dataTable_filter, #opt-update, #opt-delete {
				display: none !important;
			}

			#print_show, #print_show_name {
				display: block !important;
			}

			.card {
				border: none !important;
			}
		}
	</style>
	
	<script type="text/javascript">
		var selected_row = null;
		$('#dataTable').on('click', 'tbody > tr', function() {
			$(this).parents('tbody').find('.table-active').removeClass('table-active');
			selected_row = $(this);
			$(this).toggleClass('table-active');
		});
	</script>

	<script type="text/javascript">
		$('#date_from').datepicker(date_option5);
		$('#date_to').datepicker(date_option5);
		// $('#dtp_filed').datepicker(date_option);
		$('#dtp_lfrm').datepicker();
		$('#dtp_lto').datepicker();
		var table = $('#dataTable').DataTable(dataTable_short);
	</script>

	<script>
		// function leavewithpay(dom) {
		// 	var txt_amount = $('#txt_amount');
		// 	switch(dom.value) {
		// 		case "NO": 
		// 			txt_amount.val( "0.00");
		// 			txt_amount.attr('readonly', true);
		// 			break;	
		// 		case "YES":
		// 			txt_amount.attr('readonly', false);
		// 			break;	
		// 	}
		// }

		$('#office').on('change', function() {

			while($('#tito_emp')[0].firstChild) {
				$('#tito_emp')[0].removeChild($('#tito_emp')[0].firstChild);
			}

			var hiddenChild = document.createElement('option');
				hiddenChild.setAttribute('selected', '');
				hiddenChild.setAttribute('disabled', '');
				hiddenChild.setAttribute('value', '');
				hiddenChild.innerText='---';

			$('#tito_emp')[0].appendChild(hiddenChild);

			$.ajax({
				type: 'post',
				url: '{{url('timekeeping/timelog-entry/find-emp-office')}}',
				data: {ofc_id: $(this).val()},
				success: function(data) {
					// console.log(typeof(data));
					if(data.length > 0) {
						for(i=0; i<data.length; i++) {
							var option = document.createElement('option');
								option.setAttribute('value', data[i].empid);
								option.innerText=data[i].name;

							$('#tito_emp')[0].appendChild(option);
						}
					}
				},
			});
		});
	</script>

	<script type="text/javascript">
		function LoadTable(data)
		{
			// var fa="", fp="", ta="", tp="";
			var f = "", t = "";
			// var type= "";
			// $.ajax({
			// 	url: "{{asset('timekeeping/leaves-entry/getType')}}",
			// 	method: 'POST',
			// 	data: {data: data.leave_type},
			// 	dataTy: 'json',
			// 	success : function(data) {
			// 		// return JSON.parse(data);
			// 		// type = data;
			// 		// console.log(JSON.parse(data));
			// 	}
			// });
			// alert(type);
			// if(data.frm_am == "True") fa = '<i class="fa fa-check" aria-hidden="true"></i>';
			// if(data.frm_pm == "True") fp = '<i class="fa fa-check" aria-hidden="true"></i>';
			// if(data.to_am == "True") ta = '<i class="fa fa-check" aria-hidden="true"></i>';
			// if(data.to_pm == "True") tp = '<i class="fa fa-check" aria-hidden="true"></i>';
			if(data.frm_am == "True" && data.frm_pm != "True") f = "AM";
			if(data.frm_pm != "True" && data.frm_pm == "True") f = "PM";
			if(data.to_am == "True" && data.frm_pm != "True") t = "AM";
			if(data.to_pm != "True" && data.frm_pm == "True") t = "PM";
			table.row.add([
				data.lvcode,
				'<span empname="'+data.empid+'">'+data.emp_name+'</span>',
				'<span lloyd="'+data.lloyd+'">'+data.leave_desc+'</span>',
				data.d_filed,
				// data.leave_from,
				// fa,
				// fp,
				// data.leave_to,
				// ta,
				// tp,
				data.leave_from+" "+f+" to "+data.leave_to+" "+t,
				data.no_of_days,
				data.leave_pay,
				// data.leave_amount
				// '<button type="button" class="btn btn-primary" id="opt-update" onclick="row_update(this)">'+
				// '	<i class="fa fa-edit"></i>'+
				// '</button>'+
				'<button type="button" class="btn btn-danger" leavetype="'+data.leave_type+'" days="'+data.no_of_days+'" id="opt-delete" onclick="row_delete(this)">'+
				'	<i class="fa fa-trash"></i>'+
				'</button',

			]).draw();
		}

		function UpdateLeaveCount(data = null, toWhatDom = '#dataTable-leavecount')
		{
			var lct = toWhatDom, isDefault = (toWhatDom == '#dataTable-leavecount');
			$(lct).empty();
			if (data==null) {
				@php
					$LeaveTypes = LeaveType::Load_LeaveTypes();
				@endphp
				@if($LeaveTypes != null)
					@foreach($LeaveTypes as $key => $value)
						a = '<tr id="td-{{$value->code}}">'+
							'<td>{{$value->description}}</td>'+
							'<td>0</td>'+
							'<td>{{$value->carry_over}}</td>'+
						'</tr>'
						$(lct).append(a);
					@endforeach
				@endif
			} else {
				if (data.length==0) {
					@php
					$LeaveTypes = LeaveType::Load_LeaveTypes();
					@endphp
					@if($LeaveTypes != null)
						@foreach($LeaveTypes as $key => $value)
							a = '<tr id="td-{{$value->code}}">'+
								'<td>{{$value->description}}</td>'+
								'<td>{{$value->leave_limit}}</td>'+
								'<td>{{$value->carry_over}}</td>'+
							'</tr>'
							$(lct).append(a);
						@endforeach
					@endif
				} else {
					for (var i = 0; i < data.length; i++) {
						d = data[i];
						a = '<tr id="td-'+d.code+'">'+
							'<td>'+d.description+'</td>'+
							'<td>'+(isDefault ? (parseFloat(d.peak)-parseFloat(d.count)) : '<input step="any" required class="form-control" name="'+d.code+'" type="number" value="'+(parseFloat(d.peak)-parseFloat(d.count))+'">')+'</td>'+
							'<td>'+d.carry_over+'</td>'+
						'</tr>'
						$(lct).append(a);
					}
				}
			}
		} UpdateLeaveCount();

		function ValidateSearchFrm()
		{
			if ($('#date_from').val() != "" && $('#date_from') != null && $('#date_to').val() != "" && $('#date_to') != null) {
				if ($('#tito_emp').val() != null) {
					if ($('#tito_emp').val() != "") {
						return true;
					} else {
						return false;
					}
				} else {
					alert("Please select an employee.");
					return false;
				}
			} else {
				alert("No date selected.");
			}
		}

		function SubmitSearchFrm(e,fromDom = '#dataTable-leavecount')
		{
			e.preventDefault();
			var frm = $('#frm-loaddtr');
			$('#frm-spinner').show();
			$.ajax({
				type : frm.attr('method'),
				url : frm.attr('action'),
				data : frm.serialize(),
				dataTy : 'json',
				success : function(data) {
					if (data!="error") {
						if (data[0]!="No record found.") {
							table.clear().draw();
							var d = data[0];
							for(var i = 0 ; i < d.length; i++) {
								LoadTable(d[i]);
							}
						} else {
							table.clear().draw();
						}
						UpdateLeaveCount(data[1],fromDom);
					} else {
						alert('Error on loading data.');
					}
					$('#frm-spinner').hide();
				}
			});
		}

		function GetNOD()
		{
			var fam = ($('#fam').prop('checked')==true) ? 0.5 : 0;
			var fpm = ($('#fpm').prop('checked')==true) ? 0.5 : 0;
			var tam = ($('#tam').prop('checked')==true) ? 0.5 : 0;
			var tpm = ($('#tpm').prop('checked')==true) ? 0.5 : 0;
			var f = 0;
			var t = 0;
			// if (fam == fpm) {f = 0;} else {f = fam + fpm}
			// if (tam == tpm) {t = 0;} else {t = tam + tpm}

			if (fam == tam) {f = 0.5;}
			if (fpm == tpm) {f = 0.5;}
			if (fam == fpm && tam == tpm) {f = 0; t = 0;}

			var date1 = $('#dtp_lfrm').val();
			var date2 = $('#dtp_lto').val(); /*alert(date2);*/

			var nod = Math.round(GetDateDiff(date1, date2), 2);
			nod = nod - f - t;
			$('#txt_no_of_days').val(nod);
		}

		function OpenModal(id)
		{
			$('.AddMode').hide();
			$('.DeleteMode').hide();

			$(id).show();
			$('#modal-pp').modal('show');
		}

		function FillFld(data){ console.log(data);
			$('#cbo_employee_txt').val(data.name);
			$('#cbo_employee').val(data.empid);
			$('#txt_code').attr('readonly', true);
			$('#txt_code').val(data.lvcode);
			$('#dtp_filed').val(data.d_filed);
			$('#dtp_lfrm').val(data.leave_from);
			$('#fam').prop('checked', (data.frm_am.toLowerCase() == "true") ? true : false);
			$('#fpm').prop('checked', (data.frm_pm.toLowerCase() == "true") ? true : false);
			$('#dtp_lto').val(data.leave_to);
			$('#tam').prop('checked', (data.to_am.toLowerCase() == "true") ? true : false);
			$('#tpm').prop('checked', (data.to_pm.toLowerCase() == "true") ? true : false);
			$('#txt_no_of_days').val(data.no_of_days);
			$('#cbo_leave_pay').val((data.leave_pay == "YES") ? 'YES' : 'NO').trigger('change');
			$('#txt_amount').val(data.leave_amount);
			$('#cbo_leave').val(data.leave_type).trigger('change');
			$('#txt_reason').text(data.leave_reason);
		}

		function ClearFld() {
			$('#btn-print').attr('data', '#');

			$('#cbo_employee_txt').val('');
			$('#cbo_employee').val('');
			$('#txt_code').removeAttr('readonly');
			$('#txt_code').val('');
			$('#dtp_filed').val('');
			$('#dtp_lfrm').val('');
			$('#fam').prop('checked', false);
			$('#fpm').prop('checked', false);
			$('#dtp_lto').val('');
			$('#tam').prop('checked', false);
			$('#tpm').prop('checked', false);
			$('#txt_no_of_days').val(0);
			$('#cbo_leave_pay').val('NO').trigger('change');
			$('#txt_amount').val('0.00');
			$('#cbo_leave').val('').trigger('change');
			$('#txt_reason').text('');
		}

		$("#modal-pp").on("hidden.bs.modal", function () {
			ClearFld();
		});

		$('#date_from, #date_to, #tito_emp').on('change', function(e) {
			if (ValidateSearchFrm() == true) {SubmitSearchFrm(e);}
			$('#print_emp_name').html($('#tito_emp option:selected').text());
			$('#print_date').html($('#date_from').val()+" to "+$('#date_to').val());
		});

		$('#opt-add').on('click', function() {
			if (ValidateSearchFrm()) {
				processModalForEntry();
			}
		});
		$('#opt-apply').on('click', function() {
			if (ValidateSearchFrm()) {
				processModalForEntry('apply');
			}
		});

		function processModalForEntry(mode = 'new'){
			$.ajax({
				type : 'get',
				url : '{{url('timekeeping/leaves-entry/new-entry-code')}}',
				dataTy: 'json',
				success : function(data){
					$('#txt_code').val(data);
				}
			});
			$('#ModalLabel').text("New Leave Entry");
			// $("#cbo_leave").attr('required',true);
			$('#frm-pp').attr('action', '{{url('timekeeping/leaves-entry?mode=')}}'+mode);
			$('#cbo_employee_txt').val($('#tito_emp option:selected').text());
			$('#cbo_employee').val($('#tito_emp').val());
			$('#dtp_filed, #dtp_lfrm, #dtp_lto').val('{{date('Y-m-d')}}');
			GetNOD();
			OpenModal('.AddMode');
		}

		$('#opt-override').click(function(event) {
			if(ValidateSearchFrm()){
				SubmitSearchFrm(event,'#dataTable-leavecount-override');
				$('#override-pp').modal('show');
			}
		});


		// $('#opt-update').on('click', function() {
		function row_update(obj) {
			selected_row = $($(obj).parents()[1]);
			if (ValidateSearchFrm()) {
				if (selected_row!=null) {
					$.ajax({
						type : 'get',
						url : '{{url('timekeeping/leaves-entry/get-entry')}}',
						data : {code: selected_row.children()[0].innerText},
						dataTy : 'json',
						success : function(data) {
							if (data!="error") {
								if (data!="No record found.") {
									// $('#frm-pp').attr('action', '{{url('timekeeping/leaves-entry/update')}}');
									$('#frm-pp').attr('action', '{{url('timekeeping/leaves-entry?mode=update')}}');
									FillFld(JSON.parse(data));
								} else {
									table.clear().draw();
									alert(data);
								}
							} else {
								alert('Error on loading data.');
							}
							$('#frm-spinner').hide();
						}
					});
					$('#ModalLabel').text("Edit Leave Entry");
					$('#frm-pp').attr('action', '#');
					$('#cbo_employee_txt').val($('#tito_emp option:selected').text());
					$('#cbo_employee').val($('#tito_emp').val());
					OpenModal('.AddMode');
				} else {
					NoSelectedRow();
				}
			}
		// });
		}

		// $('#opt-delete').on('click', function() {
		function row_delete(obj) {
			selected_row = $($(obj).parents()[1]);
			if (ValidateSearchFrm()) {
				if (selected_row!=null) {
					$('[name=noofdays]').val($(obj).attr('days'));
					$('[name=type]').val($(obj).attr('leavetype'));
					$('#ModalLabel').text("Delete Leave Entry");
					// $("#cbo_leave").removeAttr('required');
					$('#txt_code').val(selected_row.children()[0].innerText);
					$('#frm-pp').attr('action', '{{url('timekeeping/leaves-entry/delete')}}');
					OpenModal('.DeleteMode');
				} else {
					NoSelectedRow();
				}
			}
		// });
		}

		$('#dtp_lfrm, #dtp_lto').on('change', function() {
			GetNOD();
		});

		$('#fam, #fpm, #tam, #tpm').on('click', function() {
			GetNOD();
		});

		$("#override-submit").submit(function(event) {
			event.preventDefault();
			let thisForm = $(this).serialize()+'&cbo_employee='+$('#tito_emp').val();
			$.ajax({
				method: 'POST',
				url:'{{url('timekeeping/leaves-entry?mode=override')}}',
				data: thisForm
			})
			alert('Updated Successfully');
			SubmitSearchFrm(event);
		});
	</script>
	<script type="text/javascript">
		function PrintPage(page_location) {
			$("<iframe>")
	        .hide()
	        .attr("src", page_location)
	        .appendTo("body");   
		}

		$('#opt-print').on('click', function() {
			if($('#tito_emp').val() == null) alert('Please select an employee.');
			else window.print();
		});
	</script>
@endsection