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
							<th>Name of Employer</th>
							<th>Employee SSS No.</th>
							<th>Agency</th>
							<th>Branch Code</th>
							<th>Region Code</th>
							<th>Name of Employees</th>
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
	<script type="text/javascript" src="{{url('js/for-fixed-tag.js')}}"></script>
	<script type="text/javascript" src="{{url('js/print-me.js')}}"></script>
	<script>
		var table = $('#dataTable').DataTable({
			"paging": false
		});

		// onchange jquery script for <select id="office">
		$('#ofc').on('change', function() {
			table.clear().draw();
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
						if(data[i][0].sss == ''){
							var sss = '0-0-0';
						}
						else{
							var sss = data[i][0].sss;	
						}

						var emp1 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_sc : 0.00));
						var emp2 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_ec : 0.00));
						var emp3 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].s_ec : 0.00));
						var employer = '';
						var agency = '';
						var branch = '';
						var region = '';
						var sums = emp1 + emp2;
						table.row.add([
							data[i][0].empname,
							sss,
							agency,
							branch,
							region,
							data[i][0].empname,
							sums,
						]).draw();
					}
				},
			});
		});

		function PrintAllPage(obj)
		{
			var ofc_id = parseInt($('#ofc :selected').val());
			PrintPage("{{url('reports/pagibig/print')}}?ofc_id="+ofc_id);

		}
	</script>




@endsection
