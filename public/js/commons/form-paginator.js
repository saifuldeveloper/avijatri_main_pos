$(document).ready(function() {
	var url = new URL(window.location.href);
	var page = url.searchParams.get('page');
	var currentpage = page == null ? 1 : parseInt(page);

	$('#goto-prev').click(function(e) {
		$('#goto-page').val(currentpage - 1);
	});
	$('#goto-next').click(function(e) {
		$('#goto-page').val(currentpage + 1);
	})
});