@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-fw fa-wrench"></i> Group Right Settings
		</div>
		<div class="card-body">
			<div class="card mb-3">
				<div class="card-body">
					<div class="form-inline">
						<div class="form-inline">
							<div class="form-group">
								<a href="#" class="btn btn-primary mr-2" id="btn-add"><i class="fa fa-plus"></i> Add New</a>
							</div>
						</div>
						@if($data['nogr'] != 0)
							<form method="post" action="{{url('settings/group-rights/add-rights')}}">{{csrf_field()}}<button type="submit" class="btn btn-danger"><i class="fa fa-exclamation"></i> There are new User Groups.Click to integrate rules</button></form>
						@endif
					</div>
				</div>
			</div>

			<div class="table-responsive" id="div-table">
				<table class="table table-bordered table-hover" id="dataTable">
					<col width="10%">
					<col>
					<col width="10%">
					<thead>
						<tr>
							<th>Group ID</th>
							<th>Group Description</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(count($data[1])!=0)
							@foreach($data[1] as $row)
								<tr>
									<td>{{$row->grp_id}}</td>
									<td>{{$row->grp_desc}}</td>
									<td>
										<button class="btn btn-warning btn-edit exclusive-edit-btn" data="{{$row->grp_id}}" restr="{{X07::GetGroup($row->grp_id)->restrictions}}"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger exclusive-delete-btn" data="{{$row->grp_desc}}" restid="{{$row->grp_id}}"><i class="fa fa-close"></i></button>
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>

			<div class="hidden" id="div-info">
				<div class="card mb-3">
					<div class="card-body">
						Group Description
						<div class="float-right">
							<button class="btn btn-danger" id="btn-back"><i class="fa fa-arrow-left"></i> Back</button>
						</div>
					</div>
				</div>
				<div class="table-responsive" id="div-info">
					<table class="table table-bordered table-hover" id="dataTable1">
						<col width="10%">
						<col width="30%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<thead>
							<tr>
								<th>Module ID</th>
								<th>Module Description</th>
								<th>Allow</th>
								{{-- <th>Add</th>
								<th>Update</th>
								<th>Delete</th>
								<th>Print</th>
								<th>Action</th> --}}
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
@endsection

