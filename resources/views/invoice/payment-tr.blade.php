@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($transaction)) $transaction = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light">
	<td><button class="btn btn-danger btn-remove-row" data-parent-id="#payment-table"><span class="fas fa-minus"></span></button></td>

	<td>
		@if($transaction !== null)
		{{ input($errors, 'hidden', "payments.{$index}.id", '', $transaction->id ?? '') }}
	    @endif
		{{ select($errors, "payments.{$index}.payment_method", '', $transaction->toAccount->BankAccount->id ?? '', $bankAccounts, 'input-payment-method required-value', ['required' => true]) }}
		{{ error($errors, "payments.{$index}.payment_method") }}
	
	</td>
	<td>{{ input($errors, 'text', "payments.{$index}.cheque_no", '', $transaction->description ?? '', 'input-cheque-no number') }}</td>
	     {{ error($errors, "payments.{$index}.cheque_no") }}


	 <td>{{ input($errors, 'text', " payments.{$index}.amount", '', $transaction->amount ?? '', 'input-payment-amount taka required-value allow-empty update-payment-sum text-right') }}
	    {{ error($errors, "payments.{$index}.amount") }}
	</td>
</tr>

