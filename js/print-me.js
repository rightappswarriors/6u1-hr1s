function PrintPage(page_location) {
	// $('#loading-icon').attr('hidden', false);
	togglePreloader();
	if ($("<iframe>")
    .hide()
    .attr("src", page_location)
    .appendTo("body")) {
		// $('#loading-icon').attr('hidden', true);
		togglePreloader();
    }
}