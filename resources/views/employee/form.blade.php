<h5>{{ $employee === null ? 'নতুন স্টাফ' : 'স্টাফ এডিট' }}</h5>
<form action="{{ isset($employee) ? route('employee.update', ['employee' => $employee]) : route('employee.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
	@if($employee !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<div class="form-group">
		<label for="employee-name">নাম</label>
		<input type="text" name="name" id="employee-name" class="form-control" value="{{ old('name', optional($employee)->name) }}">
	</div>
	<div class="form-group">
		<label for="employee-address">ঠিকানা</label>
		<input type="text" name="address" id="employee-address" class="form-control" value="{{ old('address', optional($employee)->address) }}">
	</div>
	<div class="form-group">
		<label for="employee-mobile-no">মোবাইল নং</label>
		<input type="text" name="mobile_no" id="employee-mobile-no" class="form-control" value="{{ old('mobile_no', optional($employee)->mobile_no) }}">
	</div>
	<div class="form-group">
		<label for="employee-image">ছবি</label><br>
		<input type="file" name="image" id="employee-image">
	</div>
	<div class="form-group">
		<label for="employee-limit">টাকা তোলার লিমিট</label>
		<input type="number" name="limit" id="employee-limit" class="form-control" value="{{ old('limit', optional($employee)->limit) }}">
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>