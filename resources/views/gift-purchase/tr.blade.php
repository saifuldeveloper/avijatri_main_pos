@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($giftTransaction)) $giftTransaction = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td>
		@if($giftTransaction !== null)
			{{ input($errors, 'hidden', "gift_purchases.{$index}.id", '', $giftTransaction->id ?? '') }}
		@endif
		{{ select($errors, "gift_purchases.{$index}.gift_id", '', $giftTransaction->gift_id ?? '', $gifts, 'input-gift required-value') }}
		{{ error($errors, "gift_purchases.{$index}.gift_id") }}
	</td>
	<td>
		{{ input($errors, 'text', "gift_purchases.{$index}.count", '', $giftTransaction->count ?? '', 'input-count number update-sum required-value') }}
		{{ error($errors, "gift_purchases.{$index}.count") }}
	</td>
	<td>
		{{ input($errors, 'text', "gift_purchases.{$index}.unit_price", '', toFixed($giftTransaction->unit_price ?? ''), 'input-unit-price taka update-sum') }}
		{{ error($errors, "gift_purchases.{$index}.unit_price") }}
	</td>
	<td>
		{{ disabledInput($errors, 'text', '', '', toFixed($giftTransaction->amount ?? '0.00'), 'input-amount taka text-right') }}
	</td>
</tr>