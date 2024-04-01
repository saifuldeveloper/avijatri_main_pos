$(document).ready(function() {
	$('#waste-id').change(function(e) {
		var me = $(this);
		var url = $(this).attr('data-shoe-details').replace('#', $(this).val());
		console.log(url);

		$.get(url, [], function(data) {
			console.log(data);
			// if(data.available == 0) {
			// 	alert('এই জুতাটি ইনভেন্টরিতে নেই।');
			// 	$('#shoe-description').addClass('d-none');
			// 	me.val('');
			// 	me.focus();
			// 	return;
			// }
			$('#shoe-description').html('টাইপ: ' + data.category.full_name + ', রং: ' + data.color.name + ', গায়ের দাম: ' + data.retail_price.toFixed(2) + ', জোড়া আছে: ' + data.available);
			$('#shoe-description').removeClass('d-none');
		}).fail(function() {
			// alert('এই আইডির কোন জুতা নেই।');
			Swal.fire({
				text: "এই আইডির কোন জুতা নেই",
			  })
			$('#shoe-description').addClass('d-none');
			me.val('');
			me.focus();
		});
	});
});