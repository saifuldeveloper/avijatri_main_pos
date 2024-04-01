@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td style="width:10%">
		{{ input($errors, 'text', "returns.{$index}.shoe_id", '', '', 'input-shoe-id number required-value', ['data-shoe-details' => route('ajax.shoe.show', ['shoe' => '#'])]) }}
		{{ error($errors, "returns.{$index}.shoe-id") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', '', 'input-category') }}
	</td>
	<td style="width:10%">
		{{ disabledInput($errors, 'text', '', '', '', 'input-color') }}
	</td>
	<td style="width:10%">
		{{ disabledInput($errors, 'text', '', '', '', 'input-retail-price taka') }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', '', 'input-purchase-price taka') }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', '', 'input-available') }}
	</td>
	<td style="width:10%">
		{{ input($errors, 'number', "returns.{$index}.count", '', '', 'input-count number update-sum required-value', ['min' => 0]) }}
		{{ error($errors, "returns.{$index}.count") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', '0.00', 'input-total-purchase-price taka text-right') }}
	</td>
</tr>