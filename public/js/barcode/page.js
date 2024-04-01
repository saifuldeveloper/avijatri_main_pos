$(document).ready(function() {
	$(document).on('change', '.input-shoe-id', function(e) {
		var me  = $(this);
		var same = $('.input-shoe-id').filter(function() {
			return $(this).val() == me.val();
		});
		if(same.length > 1) {
			if(confirm('এই আইডির আরো বারকোড প্রিন্ট করতে চান?')) {
				var input = same.first().parents('.tr-main').find('.input-count');
				var count = input.extractInt();
				input.val(count + 6);
			}
			$(this).val('').focus();
			return;
		}

		var row = $(this).parents('tr');
		var url = $(this).attr('data-shoe-details').replace('#', $(this).val());

		$.get(url, [], function(data) {
			row.find('.input-category').val(data.category.full_name);
			row.find('.input-color').val(data.color.name);
			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
			if(row.find('.input-count').val() == '') {
				row.find('.input-count').val(6);
			}
		}).fail(function() {
			alert('এই আইডির কোন জুতা নেই।');
			me.val('');
			me.focus();
		});
	});
});

function addRowAfterCallback(parent_id) {
	$('.barcode-table .input-shoe-id').filter(function() {
		return $(this).val() == '';
	}).focus();
}