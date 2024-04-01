<h5>{{ $factory === null ? 'নতুন মহাজন' : 'মহাজন এডিট' }}</h5>
<form action="{{ isset($factory) ? route('factory.update', ['factory' => $factory]) : route('factory.store') }}" method="POST" autocomplete="off">
	@if($factory !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<div class="form-group">
		<label for="factory-name">নাম</label>
		<input type="text" name="name" id="factory-name" class="form-control" value="{{ old('name', optional($factory)->name) }}" required>
	</div>
	<div class="form-group">
		<label for="factory-address">ঠিকানা</label>
		<input type="text" name="address" id="factory-address" class="form-control" value="{{ old('address', optional($factory)->address) }}" required>
	</div>
	<div class="form-group">
		<label for="factory-mobile-no">মোবাইল নং</label>
		<input type="text" name="mobile_no" id="factory-mobile-no" class="form-control" value="{{ old('mobile_no', optional($factory)->mobile_no) }}" required>
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>