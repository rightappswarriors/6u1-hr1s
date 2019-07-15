<div class="mb-2 border" style="padding:10px;">
	<div class="btn-toolbar col-md-8" role="toolbar">
		<div class="row">
			{{csrf_field()}}
			<div class="btn-group mr-3" role="group">
		    	<button type="submit" form="{{$form_id}}" class="btn btn-success mr-1" name="btn_set_status" value="approve"><i class="fa fa-save"></i> Save</button>
		    	<a href="{{route('redirect.clearAlert', ['route'=>$to_route])}}" class="btn btn-danger mr-1"><i class="fa fa-close"></i> Cancel</a>
		  	</div>	  		
		</div>
	</div>
</div>