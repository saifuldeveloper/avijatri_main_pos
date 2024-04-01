@php
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row" data-parent-id="#payment-table"><span class="fas fa-minus"></span></button></td>
	<td>{{ select($errors, "payments.{$index}.payment_method", '', '', $bankAccounts, 'input-payment-method required-value', ['required' => true]) }}</td>
	<td>{{ disabledInput($errors, 'text', "payments.{$index}.cheque_no", '', '', 'input-cheque-no number') }}</td>
	<td>{{ input($errors, 'text', "payments.{$index}.amount", '', '', 'input-payment-amount taka required-value allow-empty update-payment-sum text-right') }}</td>
</tr>