<h5>{{ $loan === null ? 'নতুন হাওলাত খাতা' : 'হাওলাত খাতা এডিট' }}</h5>
<form action="{{ isset($loan) ? route('loan.update', ['loan' => $loan]) : route('loan.store') }}" method="POST" class="form-inline" autocomplete="off">
	@if($loan !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<input type="text" name="name" class="form-control" placeholder="(নাম)" value="{{ old('name', optional($loan)->name) }}">
	&nbsp;
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>