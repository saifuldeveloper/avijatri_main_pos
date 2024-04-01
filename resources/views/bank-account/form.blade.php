<h5>{{ $bankAccount === null ? 'নতুন ব্যাংক অ্যাকাউন্ট' : 'ব্যাংক অ্যাকাউন্ট এডিট' }}</h5>
<form action="{{ isset($bankAccount) ? route('bank-account.update', ['bank_account' => $bankAccount]) : route('bank-account.store') }}" method="POST" autocomplete="off">
	@if($bankAccount !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<div class="form-group">
		<label for="bank-account-bank">ব্যাংক</label>
		<input type="text" name="bank" id="bank-account-bank" class="form-control" value="{{ old('bank', optional($bankAccount)->bank) }}" required>
	</div>
	<div class="form-group">
		<label for="bank-account-branch">শাখা</label>
		<input type="text" name="branch" id="bank-account-branch" class="form-control" value="{{ old('branch', optional($bankAccount)->branch) }}" required>
	</div>
	<div class="form-group">
		<label for="bank-account-account-no">অ্যাকাউন্ট নং</label>
		<input type="text" name="account_no" id="bank-account-account-no" class="form-control" value="{{ old('account_no', optional($bankAccount)->account_no) }}" required>
	</div>
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>