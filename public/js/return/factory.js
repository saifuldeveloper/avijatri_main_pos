$(document).ready(function() {
	$(document).on('change', '.input-shoe-id', function(e) {
		var me  = $(this);
		var same = $('.input-shoe-id').filter(function() {
			return $(this).val() == me.val();
		});
		if(same.length > 1) {
			var samerow = same.parents('.tr-main');
			var available = parseInt(samerow.find('.input-available').val()) - parseInt(samerow.find('.input-count').val());
			var add = (available < 6 ? available : 6);
			if(add == 0) {
				alert('এই আইডির জুতা আর নেই।');
			} else if(confirm('এই আইডির আরো ' + add + 'টি জুতা ফেরত দিতে চান?')) {
				var input = same.first().parents('.tr-main').find('.input-count');
				var count = input.extractInt();
				input.val(count + add);
				input.trigger('change');
			}
			$(this).val('').focus();
			return;
		}

		var row = $(this).parents('.tr-main');
		var url = $(this).attr('data-shoe-details').replace('#', $(this).val());

		$.get(url, [], function(data) {
			if(data.factory_id != $('#memo-to-id').val()) {
				alert('জুতাটি এই মহাজনের নয়।');
				me.val('');
				me.focus();
				return;
			}
			if(data.available == 0) {
				alert('এই জুতাটি ইনভেন্টরিতে নেই।');
				me.val('');
				me.focus();
				return;
			}
			row.find('.input-category').val(data.category.full_name);
			row.find('.input-color').val(data.color.name);
			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
			row.find('.input-purchase-price').val(data.purchase_price.toFixed(2));
			row.find('.input-available').val(data.available);
			if(row.find('.input-count').val() == '') {
				row.find('.input-count').val(data.available < 6 ? data.available : 6);
			} else if(parseInt(row.find('.input-count').val()) > data.available) {
				row.find('.input-count').val(data.available);
			}
			row.find('.input-count').prop('max', data.available).trigger('change');
		}).fail(function() {
			alert('এই আইডির কোন জুতা নেই।');
			me.val('');
			me.focus();
		});
	});

	$(document).on('change', '.update-sum', function(e) {
		var me = $(this);
		var row = me.parents('.tr-main');
		var total = row.find('.input-total-purchase-price');

		var purchase_price = row.find('.input-purchase-price').extractFloat();
		var count = row.find('.input-count').extractInt();
		var total_price = purchase_price * count / 12;
		total.val(total_price.toFixed(2));
	});
});

function addRowAfterCallback(parent_id) {
	$('.factory-return-table .input-shoe-id').filter(function() {
		return $(this).val() == '';
	}).focus();
}