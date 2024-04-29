@extends('layouts.app', ['title' => 'বারকোড প্রিন্ট'])

@section('content')
<h1>বারকোড প্রিন্ট</h1>
<h3>মেমো থেকে প্রিন্ট</h3>
<form action="{{ route('purchase.index') }}" method="get" class="form-inline mb-3">
	<input type="text" name="id" class="form-control mr-2 number" placeholder="মেমো নং">
	<button type="submit" class="btn btn-primary">ক্রয় মেমো দেখুন</button>
</form>
<h3>আইডি থেকে প্রিন্ট</h3>
<form action="{{ route('shoe.barcode') }}" method="POST" autocomplete="off">
	@csrf
	<table id="memo-table" class="table memo-input barcode-table">
		<thead>
			<tr>
				<th></th>
				<th>আইডি</th>
				<th>টাইপ</th>
				<th>রং</th>
				<th>গায়ের দাম</th>
				<th>জোড়া</th>
			</tr>
		</thead>
		<tbody>
			@include('barcode.tr')
		</tbody>
		<tfoot>
			<tr>
				<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.barcode') }}" data-index="1"><span class="fas fa-plus"></span></button></td>
				<td colspan="3"></td>
				<td>
					<input type="radio" name="code" value="barcode" required>
					<label>বারকোড</label>
					<input type="radio" name="code" value="qr_code">
					<label>কিউ আর কোড  </label>
				</td>
				<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
			</tr>
		</tfoot>
	</table>
</form>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/barcode/page.js') }}"></script>
@endsection