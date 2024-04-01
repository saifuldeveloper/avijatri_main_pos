@php
if(!isset($key)) $key = 'id';
if(!isset($value)) $value = 'name';
@endphp
<datalist id="{{ $model }}-list" class="form-datalist">
	@foreach($list as $object)
	@if($model == 'retail-store' && isset($extend) && $extend === true)
	<option value="{{ $object->$value }}" data-id="{{ $object->$key }}" data-return-count="{{ $object->return_count }}" data-return-amount="{{ toFixed($object->return_amount) }}" data-other-costs="{{ toFixed($object->other_costs) }}" data-return-url="{{ $object->unlisted_return_url }}">
	@elseif($model == 'retail-closing')
	@if($object->previous_book === null || $object->previous_book->description_balance <= 0)
		@continue
	@endif
	<option value="{{ $object->$value }} (বাকী: {{ toFixed($object->previous_book->description_balance) }})" data-id="{{ $object->previous_book->id }}"></option>
	@else
	<option value="{{ $object->$value }}" data-id="{{ $object->$key }}">
	@endif
	@endforeach
</datalist>