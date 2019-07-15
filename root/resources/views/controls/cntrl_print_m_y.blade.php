{{-- 
	Please include for-fixed-tag.js on the page.
	Include the following variables to the page where you put this tag:
	=> $monthselector_class
	=> $yearselector_class
--}}
@php
$monthselector_class = "mr-1";
$yearselector_class = "mr-1";
@endphp
<div class="mb-2 border" style="padding: 10px;">
	<div class="btn-toolbar col-md-8" role="toolbar">
		<div class="row">
			<div class="btn-group mr-3" role="group">
				<label class="mr-1" style="padding-top: 5px;">Year: </label>
				@include('fixed_tags.year_selector')
				<label class="mr-1" style="padding-top: 5px;">Month: </label>
				@include('fixed_tags.month_selector')
				<button class="btn btn-primary btn mr-1"><i class="fa fa-print"></i> Generate</button>
			</div>
		</div>
	</div>
</div>