<h5>{{ $category === null ? 'নতুন ধরণ' : 'জুতার ধরণ এডিট' }}</h5>
<form action="{{ isset($category) ? route('category.update', ['category' => $category]) : route('category.store') }}" method="POST" class="form-inline" autocomplete="off">
	@if($category !== null)
	{{ method_field('PUT') }}
	@endif
	{{ csrf_field() }}
	<select name="parent_id" class="form-control" required>
		<option value="">(মূল ধরণ)</option>
		@foreach($parents as $parent)
		<option value="{{ $parent->id }}"{{ optional($category)->parent_id === $parent->id ? ' selected' : '' }}>{{ $parent->name }}</option>
		@endforeach
	</select>
	&nbsp;&mdash;&nbsp;
	<input type="text" name="name" class="form-control" placeholder="(নাম)" value="{{ old('name', optional($category)->name) }}" required>
	&nbsp;
	<button type="submit" class="btn btn-primary btn-form-save">সংরক্ষণ করুন</button>
</form>