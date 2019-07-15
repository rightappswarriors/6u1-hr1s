@extends('layouts.print_layout')

@section('body') 
	<table>
		<tbody>
			<tr>
				<td><b>Payroll Period</b></td>
			</tr>
		</tbody>
	</table>
@endsection

@section('script-body')
	<script type="text/javascript">
		PrintPage();
	</script>
@endsection

