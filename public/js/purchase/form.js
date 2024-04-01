$(document).ready(function() {
	var nextShoe = $('#memo-table').attr('data-nextshoe');

	$('#memo-to').change(function(e) {
		$('#memo-table tbody tr.oldshoe').remove();
		$('.btn-add-row').attr('data-index', $('.tr-main').last().attr('data-index'));
		addRow();
	});

	$(document).on('change', '.input-new-shoe', function(e) {
		var newshoe = $(this).prop('checked');
		var oldshoe = !newshoe;

		var row = $(this).parents('.tr-main');
		var defaultSrc = row.find('.shoe-preview').attr('data-default-src');

		row.find('.enable-old').prop('readonly', newshoe);
		row.find('.enable-new').prop('disabled', oldshoe);
		row.find('input').val('');
		row.find('.shoe-preview').attr('src', defaultSrc);
		updateSum(row.find('.input-count'));

		var subrow = row.next('.tr-sub');
		subrow.find('.enable-old').prop('readonly', newshoe);
		subrow.find('.enable-new').prop('disabled', oldshoe);
		subrow.find('input[type="radio"]:checked').prop('checked', false);

		if(oldshoe) {
			row.find('.input-shoe-id').focus();
			row.addClass('oldshoe');
			subrow.addClass('oldshoe');
		} else {
			row.find('.input-category').focus();
			row.removeClass('oldshoe');
			subrow.removeClass('oldshoe');
		}

		updateNewIds(nextShoe);
		// Set default value for input-count if it is empty
		var countInput = row.find('.input-count');
		if (countInput.val() === '') {
			var defaultValue = 12; 
			countInput.val(defaultValue);
		}
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
				input.val(count + 12);
				input.trigger('change');
			}
			$(this).val('').focus();
			return;
		}

		var row = $(this).parents('tr');
		var url = $(this).attr('data-shoe-details').replace('#', $(this).val());

		$.get(url, [], function(data) {

			console.log(data);
			if(data.factory_id != $('#memo-to-id').val()) {
				// alert('জুতাটি এই মহাজনের নয়।');
				Swal.fire({
					text: "জুতাটি এই মহাজনের নয়।",
				  })
				me.val('');
				me.focus();
				return;
			}
			row.find('.input-category').val(data.category.full_name);
			row.find('.input-color').val(data.color.name);
			row.find('.input-retail-price').val(data.retail_price.toFixed(2));
			row.find('.input-purchase-price').val(data.purchase_price.toFixed(2));
			row.find('.shoe-preview').attr('src', '/images/small-thumbnail/' +data.image);
			if(row.find('.input-count').val() == '') {
				row.find('.input-count').val(12);
			}
			row.find('.input-count').trigger('change');

			var subrow = row.next('.tr-sub');
			subrow.find('.input-box[value="' + data.box_id + '"]').prop('checked', true);
			subrow.find('.input-bag[value="' + data.bag_id + '"]').prop('checked', true);
		}).fail(function() {
			// alert('এই আইডির কোন জুতা নেই।');
			Swal.fire({
				text: "এই আইডির কোন জুতা নেই।!",
			  })
			me.val('');
			me.focus();
		});
	});

	$(document).on('change', '.input-category, .input-color', function(e) {
		if($(this).val() == '') {
			$(this).val($(this).data('oldval'));
			return;
		}
		var datalistId = '#' + $(this).attr('data-datalist-id');
		var option = $(datalistId + ' option[value="' + $(this).val() + '"]');
		if(option.length == 0) {
			var model = $(this).hasClass('input-category') ? 'জুতার টাইপ' : 'রং';
			// alert('এই নামে কোন ' + model + ' নেই।');
			Swal.fire({
				text: "এই নামে কোন " + model + " নেই।",
			});
			$(this).val($(this).data('oldval'));
			$(this).focus();
			return;
		}
		$(this).prev().val(option.attr('data-id'));
		$(this).data('oldval', $(this).val());
	});

	$(document).on('change', '.input-image', function(e) {
		var row = $(this).parents('tr');
		var img = row.find('.shoe-preview');
		var defaultSrc = img.attr('data-default-src');

		if($(this).val() == '') {
			$(this).prev('.btn').removeClass('btn-success').addClass('btn-primary');
			img.attr('src', defaultSrc);
		} else {
			$(this).prev('.btn').removeClass('btn-primary').addClass('btn-success');
			setImageByInput(img, this);
		}
	});

	$(document).on('change', '.update-sum', function(e) {
		updateSum($(this));
	});
});

function updateNewIds(first) {
	var id = first;
	$('.input-shoe-id[readonly]').each(function() {
		$(this).val(id);
		id = nextShoeId(id);
	});
}

function updateSum(me) {
	if(me.hasClass('input-purchase-price') || me.hasClass('input-count')) {
		var row = me.parents('.tr-main');
		var purchase_price = row.find('.input-purchase-price').extractFloat();
		var count = row.find('.input-count').extractInt();

		var sum = purchase_price * count / 12;
		var prevSum = row.find('.input-total-purchase-price').extractFloat();
		var totalPayable = $('.input-total-payable').extractFloat() - prevSum + sum;
		row.find('.input-total-purchase-price').val(sum.toFixed(2));
		$('.input-total-payable').val(totalPayable.toFixed(2));
	}
}

function trAfterCallback() {
	var categoryDatalistUrl = $('.input-category').attr('data-datalist');
	var colorDatalistUrl = $('.input-color').attr('data-datalist');

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
	$('.purchase-table .input-shoe-id').filter(function() {
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



const numericInputs = document.querySelectorAll('.numericInput');

// Add event listener for input event to each numeric input field
numericInputs.forEach(input => {
    input.addEventListener('input', function(event) {
        // Remove non-numeric characters from the input value
        this.value = this.value.replace(/\D/g, '');
    });
});