
@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($giftTransaction)) $giftTransaction = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td  colspan="2">
		@if($giftTransaction !== null)
			{{ input($errors, 'hidden', "gifts.{$index}.id", '', $giftTransaction->id ?? '') }}
		@endif
		{{ select($errors, "gifts.{$index}.gift_id", '', $giftTransaction->gift_id ?? '', $gifts, 'input-gift') }}
		{{ error($errors, "gifts.{$index}.gift_id") }}
	</td>
	<td>
		{{ input($errors, 'text', "gifts.{$index}.count", '', $giftTransaction->count ?? '', 'input-count number update-sum ') }}
		{{ error($errors, "gifts.{$index}.count") }}
	</td>
</tr>