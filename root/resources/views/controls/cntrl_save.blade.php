{{--
	Include the following variables to the page where you put this tag:
	=> $cntrl_save_class
	=> $cntrl_save_id
	=> $cntrl_save_frm
--}}
<div class="btn-group mr-3" role="group">
	<button type="submit" class="btn btn-success btn-spin {{(isset($cntrl_save_class)) ? $cntrl_save_class : ""}}" id="{{(isset($cntrl_save_id)) ? $cntrl_save_id : ""}}" form="{{(isset($cntrl_save_frm)) ? $cntrl_save_frm : ""}}"><i class="fa fa-save"></i> Save Changes</button>
</div>