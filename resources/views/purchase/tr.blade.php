@php
if(!isset($oldvals)) $oldvals = null;
if(!isset($purchaseEntry)) $purchaseEntry = null;
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main bg-light oldshoe" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row"><span class="fas fa-minus"></span></button></td>
	<td>
		<img src="{{ asset('img/shoe.png') }}" class="shoe-preview" style="width:50px;height:50px;object-fit:cover" data-default-src="{{ asset('img/shoe.png') }}">
	</td>
	<td class="text-center">
		@if($purchaseEntry !== null)
			{{ input($errors, 'hidden', "purchases.{$index}.id", '', $purchaseEntry->id ?? '') }}
		@endif
		{{ checkbox($errors, "purchases.{$index}.new", '', false, 'input-new-shoe table-checkbox', ['disabled' => $purchaseEntry !== null]) }}
	</td>
	<td style="width:10%">
		{{ input($errors, 'text', "purchases.{$index}.shoe_id", '', $purchaseEntry->shoe_id ?? '', 'input-shoe-id enable-old number required-value', ['data-shoe-details' => route('ajax.shoe.show', ['shoe' => '#'])]) }}
		{{ error($errors, "purchases.{$index}.shoe-id") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'hidden', "purchases.{$index}.category_id", '', $purchaseEntry->shoe->category_id ?? '', 'input-category-id enable-new') }}
		{{ disabledInput($errors, 'text', "purchases.{$index}.category", '', $purchaseEntry->shoe->category->full_name ?? '', 'input-category enable-new required-value', ['data-datalist-id' => 'category-list', 'data-datalist' => route('datalist.category')]) }}
		{{ error($errors, "purchases.{$index}.category_id") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'hidden', "purchases.{$index}.color_id", '', $purchaseEntry->shoe->color_id ?? '', 'input-color-id enable-new') }}
		{{ disabledInput($errors, 'text', "purchases.{$index}.color", '', $purchaseEntry->shoe->color->name ?? '', 'input-color enable-new required-value', ['data-datalist-id' => 'color-list', 'data-datalist' => route('datalist.color')]) }}
		{{ error($errors, "purchases.{$index}.color_id") }}
	</td>
	<td>
		<label for="input-image-{{ $index }}" class="btn btn-{{ $purchaseEntry === null ? 'primary' : 'success' }} mb-0"><span class="fas fa-camera"></span></label>
		<input type="file" name="purchases[{{ $index }}][image]" id="input-image-{{ $index }}" class="input-image d-none enable-new required-value" disabled>
	</td>
	<td style="width:15%">
		{{ input($errors, 'text', "purchases.{$index}.count", '', $purchaseEntry->count ?? '',  'input-count number update-sum required-value') }}
		{{ error($errors, "purchases.{$index}.count") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', "purchases.{$index}.retail_price", '', toFixed($purchaseEntry->shoe->retail_price ?? ''), 'input-retail-price taka enable-new required-value') }}
		{{ error($errors, "purchases.{$index}.retail_price") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', "purchases.{$index}.purchase_price", '', toFixed($purchaseEntry->shoe->purchase_price ?? ''), 'input-purchase-price taka enable-new update-sum') }}
		{{ error($errors, "purchases.{$index}.purchase_price") }}
	</td>
	<td style="width:15%">
		{{ disabledInput($errors, 'text', "purchases.{$index}.total_purchase_price", '', toFixed($purchaseEntry->total_price ?? 0), 'input-total-purchase-price taka text-right') }}
		{{ error($errors, "purchases.{$index}.total_purchase_price") }}
	</td>
</tr>
<tr class="tr-sub oldshoe">
	<td colspan="3"></td>
	<td colspan="8">
		<div class="boxes">
			<span>বক্স:</span>

			@php
			 $boxes = App\Models\Gift::where('gift_type_id', '1')->get();
            $bags = App\Models\Gift::where('gift_type_id', '2')->get();
				

			@endphp
			@foreach($boxes as $box)
			<label><input type="radio" name="purchases[{{ $index }}][box_id]" value="{{ $box->id }}" class="ml-2 mr-1 enable-new input-box"{{ old("purchases[{$index}][box_id]", $purchaseEntry->shoe->box_id ?? '') == $box->id ? ' checked' : '' }} disabled>{{ $box->name }}</label>
			@endforeach
		</div>
		<div class="bags">
			<span>ব্যাগ:</span>
			@foreach($bags as $bag)
			<label><input type="radio" name="purchases[{{ $index }}][bag_id]" value="{{ $bag->id }}" class="ml-2 mr-1 enable-new input-bag"{{ old("purchases[{$index}][bag_id]", $purchaseEntry->shoe->bag_id ?? '') == $bag->id ? ' checked' : '' }} disabled>{{ $bag->name }}</label>
			@endforeach
		</div>
	</td>
</tr>