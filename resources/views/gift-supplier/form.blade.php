<h5>{{ $giftSupplier === null ? 'নতুন গিফট মহাজন' : 'গিফট মহাজন এডিট' }}</h5>
<form action="{{ isset($giftSupplier) ? route('gift-supplier.update', ['gift_supplier' => $giftSupplier]) : route('gift-supplier.store') }}" method="POST" autocomplete="off">
	@if($giftSupplier !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<div class="form-group">
		<label for="gift-supplier-name">নাম</label>
		<input type="text" name="name" id="gift-supplier-name" class="form-control" value="{{ old('name', optional($giftSupplier)->name) }}" required>
	</div>
	<div class="form-group">
		<label for="gift-supplier-address">ঠিকানা</label>
		<input type="text" name="address" id="gift-supplier-address" class="form-control" value="{{ old('address', optional($giftSupplier)->address) }}" required>
	</div>
	<div class="form-group">
		<label for="gift-supplier-mobile-no">মোবাইল নং</label>
		<input type="text" name="mobile_no" id="gift-supplier-mobile-no" class="form-control" value="{{ old('mobile_no', optional($giftSupplier)->mobile_no) }}" required>
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>