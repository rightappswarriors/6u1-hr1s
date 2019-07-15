<div class="mb-2 border" style="padding:10px;">
	<div class="btn-toolbar col-md-8" role="toolbar">
		<form action="{{route('usermanagement.assessapplication', ['id'=>$id])}}" method="post" class="row">
			{{csrf_field()}}
			<div class="btn-group mr-3" role="group">
		    	<button type="submit" class="btn btn-success mr-1" name="btn_set_status" value="approve"><i class="fa fa-check"></i> Approve</button>
		    	<button type="submit" class="btn btn-danger mr-1" name="btn_set_status" value="deny"><i class="fa fa-close"></i> Deny</button>
		    	<button type="submit" class="btn btn-dark mr-1" name="btn_set_status" value="ban"><i class="fa fa-ban"></i> Ban</button>
		    	<button type="submit" class="btn btn-warning mr-1" name="btn_set_status" value="pending"><i class="fa fa-clock-o"></i> Pending</button>
		    	<button type="submit" class="btn btn-light mr-1" name="btn_set_status" value="inactive"><i class="fa fa-exclamation-circle"></i> Inactive</button>
		  	</div>
		  	<div class="btn-group mr-3" role="group">
		  		<a href="{{route('usermanagement.applicationlist')}}"><button type="button" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</button></a>
		  	</div>		  		
		</form>
	</div>
</div>