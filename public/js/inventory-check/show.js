
$(document).ready(function() {
	$('#shoe-check-id').change(function(e) {
		var me = $(this);
		var url = me.attr('data-shoe-details').replace('#', me.val());

		$.get(url, [], function(data) {
			$('#shoe-check-factory').html(data.factory.name);
			$('#shoe-check-category').html(data.category.full_name);
			$('#shoe-check-color').html(data.color.name);
			$('#shoe-check-retail-price').html(data.retail_price.toFixed(2));
			$('#shoe-check-purchase-price').html(data.purchase_price.toFixed(2));
			$('#shoe-check-thumbnail').prop('src', '/images/small-thumbnail/' +data.image);
			$('#shoe-check-available').val(data.available);

			var tr = $('#shoe-' + me.val());
			if(tr.length > 0) {
				$('#shoe-check-remaining').val(tr.attr('data-remaining'));
			} else {
				$('#shoe-check-remaining').val(data.available);
			}

			$('#shoe-check-count').focus();
		}).fail(function() {
			alert('এই আইডির কোন জুতা নেই।');
			me.val('');
			me.focus();
		});
	});

	$('.shoe-image-link').click(function(e) {
		$('#shoe-image-modal img').prop('src' , $(this).prop('href'));
	});
});