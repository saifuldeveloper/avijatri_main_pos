$(document).ready(function() {
	var nextShoe = $('#memo-table').attr('data-nextshoe');

	$(document).on('change', '.input-new-shoe', function(e) {
		var newshoe = $(this).prop('checked');
		var oldshoe = !newshoe;

		var row = $(this).parents('.tr-main');
		row.find('.enable-old').prop('readonly', newshoe);
		row.find('.enable-new').prop('disabled', oldshoe);
		row.find('input').val('');
		updateSum(row.find('.input-count'));

		var subrow = row.next('.tr-sub');
		/*subrow.find('.enable-old').prop('disabled', newshoe);
		subrow.find('.enable-new').prop('disabled', oldshoe);
		subrow.find('input[type="radio"]:checked').prop('checked', false);*/

		if(oldshoe) {
			row.find('.input-shoe-id').focus();
			row.addClass('oldshoe');
			subrow.addClass('oldshoe');
			subrow.find('.input-destination').removeAttr('readonly');
			subrow.find('.input-destination').parent().show();
		
		} else {
			row.find('.input-factory').focus();
			// row.find('.input-category').focus();
			row.removeClass('oldshoe');
			subrow.removeClass('oldshoe');
			subrow.find('.input-destination').removeAttr('readonly');
			subrow.find('.input-destination[value="inventory"]').parent().hide();
			subrow.find('.input-destination[value!="inventory"]').parent().show();
		}

		updateNewIds(nextShoe);
	});

	$(document).on('change', '.input-shoe-id', function(e) {
		var me  = $(this);
		var same = $('.input-shoe-id').filter(function() {
			return $(this).val() == me.val();
		});
		if(same.length > 1) {
			if(confirm('এই আইডির আরো জুতা কিনতে চান?')) {
				var input = same.first().parents('.tr-main').find('.input-count');
				var count = input.extractInt();
				input.val(count + 6);

				input.trigger('change');
			}
			$(this).val('').focus();
			return;
		}

		var row = $(this).parents('tr');
		var url = $(this).attr('data-shoe-details').replace('#', $(this).val());

		var retail_store = $('#memo-to-id').val();
		var csrfToken = $('meta[name="csrf-token"]').attr('content');
		var postData = {
			_token: csrfToken,
			shoe_id: me.val(),
			RetailStore: retail_store // Sending the invoice ID
		};
		var invoiceCheckUrl = '/ajax/datalist/retail-store/invoice/product/check';

		// $.post(invoiceCheckUrl, postData, function(data) {
		// 	 if(data.found == true){
		// 		$.get(url, [], function(data) {
		// 			row.find('.input-factory').val(data.factory.name)
		// 			row.find('.input-category').val(data.category.full_name);
		// 			row.find('.input-color').val(data.color.name);
		// 			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
		// 			row.find('.input-purchase-price').val(data.purchase_price.toFixed(2));
		// 			if(row.find('.input-count').val() == '') {
		// 				row.find('.input-count').val(6);
		// 			}
		// 			if(row.find('.input-commission').val() == '') {
		// 				row.find('.input-commission').val(28);
		// 			}
		// 			row.find('.input-count').trigger('change');
		
		// 			var subrow = row.next('.tr-sub');
		// 			var radios = subrow.find('.input-destination');
		// 			if(radios.filter(function() { return $(this).prop('checked') }).length == 0) {
		// 				radios.first().prop('checked', true);
		// 			}
		// 		}).fail(function() {
		// 			alert('এই আইডির কোন জুতা নেই।');
		// 			me.val('');
		// 			me.focus();
		// 		});
		// 	 }else{
		// 		alert( data.id+' আইডির  জুতা ' + data.name +' এর কাছে  বিক্রয়  করা হয় নাই ');
		// 	 }
			 
		// })

		$.get(url, [], function(data) {
			row.find('.input-factory').val(data.factory.name)
			row.find('.input-category').val(data.category.full_name);
			row.find('.input-color').val(data.color.name);
			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
			row.find('.input-purchase-price').val(data.purchase_price.toFixed(2));
			if(row.find('.input-count').val() == '') {
				row.find('.input-count').val(6);
			}
			if(row.find('.input-commission').val() == '') {
				row.find('.input-commission').val(28);
			}
			row.find('.input-count').trigger('change');

			var subrow = row.next('.tr-sub');
			var radios = subrow.find('.input-destination');
			if(radios.filter(function() { return $(this).prop('checked') }).length == 0) {
				radios.first().prop('checked', true);
			}
		}).fail(function() {
			alert('এই আইডির কোন জুতা নেই।');
			me.val('');
			me.focus();
		});
	});

	$(document).on('change', 'input.input-factory, input.input-category, input.input-color', function(e) {
		if($(this).val() == '') {
			$(this).val($(this).data('oldval'));
			return;
		}
		var datalistId = '#' + $(this).attr('data-datalist-id');
		var option = $(datalistId + ' option[value="' + $(this).val() + '"]');
		if(option.length == 0) {
			// var model = $(this).hasClass('input-category') ? 'জুতার টাইপ' : 'রং';
			var model;
			if ($(this).hasClass('input-category')) {
				model = 'জুতার টাইপ';
			} else if ($(this).hasClass('input-factory')) {
				model = 'ফ্যাক্টরির নাম';
			} else {
				model = 'রং';
			}

			alert('এই নামে কোন ' + model + ' নেই।');
			$(this).val($(this).data('oldval'));
			$(this).focus();
			return;
		}
		$(this).prev().val(option.attr('data-id'));
		$(this).data('oldval', $(this).val());
	});

	$(document).on('change', '.input-image', function(e) {
		if($(this).val() == '') {
			$(this).prev('.btn').removeClass('btn-success').addClass('btn-primary');
		} else {
			$(this).prev('.btn').removeClass('btn-primary').addClass('btn-success');
		}
	});

	$(document).on('change', '.update-sum', function(e) {
		updateSum($(this));
	});
});

