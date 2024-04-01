@extends('layouts.app', ['title' => 'গিফট জোলাপ'])

@section('content')
<h1>গিফট জোলাপ</h1>
<form action="{{ route('waste.gifts') }}" method="post" class="row">
	@csrf
	<div class="col-md-2 form-group">
		<label for="waste-id">গিফট</label>
		{{ select($errors, "gift_id", 'waste-id', '', $gifts, '', ['required' => true]) }}
	</div>
	<div class="col-md-6 form-group">
		<label for="waste-description">বিবরণ</label>
		<input type="text" name="description" id="waste-description" class="form-control">
	</div>
	<div class="col-md-2 form-group">
		<label for="waste-count">সংখ্যা</label>
		<input type="text" name="count" id="waste-count" class="form-control" required>
	</div>
	<div class="col-md-2 form-group">
		<label>&nbsp;</label>
		<button type="submit" class="btn btn-primary form-control">জমা দিন</button>
	</div>
</form>

<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:15%" class="text-center">তারিখ</th>
			<th style="width:15%" class="text-center">গিফট</th>
			<th style="width:55%">বিবরণ</th>
			<th style="width:15%" class="text-center">সংখ্যা</th>
		</tr>
	</thead>
	<tbody>
		@foreach($wasteEntries as $wasteEntry)
		<tr>
			<td class="text-center">{{ $wasteEntry->created_at }}</td>
			<td class="text-center">{{ $wasteEntry->gift->name }}</td>
			<td>{{ $wasteEntry->description }}</td>
			<td class="text-center">{{ $wasteEntry->count }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $wasteEntries->links('pagination.default') }}
@endsection