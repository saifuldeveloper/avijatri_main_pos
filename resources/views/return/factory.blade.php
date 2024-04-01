@extends('layouts.app', ['title' => 'মহাজন ফেরত'])

@php $index = 0; @endphp

@section('content')
<h1>মহাজন ফেরত</h1>
<form action="{{ route('return.factory') }}" method="POST" enctype="multipart/form-data" id="memo-form" autocomplete="off">
	{{ csrf_field() }}
	{{ input($errors, 'hidden', 'factory_id', 'memo-to-id', '') }}
	<button type="submit" name="submit" value="submit" class="btn-invisible"></button>
	<div class="row mb-3">
		<div class="col-md-9">
			{{ inputGroupBegin('memo-to', 'মহাজন') }}
			{{ input($errors, 'text', 'memo_to_name', 'memo-to', '', '', ['data-datalist-id' => 'factory-list', 'data-datalist' => route('datalist.factory'), 'autofocus' => true ]) }}
			{{ error($errors, 'retail_store_id') }}
			{{ inputGroupEnd() }}
		</div>
	</div>
	<fieldset id="form-table"{{ !$errors->has('returns.*.*') ? ' disabled' : '' }}>
		<table id="memo-table" class="table memo-input factory-return-table">
			<thead>
				<tr>
					<th></th>
					<th>আইডি</th>
					<th>টাইপ</th>
					<th>রং</th>
					<th>গায়ের দাম</th>
					<th>ডজন দাম</th>
					<th>জোড়া আছে</th>
					<th>জোড়া</th>
					<th>মোট দাম</th>
				</tr>
			</thead>
			<tbody>
				@if($errors->has('returns.*.*'))
				@foreach(old('returns') as $index => $oldvals)
					@include('return.tr-factory', compact('index', 'oldvals'))
				@endforeach
				@else
				@include('return.tr-factory', ['shoeTransaction' => null])
				@endif
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.return.factory') }}" data-index="{{ ++$index }}"><span class="fas fa-plus"></span></button></td>
					<td colspan="7"></td>
					<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
				</tr>
			</tfoot>
		</table>
	</fieldset>
</form>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/return/factory.js') }}"></script>
@endsection