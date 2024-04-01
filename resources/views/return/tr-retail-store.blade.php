@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td class="text-center">
		{{ checkbox($errors, "returns.{$index}.new", '', false, 'input-new-shoe table-checkbox', ['disabled' => false]) }}
	</td>
	<td style="width:8%">
		{{ input($errors, 'text', "returns.{$index}.shoe_id", '', '', 'input-shoe-id enable-old number required-value', ['data-shoe-details' => route('ajax.shoe.show', ['shoe' => '#'])]) }}
		{{ error($errors, "returns.{$index}.shoe-id") }}
	</td>
	<td style="width:12%">
			{!! disabledInput($errors, 'hidden', "returns.{$index}.factory_id", '', '', 'input-factory-id enable-new') !!}
			{!! disabledInput($errors, 'text', "returns.{$index}.factory", '', '', 'input-factory enable-new required-value', ['data-datalist-id' => 'factory-list', 'data-datalist' => route('datalist.factory')]) !!}
			{!! error($errors, "returns.{$index}.factory_id") !!}
	</td>
	<td style="width:10%">
		{{ disabledInput($errors, 'hidden', "returns.{$index}.category_id", '', '', 'input-category-id enable-new') }}
		{{ disabledInput($errors, 'text', "returns.{$index}.category", '', '', 'input-category enable-new required-value', ['data-datalist-id' => 'category-list', 'data-datalist' => route('datalist.category')]) }}
		{{ error($errors, "returns.{$index}.category_id") }}
	</td>
	<td style="width:10%">
		{{ disabledInput($errors, 'hidden', "returns.{$index}.color_id", '', '', 'input-color-id enable-new') }}
		{{ disabledInput($errors, 'text', "returns.{$index}.color", '', '', 'input-color enable-new required-value', ['data-datalist-id' => 'color-list', 'data-datalist' => route('datalist.color')]) }}
		{{ error($errors, "returns.{$index}.color_id") }}
	</td>
	<td>
		<label for="input-image-{{ $index }}" class="btn btn-primary mb-0"><span class="fas fa-camera"></span></label>
		<input type="file" name="returns[{{ $index }}][image]" id="input-image-{{ $index }}" class="input-image d-none enable-new required-value" disabled>
	</td>
	<td style="width:12%">
		{{ disabledInput($errors, 'text', "returns.{$index}.retail_price", '', '', 'input-retail-price taka enable-new required-value update-sum') }}
		{{ error($errors, "returns.{$index}.retail_price") }}
	</td>
	<td style="width:12%">
		{{ disabledInput($errors, 'text', "returns.{$index}.purchase_price", '', '', 'input-purchase-price taka enable-new') }}
		{{ error($errors, "returns.{$index}.purchase_price") }}
	</td>
	<td style="width:10%">
		{{ input($errors, 'text', "returns.{$index}.count", '', '', 'input-count number update-sum required-value') }}
		{{ error($errors, "returns.{$index}.count") }}
	</td>
	<td style="width:10%">
		{{ input($errors, 'text', "returns.{$index}.commission", '', '', 'input-commission number update-sum required-value') }}
		{{ error($errors, "returns.{$index}.commission") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', "returns.{$index}.total_retail_price", '', '0.00', 'input-total-retail-price taka text-right') }}
		{{ error($errors, "returns.{$index}.total_retail_price") }}
	</td>
</tr>
<tr class="tr-sub">
	<td colspan="2"></td>
	<td colspan="9">
		<label><input type="radio" name="returns[{{ $index }}][destination]" value="inventory" class="ml-2 mr-1 enable-old input-destination">ইনভেন্টরি</label>
		<label><input type="radio" name="returns[{{ $index }}][destination]" value="factory-return" class="ml-2 mr-1 enable-old input-destination">মহাজন ফেরত</label>
		<label><input type="radio" name="returns[{{ $index }}][destination]" value="waste" class="ml-2 mr-1 enable-old input-destination">জোলাপ</label>
		<label><input type="radio" name="returns[{{ $index }}][destination]" value="pending" class="ml-2 mr-1 enable-old input-destination">পেন্ডিং</label>
	</td>
</tr>