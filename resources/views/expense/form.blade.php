<h5>{{ $expense === null ? 'নতুন খরচের খাত' : 'খরচের খাত এডিট' }}</h5>
<form action="{{ isset($expense) ? route('expense.update', ['expense' => $expense]) : route('expense.store') }}" method="POST" class="form-inline" autocomplete="off">
	@if($expense !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<input type="text" name="name" class="form-control" placeholder="(নাম)" value="{{ old('name', optional($expense)->name) }}">
	&nbsp;
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>