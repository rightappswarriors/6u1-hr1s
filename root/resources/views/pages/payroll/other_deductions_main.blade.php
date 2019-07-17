@extends('layouts.user')

@section('to-body')
	<div class="card">
		<div class="card-header">
			<div class="form-inline">
				<i class="fa fa-building"></i> Other Deductions <br>
					<div class="form-group mr-2">
						{{-- <input type="text" name="date_from" id="date_from" class="form-control" value="SELECT DATE" readonly> --}}
						<select class="form-control MonthSelector ml-3" name="date_month" id="date_month" onchange="">
							<option value="" disabled selected hidden>MONTH</option>
						</select>
						<select class="form-control YearSelector ml-3" name="date_year" id="date_year" onchange="">
							{{-- <option value="" disabled selected hidden>YEAR</option> --}}
						</select>
						{{-- <button class="btn btn-primary ml-3" onclick="toPrint()"><i class="fa fa-print"></i></button> --}}
					</div>
				{{-- <div class="float-right">
					<button class="btn btn-success" onclick="GenerateRata($('#date_from').val())">Generate</button>
				</div> --}}
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="dataTable">
									<thead>
										<tr>
											<th class="center">Code</th>
											<th class="center">ID</th>
											<th class="center">Employee <br> Name</th>
											<th class="center">Amount</th>
											<th class="center">Deduction</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>	
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-success btn-block" id="opt-add"><i class="fa fa-plus"></i> Add</button>
							<button type="button" class="btn btn-primary btn-block" id="opt-update"><i class="fa fa-edit"></i> Edit</button>
							<button type="button" class="btn btn-danger btn-block" id="opt-delete"><i class="fa fa-trash"></i> Delete</button>
							{{-- <button type="button" class="btn btn-info btn-block" id="opt-print"><i class="fa fa-print"></i> Print List</button> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('to-bottom')
	<script type="text/javascript" src="{{asset('js/for-fixed-tag.js')}}"></script>
	<script>
		var table = $('#dataTable').DataTable(dataTable_short);
	</script>

	<style>
		.center {
			text-align: center !important;
		}

		th {
			vertical-align: middle !important;
			text-align: center;
		}
	</style>
@endsection