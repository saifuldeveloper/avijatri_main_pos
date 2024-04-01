$(document).ready(function() {
	$('.update-sum').change(function(e) {
		updateSum();
	});

	$(document).on('change', '.input-payment-amount', function(e) {
		var sum = 0;
		$('.input-payment-amount').each(function() {
			sum += $(this).extractFloat();
		});
		$('.input-total-paid').val(sum.toFixed(2));
		updateSum();
	});

	$(document).on('change', '.input-cheque-amount', function(e) {
		var sum = 0;
		$('.input-cheque-amount').each(function() {
			sum += $(this).extractFloat();
		});
		$('.input-total-cheque').val(sum.toFixed(2));
		updateSum();
	});
});

function updateSum() {
	var totalPayable = $('.input-total-payable').extractFloat();
	var commission = $('.input-commission').extractFloat();
	var staff = $('.input-staff').extractFloat();
	var totalPaid = $('.input-total-paid').first().extractFloat();
	var totalCheque = $('.input-total-cheque').length > 0 ? $('.input-total-cheque').first().extractFloat() : 0;

	var commissionDeducted = totalPayable - commission;
	var remaining = commissionDeducted - staff;
	var totalRemaining = remaining - totalPaid - totalCheque;

	$('.input-commission-deducted').val(commissionDeducted.toFixed(2));
	$('.input-remaining').val(remaining.toFixed(2));
	$('.input-total-remaining').val(totalRemaining.toFixed(2));
	$('.span-total-remaining').html(totalRemaining.toFixed(2));

	$('.disable-for-nonzero').attr('disabled', totalRemaining != 0);
}

function beforeRemoveRowCallback(row, subrow) {
	var paymentAmount = row.find('.input-payment-amount');
	if(paymentAmount.length > 0) paymentAmount.val(0).trigger('change');

	var chequeAmount = row.find('.input-cheque-amount');
	if(chequeAmount.length > 0) chequeAmount.val(0).trigger('change');
}

function memoSubmitPrecondition() {
	if($('.disable-for-nonzero').length == 0) {
		return { result: true };
	}
	var totalRemaining = $('.input-total-remaining').extractFloat();
	if(totalRemaining == 0) {
		return { result: true };
	}
	return { result: false, message: 'কারখানাদারের পাওনা বাকী আছে।' };
}