function updateNewIds(first) {
	var id = first;
	$('.input-shoe-id[readonly]').each(function() {
		$(this).val('X-' + id);
		id = nextShoeId(id);
	});
}

function updateSum(me) {
	if(me.hasClass('input-retail-price') || me.hasClass('input-count') || me.hasClass('input-commission')) {
		var row = me.parents('.tr-main');
		var retail_price = row.find('.input-retail-price').extractFloat();
		var count = row.find('.input-count').extractInt();
		var commission = row.find('.input-commission').extractFloat();
		var sum = retail_price * count * (100 - commission) / 100;
		row.find('.input-total-retail-price').val(sum.toFixed(2));
	}
}

function trAfterCallback() {
	var factoryDatalistUrl = $('.input-factory').attr('data-datalist');
	var categoryDatalistUrl = $('.input-category').attr('data-datalist');
	var colorDatalistUrl = $('.input-color').attr('data-datalist');

	$.get(factoryDatalistUrl, [], function(data) {
		$('#site-content').append(data);
		$('.input-factory').attr('list', $('.input-factory').attr('data-datalist-id'));

		var savedRow = $('<div />').append($('.btn-add-row').data('tr-html'));
		savedRow.find('.input-factory').attr('list', $('.input-factory').attr('data-datalist-id'));
		$('.btn-add-row').data('tr-html', savedRow.html());
	});
	

	$.get(categoryDatalistUrl, [], function(data) {
		$('#site-content').append(data);
		$('.input-category').attr('list', $('.input-category').attr('data-datalist-id'));

		var savedRow = $('<div />').append($('.btn-add-row').data('tr-html'));
		savedRow.find('.input-category').attr('list', $('.input-category').attr('data-datalist-id'));
		$('.btn-add-row').data('tr-html', savedRow.html());
	});

	$.get(colorDatalistUrl, [], function(data) {
		$('#site-content').append(data);
		$('.input-color').attr('list', $('.input-color').attr('data-datalist-id'));

		var savedRow = $('<div />').append($('.btn-add-row').data('tr-html'));
		savedRow.find('.input-color').attr('list', $('.input-color').attr('data-datalist-id'));
		$('.btn-add-row').data('tr-html', savedRow.html());
	});
}

function addRowAfterCallback(parent_id) {
	$('.retail-store-return-table .input-shoe-id').filter(function() {
		return $(this).val() == '';
	}).focus();
}

function memoToAfterCallback() {
	$('.input-shoe-id').last().focus();
}

function beforeRemoveRowCallback(row, subrow) {
	row.find('.input-count').val(0).trigger('change');
}

function afterRemoveRowCallback() {
	updateNewIds($('#memo-table').attr('data-nextshoe'));
}