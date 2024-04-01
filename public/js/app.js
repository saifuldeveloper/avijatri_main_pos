// String extension
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

// jQuery events
$(document).ready(function() {
	$(document).on('click', '.btn-new, .btn-edit', function(e) {
		var me = $(this);
		$.get($(this).attr('href'), [], function(data) {
			$('.form-modal .modal-title').html('');
			$('.form-modal .modal-body').html(data);
			if(typeof formModalAfterCallback === 'function') {
				formModalAfterCallback();
			}
		});
	});
	$(document).on('submit', '.delete-form', function(e) {
		return confirm('আপনি কি নিশ্চিত?');
	});
	$('.form-modal').on('hidden.bs.modal', function(e) {
		$(this).find('.modal-title').html('অপেক্ষা করুন ...');
		$(this).find('.modal-body').html('');
	});
	$(document).on('input', '.number, .taka', function(e) {
		// capture the previous position of the caret
		var caretPosition = this.selectionStart;

		// change bangla digits to english
		var english = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
		var bangla  = [/০/g, /১/g, /২/g, /৩/g, /৪/g, /৫/g, /৬/g, /৭/g, /৮/g, /৯/g,];
		var number = $(this).val();

		for(var i = 0; i < 10; i++) {
			number = number.replace(bangla[i], english[i]);
		}
		$(this).val(number);

		// decimal format of taka
		/*if($(this).hasClass('taka')) {
			number = parseFloat(number);
			if(isNaN(number)) number = 0.0;
			$(this).val(number.toFixed(2));
		}*/

		// replace the caret position
		this.setSelectionRange(caretPosition, caretPosition);
	});
	$(document).on('change', '.taka', function(e) {
		var number = $(this).extractFloat();
		if(isNaN(number)) number = 0.0;
		$(this).val(number.toFixed(2));
	});
});

// jQuery plugins
(function($){
	$.fn.extractInt = function() {
		var value = parseInt($(this).val());
		if(isNaN(value)) value = 0;
		return value;
	};
	$.fn.extractFloat = function() {
		var value = parseFloat($(this).val());
		if(isNaN(value)) value = 0.0;
		return value;
	};
	$.fn.outerHTML = function() {
		return jQuery('<div />').append(this.eq(0).clone()).html();
	};
})(jQuery);

// Utility functions
function setImageByInput(obj, input) {
	if(input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			obj.attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	}
}

function isEmpty() {
	return !this.value;
}

function nextShoeId(current) {
	return toHex(toDec(current) + 1);
}

function toHex(num) {
	return num.toString(16);
}

function toDec(hex) {
	return parseInt(hex, 16);
}