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
			} else if(confirm('এই আইডির আরো ' + add + 'টি জুতা যোগ করতে চান?')) {
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
			if(data.available == 0) {
				alert('এই জুতাটি ইনভেন্টরিতে নেই।');
				me.val('');
				me.focus();
				return;
			}
			row.find('.input-category').val(data.category.full_name);
			row.find('.input-color').val(data.color.name);
			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
			row.find('.input-available').val(data.available);
			if(row.find('.input-count').val() == '') {
				row.find('.input-count').val(data.available < 6 ? data.available : 6);
			} else if(parseInt(row.find('.input-count').val()) > data.available) {
				row.find('.input-count').val(data.available);
			}
			row.find('.input-count').prop('max', data.available).trigger('change');

			$('.invoice-table .btn-add-row').first().trigger('click');
		}).fail(function() {
			alert('এই আইডির কোন জুতা নেই।');
			me.val('');
			me.focus();
		});
	});

	$(document).on('change', '.input-payment-method', function(e) {
		var row = $(this).parents('.tr-main');
		console.log(row);
		row.find('.input-cheque-no').prop('disabled', $(this).find('option:selected').html() == 'ক্যাশ');
	});

	$(document).on('change', '.update-sum', function(e) {
		updateSum($(this));
	});

	$(document).on('change', '.update-payment-sum', function(e) {
		var sum = 0;
		$('.update-payment-sum').each(function(index) {
			sum += $(this).extractFloat();
		});
		$('#payment-table .input-total-payment').val(sum.toFixed(2));
	});
});

function updateSum(me) {
	var commission = $('.input-commission').extractFloat();
	var totalCommission = $('.input-total-commission').extractFloat();
	var commissionDeducted = $('.input-commission-deducted').extractFloat();

	if(me.hasClass('input-count') || me.hasClass('input-commission')) {
		var totalAmount = $('.input-total-amount').extractFloat();
		if(me.hasClass('input-count')) {
			var row = me.parents('.tr-main');
			var retail_price = row.find('.input-retail-price').extractFloat();
			var count = me.extractInt();

			var sum = retail_price * count;
			var prevSum = row.find('.input-total-retail-price').extractFloat();
			var totalAmount = totalAmount - prevSum + sum;
			row.find('.input-total-retail-price').val(sum.toFixed(2));
			$('.input-total-amount').val(totalAmount.toFixed(2));
		}
		totalCommission = totalAmount * commission / 100;
		commissionDeducted = totalAmount - totalCommission;
		$('.input-total-commission').val(totalCommission.toFixed(2));
		$('.input-commission-deducted').val(commissionDeducted.toFixed(2));
	}

	var returnAmount = $('.input-return-amount').extractFloat();
	var returnDeducted = commissionDeducted - returnAmount;

	var transport = $('.input-transport').extractFloat();
	var otherCosts = $('.input-other-costs').extractFloat();
	var discount = $('.input-discount').extractFloat();
	var totalReceivable = returnDeducted + transport - otherCosts - discount;

	$('.input-return-deducted').val(returnDeducted.toFixed(2));
	$('.input-total-receivable').val(totalReceivable.toFixed(2));
}

function addRowAfterCallback(parent_id) {
	if(parent_id == '#memo-table') {
		$('.invoice-table .input-shoe-id').filter(function() {
			return $(this).val() == '';
		}).focus();
	} else {
		//
	}
}

function beforeRemoveRowCallback(row, subrow) {
	row.find('.input-count').val(0).trigger('change');
}





$('#invoice-memo-form').submit(function(e) {
	if(typeof memoSubmitPrecondition === 'function') {
		var preTest = memoSubmitPrecondition();
		if(!preTest.result) {
			alert(preTest.message);
			e.preventDefault();
			return;
		}
	}
	var emptyInputs = $('.required-value:enabled').filter(isEmpty);
	if(emptyInputs.length > 0) {
		for(var i = 0; i < emptyInputs.length; i++) {
			var elem = $(emptyInputs[i]);
			if(elem.hasClass('allow-empty')) {
				continue;
			}
			var requiredOnceRows = elem.parents('table').find('.required-once').length;
			if((requiredOnceRows > 1 || requiredOnceRows == 0) && elem.hasClass('empty-row')) {
				var tr = elem.parents('tr');
				var emptyRowInputs = tr.find('.empty-row');
				for(var j = 0; j < emptyRowInputs.length; j++) {
					if($(emptyRowInputs[j]).val() != '') {
						break;
					}
				}
				if(j < emptyRowInputs.length) {
					var td = elem.parents('td');
					var th = td.parents('table').first().find('th').eq(td.index());
					var title = th.html();
					// alert(title + ' প্রদান করুন।');
					elem.focus();
					e.preventDefault();
					return;
				}
			} else {
				var td = elem.parents('td');
				var th = td.parents('table').first().find('th').eq(td.index());
				var title = th.html();
				// alert(title + ' প্রদান করুন।');
				elem.focus();
				e.preventDefault();
				return;
			}
		}
	}
	// var table = $('.purchase-table');
	// if(table.length > 0) {
	// 	var emptyFound = false;
	// 	table.find('.tr-sub').each(function() {
	// 		if($(this).find('.input-box:enabled').length > 0 && $(this).find('.input-box:enabled:checked').length == 0) {
	// 			// alert('বক্স বাছাই করুন।');
	// 			Swal.fire({
	// 				text: "বক্স বাছাই করুন।",
	// 			});
	// 			$(this).find('.input-box:enabled').first().focus();
	// 			emptyFound = true;
	// 			return false;
	// 		}
	// 		if($(this).find('.input-bag:enabled').length > 0 && $(this).find('.input-bag:enabled:checked').length == 0) {
	// 			// alert('ব্যাগ বাছাই করুন।');
	// 			Swal.fire({
	// 				text: "ব্যাগ বাছাই করুন।",
	// 			});
	// 			$(this).find('.input-bag:enabled').first().focus();
	// 			emptyFound = true;
	// 			return false;
	// 		}
	// 	});
	// 	if(emptyFound) e.preventDefault();
	// }
});