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
								<a href="#" class="btn btn-primary mr-2"><i class="fa fa-plus"></i> Add New</a>
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
										<button class="btn btn-warning btn-edit" data="{{$row->grp_id}}"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger" data-toggle="modal" data-target="#md-grp"><i class="fa fa-close"></i></button>
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
								<th>Add</th>
								<th>Update</th>
								<th>Delete</th>
								<th>Print</th>
								<th>Action</th>
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
					<h5 class="modal-title" id="exampleModalLabel">Group Info</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
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
		$('.btn-edit').on('click', function(){
			showTable1();

			$.ajax({
				type : 'get',
				url : '{{url('settings/group-rights/info')}}',
				data : {id : $(this).attr('data')},
				dataTy : 'json',
				success : function(data) {
					console.log(data);
					LoadTable(data);
				}
			});
		});

		function LoadTable(data)
		{
			table1.row.add([
				'id',
				'desc',
				'<input type="checkbox" class="form-control checkbox-solo" name="c_allow[]">',
				'<input type="checkbox" class="form-control checkbox-solo" name="c_add[]">',
				'<input type="checkbox" class="form-control checkbox-solo" name="c_update[]">',
				'<input type="checkbox" class="form-control checkbox-solo" name="c_delete[]">',
				'<input type="checkbox" class="form-control checkbox-solo" name="c_print[]">',
				'<button class="btn btn-warning" data-toggle="modal" data-target="#md-grp"><i class="fa fa-pencil"></i></button>'+
				'<button class="btn btn-danger" data-toggle="modal" data-target="#md-grp"><i class="fa fa-close"></i></button>',
			]);
		}
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