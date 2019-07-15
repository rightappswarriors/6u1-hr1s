{{-- 
	Please include custom-btn.js on the page.
	Include the following variables to the page where you put this tag:
	=> $filefld_name
	=> $filefld_append
--}}
<div class="form-group" id="{{(isset($filefld_append)) ? $filefld_append : ''}}">
	<label><strong><i class="fa fa-paperclip"></i> Attach File</strong></label>
	<input type="file" name="{{(isset($filefld_name)) ? $filefld_name : ''}}" class="form-control-file file-array">
</div>
<div class="form-group">
	<button type="button" class="btn btn-primary btn-block btn-file" data-target="{{(isset($filefld_append)) ? $filefld_append : ''}}"><i class="fa fa-plus"></i> Add File</button>
</div>