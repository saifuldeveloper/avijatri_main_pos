$(document).ready(function() {
	$(document).on('change', '.update-sum', function(e) {
		updateSum($(this));
	});
});

function updateSum(me) {
	var sum = 0;
	$('.tr-main').each(function() {
		var count = $(this).find('.input-count').extractInt();
		var unit_price = $(this).find('.input-unit-price').extractFloat();
		var row_sum = count * unit_price;
		
		$(this).find('.input-amount').val(row_sum.toFixed(2));
		sum += row_sum;
	});
	$('.input-total').val(sum.toFixed(2));
}

function beforeRemoveRowCallback(row, subrow) {
	updateSum(row.find('.input-amount').val(0));
}