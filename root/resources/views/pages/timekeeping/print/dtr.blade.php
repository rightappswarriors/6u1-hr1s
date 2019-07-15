@extends('layouts.print_layout')

@section('body')
<table>
	<thead>
		<tr>
			<th width="20%">Employee</th>
			<th>Date</th>
			<th>Time In</th>
			<th>Time In</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data[0] as $timelog)
		<tr>
			<td>{{$timelog->empid}}</td>
			<td>{{$timelog->work_date}}</td>
			<td>{{$timelog->timein}}</td>
			<td>{{$timelog->timeout}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop

@section('script-body')
<script type="text/javascript">
	PrintPage();
</script>
@stop