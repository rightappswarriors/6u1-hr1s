@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> HDMF(Pag-ibig) Contributions Summary
		</div>
		<div class="card-body">
			<form method="post" action="{{url('reports/sss/find-pagibig')}}" id="frm-gp">
				<div class="container">
					<div class="form-group">
						<div class="form-inline">
							<select class="form-control mr-2" id="ofc" name="ofc">
								<option value="" selected="" disabled="">-Select office to generate-</option>
								@foreach($data[0] as $office)
								<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
								@endforeach
							</select>
						<button class="btn btn-primary">Print</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<thead>
						<tr>
							<th>Name of Employer</th>
							<th>Employer SSS No.</th>
							<th>Agency</th>
							<th>Branch Code</th>
							<th>Region Code</th>
							<th>Name Of Employees</th>
							<th>Contributions</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection


@section('to-bottom')
	<script>
		var table = $('#dataTable').DataTable({
			"paging": false
		});

		// onchange jquery script for <select id="office">
		$('#ofc').on('change', function() {
			var ofc_id = $('#ofc :selected').val();
			var data = { 
                          _token : $('meta[name="csrf-token"]').attr('content'),
                          ofc_id : $('#ofc :selected').val(),
                       };
			$.ajax({
				type: "post",
				url: "{{url('reports/pagibig/find-pagibig')}}",
				data: data,
				success: function(data) {
					for(i=0; i<data.length; i++){
						var sum = parseFloat(data[i].pay_rate);
						var sums = sum + sum;
						console.log(sums);
						table.row.add([
							data[i].sss,
							data[i].empname,
							data[i].pay_rate,
							data[i].pay_rate,
							data[i].pay_rate,
							data[i].pay_rate,
							sums, 
						]).draw();
					}
				},
			});
		});

		// function to fill the datatables
		function FillTable(d) {
			// table.row.add([
			// 	d.date_from_readable,
			// 	d.date_to_readable,
			// 	d.empid,
			// 	d.employee_name,
			// ]).draw();
		}
	</script>




@endsection
