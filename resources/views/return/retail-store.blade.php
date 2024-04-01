@extends('layouts.app', ['title' => 'পার্টি ফেরত'])

<style>
  .table td, .table th {
  vertical-align: top;
  border-top: 1px solid #dee2e6;
  padding: 10px 3px 8px !important;
}
</style>
@php $index = 0; @endphp

@section('content')
<h1>পার্টি ফেরত</h1>
<form action="{{ route('return.retail-store') }}" method="POST" enctype="multipart/form-data" id="memo-form" autocomplete="off">
	{{ csrf_field() }}
	{{ input($errors, 'hidden', 'retail_store_id', 'memo-to-id', '') }}
	<button type="submit" name="submit" value="submit" class="btn-invisible"></button>
	<div class="row mb-3">
		<div class="col-md-9">
			{{ inputGroupBegin('memo-to', 'পার্টি') }}
			{{ input($errors, 'text', 'memo_to_name', 'memo-to', '', '', ['data-datalist-id' => 'retail-store-list', 'data-datalist' => route('datalist.retail-store'), 'autofocus' => true ]) }}
			{{ error($errors, 'factory_id') }}
			{{ inputGroupEnd() }}
		</div>
	</div>
	<fieldset id="form-table"{{ !$errors->has('returns.*.*') ? ' disabled' : '' }}>
		<table id="memo-table" class="table memo-input retail-store-return-table" data-nextshoe="{{ $nextShoe }}">
			<thead>
				<tr>
					<th></th>
					<th>আইডি ছাড়া?</th>
					<th>আইডি</th>
					<th>মহাজন</th>
					<th>টাইপ</th>
					<th>রং</th>
					<th>ছবি</th>
					<th>গায়ের দাম</th>
					<th>ডজন দাম</th>
					<th>জোড়া</th>
					<th>কমিশন</th>
					<th>মোট দাম</th>
				</tr>
			</thead>
			<tbody>
				@if($errors->has('returns.*.*'))
				@foreach(old('returns') as $index => $oldvals)
					@include('return.tr-retail-store', compact('index', 'oldvals'))
				@endforeach
				@else
					@include('return.tr-retail-store')
				@endif
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.return.retail-store') }}" data-index="{{ ++$index }}"><span class="fas fa-plus"></span></button></td>
					<td colspan="9"></td>
					<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
				</tr>
			</tfoot>
		</table>
	</fieldset>
</form>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/return/retail-store.js') }}"></script>
@endsection