<div class="mb-2 border" style="padding:10px;">
	<div class="btn-toolbar col-md-8" role="toolbar">
		<div class="row">
			<div class="btn-group mr-3" role="group">
		    	<a href="@if($addnew_route!=null) {{route($addnew_route)}} @else {{$trgr_modal_add}} @endif" class="btn btn-primary mr-1" @isset($trgr_modal_add) data-toggle="modal" @endisset><i class="fa fa-plus"></i> Add new</a>
		    	<a href="#" class="btn btn-primary mr-1"><i class="fa fa-refresh"></i> History</a>
		    	<a href="#" class="btn btn-primary mr-1"><i class="fa fa-edit"></i> Change Permission</a>
		    	<a href="#" class="btn btn-primary mr-1"><i class="fa fa-print"></i> Print</a>
		  	</div>
		</div>
	</div>
</div>