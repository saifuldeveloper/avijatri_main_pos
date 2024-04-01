@php
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td style="width:10%">
		{{ input($errors, 'text', "entries.{$index}.shoe_id", '', '', 'input-shoe-id number required-value', ['data-shoe-details' => route('ajax.shoe.show', ['shoe' => '#'])]) }}
		{{ error($errors, "entries.{$index}.shoe-id") }}
	</td>
	<td style="width:25%">
		{{ disabledInput($errors, 'text', "entries.{$index}.category", '', '', 'input-category') }}
	</td>
	<td style="width:25%">
		{{ disabledInput($errors, 'text', "entries.{$index}.color", '', '', 'input-color') }}
	</td>
	<td style="width:25%">
		{{ disabledInput($errors, 'text', "entries.{$index}.retail_price", '', '', 'input-retail-price') }}
	</td>
	<td style="width:15%">
		{{ input($errors, 'text', "entries.{$index}.count", '', '', 'input-count number required-value') }}
		{{ error($errors, "entries.{$index}.count") }}
	</td>
</tr>