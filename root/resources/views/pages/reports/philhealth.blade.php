@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> Philhealth Contributions Summary
		</div>
		<div class="card-body">
			<form method="post" action="{{url('reports/sss/find-philhealth')}}" id="frm-gp">
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
							<th>Philhealth Number</th>
							<th>Employee Name</th>
							<th>Employee Contribution</th>
							<th>Employer Contribution</th>
							<th>ECC</th>
							<th>TOTAL</th> 
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
				url: "{{url('reports/philhealth/find-philhealth')}}",
				data: data,
				success: function(data) {
					for(i=0; i<data.length; i++){
						var sum = parseFloat(data[i].pay_rate);
						var sums = sum + sum;
						console.log(sums);
						table.row.add([
							data[i].philhealth,
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
	</script>




@endsection
