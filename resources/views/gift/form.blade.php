<h5>{{ $gift === null ? 'নতুন গিফট' : 'গিফট এডিট' }}</h5>
<form action="{{ isset($gift) ? route('gift.update', ['gift' => $gift]) : route('gift.store') }}" method="POST" autocomplete="off">
	@if($gift !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<div class="form-group">
		<label for="gift-name">নাম</label>
		<input type="text" name="name" id="gift-name" class="form-control" value="{{ old('name', optional($gift)->name) }}">
	</div>
	<div class="form-group">
		<label for="gift-type-id">টাইপ</label>
		<select name="gift_type_id" id="gift-type-id" class="form-control">
			<option>(টাইপ)</option>
			@foreach($giftTypes as $giftType)
			<option value="{{ $giftType->id }}"{{ optional($gift)->gift_type_id === $giftType->id ? ' selected' : '' }}>{{ $giftType->name }}</option>
			@endforeach
		</select>
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>