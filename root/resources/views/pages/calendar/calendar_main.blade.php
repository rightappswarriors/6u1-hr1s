@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-calendar" aria-hidden="true"></i> Calendar
		</div>
		<div class="card-body">
			{{-- <div id="top">
				<select id="locale-selector"></select>
			</div> --}}
			<div class="row">
				<div class="col-sm-2">
					<div class="card mb-4 p-3">
						<div class="row p-3">
							<div class="col-sm-12 mb-5"><b onclick="deleted_beta()">Legend:</b></div>
							<div class="col-sm-12 bg-primary text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded">Date Today</div>
							<div class="col-sm-12 bg-success text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="RH">Regular Holiday</div>
							<div class="col-sm-12 bg-danger text-white mb-1 ml-1 mb-5 w-25 p-3 text-center rounded" id="SH">Special Holiday</div>
						</div>
					</div>
				</div>
				<div class="col-sm-10">
					<div id="OEI-scheduler"></div>
				</div>
			</div>
					{{-- <div class="card mb-4 p-2">
						<div class="row p-2">
							<div class="col-sm-1"><b>Legend:</b></div>
							<div class="col-sm-2 bg-success text-white mb-1 ml-1 w-25 text-center" id="sph">Special Holiday</div>
							<div class="col-sm-2 bg-primary text-white mb-1 ml-1 w-25 text-center">Regular Holiday</div>
							<div class="col-sm-2 bg-danger text-white mb-1 ml-1 w-25 text-center">Date Today</div>
						</div>
					</div> --}}
			{{-- <div id="OEI-scheduler"></div> --}}
		</div>
	</div>
@endsection