@section('to-modal')
	<div class="modal fade" id="md-grp" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel111"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="" id="add-frm">
						<span class="AddMode">
							<div class="form-group">
								<label>Group Description:</label>
								<input type="text" name="txt_grp" class="form-control" placeholder="ACCOUNTING" style="text-transform: uppercase;">
							</div>
						</span>
						<span class="DeleteMode">
							<input type="hidden" name="hidden_txt_id">
							Are you sure you want to delete <b><span class="text-danger" id="del-msg"></span></b>?
						</span>
					</form>
				</div>
				<div class="modal-footer">
					<span class="AddMode">
						<button type="button" class="btn btn-success" id="btn-add-sub">Add</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</span>
					<span class="DeleteMode">
						<button type="button" class="btn btn-danger" id="btn-del-sub">Delete</button>
						<button type="button" class="btn" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Restriction Modal -->
	<div class="modal fade" id="modal-pp-r" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel11" aria-hidden="true">
		<div class="modal-dialog" role="document" style="background-color: black; width: 20vw;">
			<div class="modal-content" style="background-color: rgb(248,248,248);">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Restrictions</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="edit-restr-form">
					<input type="hidden" name="restriction_grpid" value="">
					<div class="modal-body">
						<span class="bg-info pl-2 pr-2 text-white">
							Uncheck module/s to restrict
						</span>
						@isset($data[2])
							@foreach($data[2] as $k => $v)
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="{{$k}}" value="{{$v->id}}" name="restrictions[]">
									<label class="custom-control-label" for="{{$k}}">{{$v->mod_name}}</label>
								</div>
							@endforeach
						@endisset
					</div>
					<div class="modal-footer">
						<span>
							<button type="button" class="btn btn-success" id="restrictions-save-btn">Save</button>
						</span>
						<span>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</span>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript">
		var table = $('#dataTable').DataTable(dataTable_config3);
		var table1 = $('#dataTable1').DataTable(dataTable_config3);
	</script>
	<script type="text/javascript">
		$('#btn-add').on('click', function() {
			$('#exampleModalLabel111').text("Add new");
			$('input[name="txt_grp"]').val('');

			$('.AddMode').show();
			$('.DeleteMode').hide();

			$('#md-grp').modal('show');
		});

		$('#btn-add-sub').on('click', function() {
			$.ajax({
				type: 'post',
				url: '{{url('settings/group-rights/add-rights-new')}}',
				data: $('#add-frm').serialize(),
				success: function(data) {

				},
			});
		});

		$('.exclusive-delete-btn').on('click', function() {
			$('#del-msg').text($(this).attr('data'));
			$('#exampleModalLabel111').text('Delete');
			$('input[name="hidden_txt_id"]').val($(this).attr('restid'));

			$('.AddMode').hide();
			$('.DeleteMode').show();

			$('#md-grp').modal('show');
		});

		$('#btn-del-sub').on('click', function() {
			$('#preloader').show();
			$.ajax({
				type: 'post',
				url: '{{url('settings/group-rights/delete-rights-new')}}',
				data: $('#add-frm').serialize(),
				success: function(data) {
					if(data == "okay" || data == "1" || data == 1) {
						window.location = "{{url('settings/group-rights')}}";
					} else {
						alert(data);
					}
				}
			});

			setTimeout(function() {
				$('#preloader').hide();
			}, 600);
		});

		$('#restrictions-save-btn').on('click', function() {
			$('#preloader').show();
			$.ajax({
				type: 'post',
				url: '{{url('settings/group-rights/edit-rights')}}',
				data: $('#edit-restr-form').serialize(),
				success: function(data) {
					if(data == "okay" || data == "1" || data == 1) {
						window.location = "{{url('settings/group-rights')}}";
					} else {
						alert(data);
					}
				},
			});
		});

		$('.exclusive-edit-btn').on('click', function() {
			$('input[name="restriction_grpid"]').val($(this).attr('data'));

			var that = this;

			setTimeout(function() {
				@foreach($data[2] as $k => $v)
					// console.log($('#'+'{{$k}}')[0]);
					$('#'+'{{$k}}')[0].removeAttribute('checked');
					$('#'+'{{$k}}')[0].checked = false;
				@endforeach
			}, 50);
				
			setTimeout(function() {
				@foreach($data[2] as $k => $v)
					if($(that).attr('restr').split(', ').includes('{{$v->id}}')) {
					// console.log('{{$k}} '+' {{$v->mod_name}}');
						$('#'+'{{$k}}')[0].setAttribute('checked', '');
						$('#'+'{{$k}}')[0].checked = true;
					}
				@endforeach
			}, 100);
			
			$('#modal-pp-r').modal('show');
		});

		// $('.btn-edit').on('click', function(){
		// 	showTable1();

		// 	$.ajax({
		// 		type : 'get',
		// 		url : '{{url('settings/group-rights/info')}}',
		// 		data : {id : $(this).attr('data')},
		// 		dataTy : 'json',
		// 		success : function(data) {
		// 			console.log(data);
		// 			// LoadTable(data);
		// 		}
		// 	});
		// });

		// function LoadTable(data)
		// {
		// 	table1.row.add([
		// 		'id',
		// 		'desc',
		// 		'<input type="checkbox" class="form-control checkbox-solo" name="c_allow[]">',
		// 		'<input type="checkbox" class="form-control checkbox-solo" name="c_add[]">',
		// 		'<input type="checkbox" class="form-control checkbox-solo" name="c_update[]">',
		// 		'<input type="checkbox" class="form-control checkbox-solo" name="c_delete[]">',
		// 		'<input type="checkbox" class="form-control checkbox-solo" name="c_print[]">',
		// 		'<button class="btn btn-warning" data-toggle="modal" data-target="#md-grp"><i class="fa fa-pencil"></i></button>'+
		// 		'<button class="btn btn-danger" data-toggle="modal" data-target="#md-grp"><i class="fa fa-close"></i></button>',
		// 	]);
		// }
	</script>
	<script type="text/javascript">
		$('#btn-back').on('click', function()
		{
			showTable();
			table1.clear().draw();
		});
	</script>
	<script type="text/javascript">
		function showTable()
		{
			$('#div-info').addClass('hidden');
			$('#div-table').removeClass('hidden');
		}

		function showTable1()
		{
			$('#div-info').removeClass('hidden');
			$('#div-table').addClass('hidden');
		}
	</script>
@endsection