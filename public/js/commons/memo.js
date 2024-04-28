$(document).ready(function() {
	if($('#memo-to').length > 0) {
		var datalistUrl = $('#memo-to').attr('data-datalist');
		$.get(datalistUrl, [], function(data) {
			$('#site-content').append(data);
			$('#memo-to').attr('list', $('#memo-to').attr('data-datalist-id'));
		});
	}

	$('.btn-add-row').each(function() {
		var me = $(this);
		$.get(me.attr('data-tr'), {'index': ':index'}, function(data) {
			me.data('tr-html', data);
			if(typeof trAfterCallback === 'function') {
				trAfterCallback();
			}
		});
	});

	$('#memo-to').change(function(e) {
		if($(this).val() == '') {
			$(this).val($(this).data('oldval'));
			return;
		}
		var datalistId = '#' + $(this).attr('data-datalist-id');
		var option = $(datalistId + ' option[value="' + $(this).val() + '"]');
		if(option.length == 0) {
			 if(datalistId =='#factory-list'){
				alert('এই নামে কোন মহাজন নেই।');
			 }else if(datalistId == '#gift-supplier-list'){
				alert('এই নামে কোন গিফট মহাজন নেই।');
			 }
			 else{
				alert('এই নামে কোন পার্টি নেই।');
			 }
			$(this).val($(this).data('oldval'));
			$(this).focus();
			return;
		}
		$('#memo-to-id').val(option.attr('data-id'));
		$('#form-table').prop('disabled', false);
		$(this).data('oldval', $(this).val());

		if(typeof option.attr('data-return-count') != 'undefined') {
			$('.input-return-count').html(option.attr('data-return-count'));
			$('.input-return-amount').val(option.attr('data-return-amount'));
			if(option.attr('data-return-count') == 0) {
				$('#unlisted-returns').css('display', 'none');
			} else {
				$.get(option.attr('data-return-url'), [], function(data) {
					$('#unlisted-returns').css('display', '');
					$('#unlisted-returns td').html(data);
				});
			}
		}

		if(typeof option.attr('data-other-costs') != 'undefined') {
			$('.input-other-costs').val(option.attr('data-other-costs'));
		}

		if(typeof memoToAfterCallback === 'function') {
			memoToAfterCallback();
		}
	});

	$('.btn-add-row').click(function(e) {
		e.preventDefault();

		var parent_id;
		if($(this).attr('data-parent-id') == undefined) {
			parent_id = '#memo-table';
		} else {
			parent_id = $(this).attr('data-parent-id');
		}

		var emptyInputs = $(parent_id).find('tbody').first().find('.required-value:enabled').filter(isEmpty);
		if(emptyInputs.length > 0) {
			var first = emptyInputs.first();
			var td = first.parents('td');
			var th = $(parent_id).find('th').eq(td.index());
			var title = th.html();
			// alert(title + ' প্রদান করুন।');
			Swal.fire({text: title + " প্রদান করুন।",});
			first.focus();
			return;
		}

		var table = $(this).parents('.purchase-table');
		if(table.length > 0) {
			var emptyFound = false;
			table.find('.tr-sub').each(function() {
				if($(this).find('.input-box:enabled').length > 0 && $(this).find('.input-box:enabled:checked').length == 0) {
					// alert('বক্স বাছাই করুন।');
					Swal.fire({
						text: "বক্স বাছাই করুন।",
					});
					$(this).find('.input-box:enabled').first().focus();
					emptyFound = true;
					return false;
				}
				if($(this).find('.input-bag:enabled').length > 0 && $(this).find('.input-bag:enabled:checked').length == 0) {
					// alert('ব্যাগ বাছাই করুন।');
					Swal.fire({
						text: "ব্যাগ বাছাই করুন।",
					});
					$(this).find('.input-bag:enabled').first().focus();
					emptyFound = true;
					return false;
				}
			});
			if(emptyFound) return;
		}
		addRow(parent_id);
		if(typeof addRowAfterCallback === 'function') {
			addRowAfterCallback(parent_id);
		}
	});

	$(document).on('click', '.btn-remove-row', function(e) {
		e.preventDefault();

		var parent_id;
		if($(this).attr('data-parent-id') === undefined) {
			parent_id = '#memo-table';
		} else {
			parent_id = $(this).attr('data-parent-id');
		}

		if($(parent_id).find('tbody').first().find('.tr-main').length == 1) {
			// alert('ফর্মে অন্তত একটি সারি থাকতে হবে।');
			Swal.fire({
				text: "ফর্মে অন্তত একটি সারি থাকতে হবে।",
			});
			return;
		}
		if(!confirm('আপনি কি আসলেই এই সারিটি মুছতে চান?'))
			return;
		var row = $(this).parents('.tr-main');
		var subrow = row.next('.tr-sub');
		if(typeof beforeRemoveRowCallback === 'function') {
			beforeRemoveRowCallback(row, subrow);
		}
		row.remove();
		subrow.remove();
		if(typeof afterRemoveRowCallback === 'function') {
			afterRemoveRowCallback(row, subrow);
		}
	});

	$('#memo-form').submit(function(e) {
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
						Swal.fire({
							text: title + " প্রদান করুন।",
						});
						elem.focus();
						e.preventDefault();
						return;
					}
				} else {
					var td = elem.parents('td');
					var th = td.parents('table').first().find('th').eq(td.index());
					var title = th.html();
					// alert(title + ' প্রদান করুন।');
					Swal.fire({
						text: title + " প্রদান করুন।",
					});
					elem.focus();
					e.preventDefault();
					return;
				}
			}
			/*var first = emptyInputs.first();
			var td = first.parents('td');
			var th = td.parents('table').find('th').eq(td.index());
			var title = th.html();
			alert(title + ' প্রদান করুন।');
			first.focus();*/
		}
		var table = $('.purchase-table');
		if(table.length > 0) {
			var emptyFound = false;
			table.find('.tr-sub').each(function() {
				if($(this).find('.input-box:enabled').length > 0 && $(this).find('.input-box:enabled:checked').length == 0) {
					// alert('বক্স বাছাই করুন।');
					Swal.fire({
						text: "বক্স বাছাই করুন।",
					});
					$(this).find('.input-box:enabled').first().focus();
					emptyFound = true;
					return false;
				}
				if($(this).find('.input-bag:enabled').length > 0 && $(this).find('.input-bag:enabled:checked').length == 0) {
					// alert('ব্যাগ বাছাই করুন।');
					Swal.fire({
						text: "ব্যাগ বাছাই করুন।",
					});
					$(this).find('.input-bag:enabled').first().focus();
					emptyFound = true;
					return false;
				}
			});
			if(emptyFound) e.preventDefault();
		}
	});

	$('#payment-method').change(function(e) {
		$('#cheque-no').prop('disabled', $(this).find('option:selected').html() == 'ক্যাশ');
		$('#cheque-date').prop('disabled', $(this).val() != 'cheque');
	});
});

function addRow(parent_id = '#memo-table') {
	var button = $(parent_id).find('.btn-add-row').first();

	var index = parseInt(button.attr('data-index'));
	$(parent_id).append(button.data('tr-html').replaceAll(':index', index));
	button.attr('data-index', ++index);
}

window.onbeforeunload = function() {
	return 'Are you sure?';
}