@section('to-modal')
	<!-- Add Modal -->
	<div class="modal fade" id="modal-pp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" action="#" id="frm-pp" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Date: <red>*</red></label>
										<input type="text" name="txt_date" id="txt_date" class="form-control" required readonly>
									</div>
									<div class="form-group">
										<label>Description: <red>*</red></label>
										<input type="text" name="txt_desc" class="form-control" maxlength="20" required>
									</div>
									<div class="form-group">
										<label>Holiday Type: <red>*</red></label>
										{{-- <input type="text" name="txt_type" class="form-control" maxlength="2" style="text-transform: uppercase;" required placeholder="XX"> --}}
										<select name="txt_type" class="form-control" maxlength="2" style="text-transform: uppercase;" required>
											<option value="" selected hidden disabled></option>
											<option value="RH">Regular Holiday</option>
											<option value="SH">Special Holiday</option>
										</select>
									</div>
									<div class="form-group hidden">
										<label>ID:</label>
										<input type="hidden" name="txt_id" class="form-control" maxlength="2" style="text-transform: uppercase;" required placeholder="XX">
									</div>
								</div>
							</div>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" name="delBtn" id="delBtn" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="submit" name="subBtn" form="frm-pp" class="btn btn-success">Save</button>
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

	<!-- Delete Modal -->
	<div class="modal fade" id="modal-ppp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="delete-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h5 class="modal-title" id="delete-body-msg"></h5>
					<form method="post" action="{{url('calendar/')}}/delete" data="#">
						@csrf
						<span class="AddMode">
							<div class="row">
								<div class="col"> <!-- Column 1 -->
									<div class="form-group">
										<label>Date: </label>
										<input type="hidden" name="txt_id" id="txt_id" class="form-control" required readonly>
									</div>
								</div>
							</div>
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="submit" name="subBtn" form="frm-pp" class="btn btn-danger">Delete</button>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="ClearFld()">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Deleted List Modal -->
	<div class="modal fade" id="modal-pppp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Deleted Holidays</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<h5 class="modal-title" id="delete-body-msg"></h5>
					<form method="post" action="{{url('calendar/')}}/delete" data="#">
						@csrf
						<div class="table-responsive">
							<table class="table table-hover" id="dataTable">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date</th>
										<th>Options</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('to-bottom')
	<script>
		var table = $('#dataTable').DataTable();
		function LoadTable(data)
		{
			table.row.add([
				data.description,
				data.date_holiday,
				'<button class="btn btn-success w-100" type="button" onclick="restore_alpha('+data.id+')">Restore</button>',
			]).draw();
		}

		function deleted_beta() {
			$.ajax({
				type : "get",
				url : "{{url('calendar/')}}/get",
				dataTy : 'json',
				success : function(data) {
					if (data!="" || data!=null) {
						table.clear().draw();
						for(var i = 0 ; i < data.length; i++) {
							LoadTable(data[i]);
						}
					} else {
						alert('Error on loading data.');
					}
				}
			});

			// uncomment to enable restoration of holidays
			$('#modal-pppp').modal('toggle');
		}

		function restore_alpha(_id) {
			$.ajax({
				type : "get",
				url : "{{url('calendar/')}}/restore/"+_id,
				success: function(response) {
					location.reload();
				}
			});
		}

		function deleted_alpha(_id) {
			$.ajax({
				type : "get",
				url : "{{url('calendar/')}}/deleteA/"+_id,
				success: function(response) {
					alert(response);
				}
			});
		}
	</script>

	<script>
		$('#SH').draggable({
			revert: true,
			revertDuration: 0,
		});

		$('#RH').draggable({
			revert: true,
			revertDuration: 0,
		});

		$('#OEI-scheduler').fullCalendar({
		    themeSystem: 'bootstrap4',
		    height: 'auto',
		    selectable: true,
		    selectHelper: false,
		    aspectRatio: 1.7,
		    defaultDate: new Date,
		    navLinks: true,
		    editable: false,
		    eventLimit: false,
		    showNonCurrentDates: false,
		    header: {
		        left: 'prevYear,prev,next,nextYear today',
		        center: 'title',
		        right: 'agendaDay,month,listMonth',
		    },

		    select: function(start, end, allDay) {
		    	// alert('You can drag the blocks in the `Legend` section to add holidays.');
		        var selectionStart = moment(start);
		        var today = moment().subtract(1, 'days'); // passing moment nothing defaults to today
		        // if (selectionStart < today) {
		        //     alert('Cannot add on past dates');
		        // } else {

		        	//uncomment this 
		            $('#modal-pp').modal('toggle');
		            $('#delBtn').attr('hidden', '');
		            $('#exampleModalLabel').text('Add Holiday');
		            $('#frm-pp').attr('action', '{{url('calendar/')}}');
   		            $('input[name="txt_date"]').val('');
   		            $('input[name="txt_date"]').attr('readonly', '');
   		            $('#txt_date').datepicker("disable");
			    	$('input[name="txt_desc"]').val('');
			    	$('select[name="txt_type"]').val('').trigger('change');

		            $('.AddMode').show();
					$('.DeleteMode').hide();
					$('#modal-pp').modal('show');
		            $('#txt_date').val(start.format());


		            // $('#OEIScheduler-preset').modal('toggle');
		            // $('#oeisp_date1').val($.fullCalendar.formatDate(start, 'M-D-Y'));
		            // $('#oeisp_date2').val(start.format());
		        // }
		        // console.log("selected:"+selectionStart+"|today:"+today);
		    },

		    events: [
		    	{
		    		id: 'today',
		    		title: 'Today',
		    		start: '{{date('Y-m-d')}}',
		    		color: '#08f',
		    	},
		    	@foreach($data as $k => $v)
		    		{
		    			id: '{{$v->id}}*{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[1]}}',
		    			title: '{{$v->description}}', 
		    			start: '{{$v->date_holiday}}',
		    			color: '{{explode("^", Holiday::Get_Holiday_Color($v->holiday_type))[0]}}',
		    		},
		    	@endforeach
		    ], 

		    eventClick: function(info) {
		    	if(info.id == 'today') {

		    	} else {
			    	$('#exampleModalLabel').text('Edit Holiday');
			    	$('#delBtn').removeAttr('hidden', '');

			    	$('#frm-pp').attr('action', '{{url('calendar/')}}/update');

			    	$('input[name="txt_date"]').val(info.start._i);
			    	$('input[name="txt_date"]').removeAttr('readonly', '');
			    	$('#txt_date').datepicker(date_option3);
			    	$('input[name="txt_desc"]').val(info.title);
			    	$('select[name="txt_type"]').val(info.id.split("*")[1]).trigger('change');
			    	$('input[name="txt_id"]').val(info.id);

			    	$('#modal-pp').modal('toggle');
		            $('.AddMode').show();
					$('.DeleteMode').hide();
					$('#modal-pp').modal('show');
		    	}	
		    },

		    droppable: true,
		    drop: function(date, resource) {
		    	// console.log(resource.target.id);
		    	// alert(date.format());
		    	$('#modal-pp').modal('toggle');
		    	$('#delBtn').attr('hidden', '');
	            $('#exampleModalLabel').text('Add Holiday');
	            $('#frm-pp').attr('action', '{{url('calendar/')}}');
	            $('#txt_date').datepicker("disable");
	            $('input[name="txt_date"]').val('');
		    	$('input[name="txt_desc"]').val('');
		    	$('select[name="txt_type"]').val(resource.target.id).trigger('change');
		    	$('.AddMode').show();
				$('.DeleteMode').hide();
				$('#modal-pp').modal('show');
	            $('#txt_date').val(date.format());
		    },
		});
			
	</script>

	<script>
		document.createElement('red');
	</script>

	<style>
		#top {
			background: #eee;
			border-bottom: 1px solid #ddd;
			padding: 0 10px;
			line-height: 40px;
			font-size: 12px;
		}

		/*#calendar {
			max-width: 900px;
			margin: 40px auto;
			padding: 0 10px;
		}*/

		#delBtn {
			position: absolute;
			left: 10px;
		}

		div.fc-content {
			cursor: pointer;
		}

		red {
			color: red;
		}
	</style>
@endsection
