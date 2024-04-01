@php
if(!isset($invoice)) $invoice = null;
if(!isset($oldvals)) $oldvals = null;
if(!isset($invoiceEntry)) $invoiceEntry = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main required-once bg-light" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td style="width:10%">
		@if($invoiceEntry !== null)
			{{ input($errors, 'hidden', "sales.{$index}.id", '', $invoiceEntry->id ?? '') }}
		@endif
		{{ input($errors, 'text', "sales.{$index}.shoe_id", '', $invoiceEntry->shoe_id ?? '', 'input-shoe-id number required-value empty-row', ['data-shoe-details' => route('ajax.shoe.show', ['shoe' => '#']), 'autofocus' => ($invoice !== null && $invoiceEntry === null)]) }}
		{{ error($errors, "sales.{$index}.shoe-id") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', $invoiceEntry->shoe->category->full_name ?? '', 'input-category') }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', $invoiceEntry->shoe->color->name ?? '', 'input-color') }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', toFixed($invoiceEntry->shoe->retail_price ?? ''), 'input-retail-price taka') }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', $invoiceEntry->shoe->available ?? '', 'input-available') }}
	</td>
	<td style="width:15%">
		{{ input($errors, 'number', "sales.{$index}.count", '', $invoiceEntry->count ?? '', 'input-count number update-sum required-value empty-row', array_merge(['min' => 0], ($invoiceEntry === null ? [] : ['max' => $invoiceEntry->shoe->available]))) }}
		{{ error($errors, "sales.{$index}.count") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', '', '', toFixed($invoiceEntry->total_price ?? 0), 'input-total-retail-price taka text-right') }}
	</td>
</tr>