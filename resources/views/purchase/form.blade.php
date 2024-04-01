@extends('layouts.app', ['title' => 'জুতা ক্রয়'])

@php
if(!isset($purchase)) $purchase = null; $index = 0;
@endphp

@section('content')
<h1>জুতা ক্রয়</h1>
<form action="{{ $purchase === null ? route('purchase.store') : route('purchase.update', ['purchase' => $purchase]) }}" method="POST" enctype="multipart/form-data" id="memo-form" autocomplete="off">
	{{ csrf_field() }}
	@if($purchase !== null)
		{{ method_field('PUT') }}
	@endif
	{{ input($errors, 'hidden', 'factory_id', 'memo-to-id', $purchase->accountBook->account->id ?? '') }}
	<button type="submit" name="submit" value="submit" class="btn-invisible"></button>
	<div class="row mb-3">
		<div class="col-md-9">
			{{ inputGroupBegin('memo-to', 'মহাজন') }}
			@if($purchase == null)
			{{ input($errors, 'text', 'memo_to_name', 'memo-to', $purchase->accountBook->account->name ?? '', '', ['data-datalist-id' => 'factory-list', 'data-datalist' => route('datalist.factory'), 'disabled' => !($purchase->accountBook->open ?? true), 'autofocus' => ($purchase === null) ]) }}
			@else
			{{ disabledInput($errors, 'text', 'memo_to_name', 'memo-to', $purchase->accountBook->account->name ?? '', '', ['data-datalist-id' => 'factory-list', 'data-datalist' => route('datalist.factory'), 'disabled' => !($purchase->accountBook->open ?? true), 'autofocus' => ($purchase === null) ]) }}
			@endif
			{{ error($errors, 'factory_id') }}
			{{ inputGroupEnd() }}
		</div>
		<div class="col-md-3 input-group justify-content-end align-items-start">
			<div class="input-group-prepend">
				<span class="input-group-text bg-white">মেমো নং</span>
			</div>
			<div class="input-group-append">
				<span class="input-group-text bg-white"><strong>{{ $purchase->id ?? $memoNo }}</strong></span>
			</div>
		</div>
	</div>
	<fieldset id="form-table"{{ (!$errors->has('purchases.*.*') && $purchase === null) ? ' disabled' : '' }}>
		<table id="memo-table" class="table memo-input purchase-table" data-nextshoe="{{ $nextShoe }}">
			<thead>
				<tr>
					<th></th>
					<th></th>
					<th>নতুন?</th>
					<th>আইডি</th>
					<th>টাইপ</th>
					<th>রং</th>
					<th>ছবি</th>
					<th>জোড়া</th>
					<th>গায়ের দাম</th>
					<th>ডজন দাম</th>
					<th>মোট দাম</th>
				</tr>
			</thead>
			<tbody>
				@if($errors->has('purchases.*.*'))
				@foreach(old('purchases') as $index => $oldvals)
					@include('purchase.tr', compact('index', 'oldvals'))
				@endforeach
				@elseif($purchase !== null)
				@foreach($purchase->purchaseEntries as $index => $purchaseEntry)
					@include('purchase.tr', compact('index', 'purchaseEntry'))
				@endforeach
				@else
					@include('purchase.tr')
				@endif
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.purchase') }}" data-index="{{ ++$index }}"><span class="fas fa-plus"></span></button></td>
					<td colspan="9" class="text-right"><div class="form-control-plaintext">মোট</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($purchase->total_amount ?? 0), 'text-right input-total-payable') }}
					</td>
				</tr>
				@if($purchase === null)
				<tr>
					<td></td>
					<td colspan="9">
						<div class="form-inline justify-content-end">
							<label for="payment-method">তাগাদা</label>
							{{ select($errors, 'payment_method', 'payment-method', '', $bankAccounts, 'mx-3', ['required' => true, 'style' => 'width:200px']) }}
							<label for="cheque-no">চেক নং</label>
							{{ disabledInput($errors, 'text', 'cheque_no', 'cheque-no', old('cheque_no', ''), 'number mx-3', ['style' => 'width:100px']) }}
							<label for="cheque-date">পরিশোধের তারিখ</label>
							{{ disabledInput($errors, 'date', 'cheque_date', 'cheque-date', old('cheque_date', ''), 'mx-3', ['style' => 'width:150px']) }}
						</div>
					</td>
					<td>{{ input($errors, 'text', 'payment_amount', 'payment-amount', old('payment_amount', ''), 'text-right taka') }}</td>
				</tr>
				@endif
				<tr>
					<td colspan="9"></td>
					<td><button type="submit" name="submit" value="preview" class="btn btn-secondary form-control" formtarget="_blank">প্রিভিউ</button></td>
					<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
				</tr>
			</tfoot>
		</table>
	</fieldset>
</form>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/purchase/form.js') }}"></script>
@endsection