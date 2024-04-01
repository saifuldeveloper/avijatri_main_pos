@extends('layouts.app', ['title' => 'মহাজন খাতা ক্লোজিং'])

@section('content')
<h1>মহাজন খাতা ক্লোজিং</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td>
				নাম: <strong>{{ $accountBook->account->name }}</strong><br>
				ঠিকানা: <strong>{{ $accountBook->account->address }}</strong><br>
				মোবাইল নং: <strong>{{ $accountBook->account->mobile_no }}</strong>
			</td>
		</tr>
	</tbody>
</table>
@if(!$accountBook->open)
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:85%">এই খাতার ক্লোজিং সম্পন্ন হয়েছে। ক্লোজিং-এর তথ্য এডিট করা যাবে না।</td>
			<td style="width:15%"><!--<button type="button" id="edit-closing-button" class="btn btn-primary form-control">এডিট</button>--></td>
		</tr>
	</tbody>
</table>
@endif
<form action="{{ route('account-book.closing', compact('accountBook')) }}" method="post" id="memo-form">
	<fieldset id="closing-inputs" class="row"{{ $accountBook->open ? '' : ' disabled' }}>
		{{ csrf_field() }}
		<div class="col-md-6">
			<h2>হিসাব</h2>
			<table class="table table-striped">
				<thead>
					<tr>
						<th width="70%">বিবরণ</th>
						<th width="30%" class="text-right">টাকা</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>মাল</td>
						<td><input type="text" class="form-control text-right input-purchase" value="{{ toFixed($accountBook->total_purchase_price) }}" disabled></td>
					</tr>
					<tr>
						<td>ফেরত (-)</td>
						<td><input type="text" class="form-control text-right input-return" value="{{ toFixed($accountBook->total_return_amount) }}" disabled></td>
					</tr>
					<tr>
						<td>তাগাদা (-)</td>
						<td><input type="text" class="form-control text-right input-payment" value="{{ toFixed($accountBook->total_payment) }}" disabled></td>
					</tr>
					@if($accountBook->opening_balance > 0)
					<tr>
						<td>সাবেক (+)</td>
						<td><input type="text" class="form-control text-right input-previous-balance" value="{{ toFixed($accountBook->opening_balance) }}" disabled></td>
					</tr>
					@endif
					<tr>
						<?php $amount = $accountBook->balance_before_closing; ?>
						<td><strong>মোট দেনা</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-payable" value="{{ toFixed($accountBook->balance_before_closing) }}" disabled></td>
					</tr>
					<tr>
						<td>কমিশন</td>
						<td><input type="text" name="commission" class="form-control text-right input-commission update-sum taka" value="{{ $accountBook->open ? '' : toFixed($accountBook->commission) }}"></td>
					</tr>
					<tr>
						<?php if(!$accountBook->open) $amount -= $accountBook->commission; ?>
						<td><strong>কমিশন বাদে</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-commission-deducted" value="{{ toFixed($amount) }}" disabled></td>
					</tr>
					<tr>
						<td>স্টাফ</td>
						<td><input type="text" name="staff" class="form-control text-right input-staff update-sum taka" value="{{ $accountBook->open ? '' : toFixed($accountBook->staff) }}"></td>
					</tr>
					<tr>
						<?php if(!$accountBook->open) $amount -= $accountBook->staff; ?>
						<td><strong>সর্বমোট দেনা</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-remaining" value="{{ toFixed($amount) }}" disabled></td>
					</tr>
					<tr>
						<td>সর্বমোট পরিশোধ</td>
						<td><input type="text" class="form-control text-right input-total-paid" value="{{ toFixed($accountBook->total_closing_transaction_amount) }}" disabled></td>
					</tr>
					<tr>
						<td>চেকের মাধ্যমে পরিশোধ</td>
						<td><input type="text" class="form-control text-right input-total-cheque" value="{{ toFixed($accountBook->total_closing_cheque_amount) }}" disabled></td>
					</tr>
					<tr>
						<?php if(!$accountBook->open) $amount -= $accountBook->total_closing_payment; ?>
						<td><strong>বাকী</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-remaining" value="{{ toFixed($amount) }}" disabled></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<h2>পরিশোধ</h2>
			<table id="closing-payment-table" class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th width="70%">ব্যাংক</th>
						<th width="30%" class="text-right">টাকা</th>
					</tr>
				</thead>
				<tbody>
					@if($accountBook->closingTransactions->count() > 0)
					@foreach($accountBook->closingTransactions as $index => $transaction)
					@include('factory.closing-payment-tr', compact('transaction', 'bankAccounts', 'index'))
					@endforeach
					@else
					@include('factory.closing-payment-tr', compact('bankAccounts'))
					@endif
				</tbody>
				<tfoot>
					<tr>
						<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.factory.closing.payment') }}" data-index="1" data-parent-id="#closing-payment-table"><span class="fas fa-plus"></span></button></td>
						<td class="text-right">সর্বমোট পরিশোধ</td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-paid" value="{{ toFixed($accountBook->total_closing_transaction_amount) }}" disabled></td>
					</tr>
				</tfoot>
			</table>
			<h2>চেক প্রদান</h2>
			<table id="closing-cheque-table" class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th width="25%">চেক নং</th>
						<th width="40%">পরিশোধের তারিখ</th>
						<th width="35%" class="text-right">টাকা</th>
					</tr>
				</thead>
				<tbody>
					{{-- @if($accountBook->closingCheques->count() > 0) --}}
					@if($accountBook->closingTransactions && $accountBook->closingTransactions->count() > 0)
					@foreach($accountBook->closingCheques as $index => $cheque)
					@include('factory.closing-cheque-tr', compact('cheque', 'index'))
					@endforeach
					@else
					@include('factory.closing-cheque-tr')
					@endif
				</tbody>
				<tfoot>
					<tr>
						<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.factory.closing.cheque') }}" data-index="1" data-parent-id="#closing-cheque-table"><span class="fas fa-plus"></span></button></td>
						<td colspan="2" class="text-right">চেকের মাধ্যমে পরিশোধ</td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-cheque" value="{{ toFixed($accountBook->total_closing_cheque_amount) }}" disabled></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="col-12">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td width="80%">কারখানাদার {{ $accountBook->account->name }}-এর আর কোন পাওনা না থাকায় কারখানাদার খাতা ক্লোজ করা হলো।</td>
						<td width="20%"><button type="submit" class="btn btn-primary form-control disable-for-nonzero" disabled>ক্লোজ করুন</button></td>
					</tr>
				</tbody>
			</table>
		</div>
	</fieldset>
</form>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/commons/closing.js') }}"></script>
@endsection