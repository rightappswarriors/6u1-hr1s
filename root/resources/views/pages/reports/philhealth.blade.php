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
						<button type="button" class="btn btn-primary " onclick="PrintAllPage();">Print <i class="fa fa-print"></i></button>
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
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript" src="{{url('js/print-me.js')}}"></script>
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
						var emp1 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_sc : 0.00));
						var emp2 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_ec : 0.00));
						var emp3 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].s_ec : 0.00));
						var sums = emp1 + emp2;
						if(data[i][0].philhealth == ''){
							var philhealth = '0-0-0';
						}
						else{
							var philhealth = data[i][0].philhealth;	
						}
						console.log(sums);
						table.row.add([
							philhealth,
							data[i][0].empname,
							emp1,
							emp2,
							emp3,
							sums, 
						]).draw();
					}
				},
			});
		});

		function PrintAllPage(obj)
		{
			var ofc_id = parseInt($('#ofc :selected').val());
			PrintPage("{{url('reports/philhealth/print')}}?ofc_id="+ofc_id);

		}
	</script>

@endsection
