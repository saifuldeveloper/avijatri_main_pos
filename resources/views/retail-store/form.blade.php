<h5>{{ $retailStore === null ? ($one_time ? 'খুচরা বিক্রি' : 'নতুন পার্টি') : 'পার্টি এডিট' }}</h5>
<form action="{{ isset($retailStore) ? route('retail-store.update', ['retail_store' => $retailStore->id]) : route('retail-store.store') }}" method="POST" autocomplete="off">
	@if($retailStore !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	@if($retailStore === null && $one_time)
	<input type="hidden" name="onetime_buyer" value="1">
	@endif
	<div class="form-group">
		<label for="retail-store-name">নাম</label>
		<input type="text" name="shop_name" id="retail-store-name" class="form-control" value="{{ old('name', optional($retailStore)->name) }}" required>
	</div>
	<div class="form-group">
		<label for="retail-store-address">ঠিকানা</label>
		<input type="text" name="address" id="retail-store-address" class="form-control" value="{{ old('address', optional($retailStore)->address) }}" required>
	</div>
	<div class="form-group">
		<label for="retail-store-mobile-no">মোবাইল নং</label>
		<input type="text" name="mobile_no" id="retail-store-mobile-no" class="form-control" value="{{ old('mobile_no', optional($retailStore)->mobile_no) }}" required>
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>