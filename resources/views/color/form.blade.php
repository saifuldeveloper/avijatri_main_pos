<h5>{{ $color === null ? 'নতুন রং' : 'রং এডিট' }}</h5>
<form action="{{ isset($color) ? route('color.update', ['color' => $color]) : route('color.store') }}" method="POST" class="form-inline" autocomplete="off">
	@if($color !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<input type="text" name="name" class="form-control" placeholder="(নাম)" value="{{ old('name', optional($color)->name) }}" required>
	&nbsp;
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>