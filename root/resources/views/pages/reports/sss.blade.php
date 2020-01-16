@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-file-excel-o"></i> SSS Contributions Summary
		</div>
		<div class="card-body">
			<form method="post" action="{{url('reports/sss/find')}}" id="frm-gp">
				<div class="container">
					<div class="form-group row">
						<div class="col-sm-7">
							<select class="form-control mr-2 select2" id="ofc" name="ofc">
								<option value="" selected="" disabled="">-Select office to generate-</option>
								@foreach($data[0] as $office)
								<option value="{{$office->cc_id}}">{{$office->cc_desc}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							
								<button type="button" class="btn btn-primary " onclick="PrintAllPage();">Print <i class="fa fa-print"></i></button>
								<i class="fa fa-spin fa-spinner ml-3" id="loadAnimation"></i>
							
						</div>
					</div>
					<div class="form-group row">
						<div class="row">
							<div class="col-sm-3">
								<select style="width: 100%;" class="form-control select2" id="month" name="month">
									@foreach(Core::Months() as $key => $value)
									<option value="{{$key}}" {{($key == date('m')) ? 'selected' : ''}}>{{$value}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-sm-3">	
								<select style="width: 100%;" class="form-control select2" id="payroll_period" name="payroll_period">
									<option value="15D">15th day</option>
									<option value="30D">30th day</option>
								</select>
							</div>
							<div class="col-sm-2">	
								<select style="width:100%;" class="form-control YearSelector select2" id="year" name="year">
								</select>
							</div>
							<div class="col-sm-4">
								<select class="form-control select2" style="width: 100%;" id="empstatus" name="empstatus">
									<option value="" selected="" disabled="">-Select Employee Status-</option>
									@foreach($data[1] as $empstatus)
										<option value="{{$empstatus->status_id}}">{{$empstatus->description}}</option>
									@endforeach
								</select>
							</div>	
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable">
					<col>
					<col>
					<col>
					<col>
					<col>
					<thead>
						<tr>
							<th>SSS Number</th>
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
		$('#loadAnimation').hide();	
		// onchange jquery script for <select id="office">
		$('#ofc').on('change', function() {
			table.clear();
			var ofc_id = $('#ofc :selected').val();
			var data = { 
                          _token : $('meta[name="csrf-token"]').attr('content'),
                          ofc_id : $('#ofc :selected').val(),
                          month : $('#month').val(),
                          payroll_period : $('#payroll_period').val(),
                      year : $('#year').val(),

                       };
			$.ajax({
				type: "post",
				url: "{{url('reports/sss/find-sss')}}",
				data: data,
				beforeSend: function(){
				    $('#loadAnimation').show();
				},
				success: function(data) {
					for(let i=0; i < data.length; i++){
						
						//data[i][0].civil_status // display user details
						//data[i][1][0].empshare_ec //payments

						var emp1 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_sc : 0.00));
						var emp2 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].empshare_ec : 0.00));
						var emp3 = parseFloat((typeof(data[i][1][0]) != 'undefined' ? data[i][1][0].s_ec : 0.00));
						if(data[i][0].sss == ''){
							var sss = '0-0-0';
						}
						else{
							var sss = data[i][0].sss;	
						}

						var sums = emp1 + emp2;
						data[i][1].empshare_sc //payments
						data[i][0].empname //employee details
						table.row.add([
							sss,
							data[i][0].empname,
							emp1,
							emp2,
							emp3,
							sums, 
						]).draw();

					}

					$('#loadAnimation').hide();		
				},
			});
		});

		function PrintAllPage(obj)
		{
			var ofc_id = parseInt($('#ofc :selected').val());
			PrintPage("{{url('reports/sss/print')}}?ofc_id="+ofc_id);

		}
	</script>




@endsection
