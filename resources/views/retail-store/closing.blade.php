@extends('layouts.app', ['title' => 'বাকী খাতা ক্লোজিং'])

@section('content')
<h1>বাকী খাতা ক্লোজিং</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td>
				<p>নাম: <strong>{{ $accountBook->retailAccount->name }}</strong><br>
				মোবাইল নং: <strong>{{ $accountBook->retailAccount->mobile_no }}</strong></p>
				তারিখ: <strong>{{ $accountBook->description }}</strong>
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
						<th width="40%">বিবরণ</th>
						<th width="30%" class="text-right">গায়ের দামে টাকা</th>
						<th width="30%" class="text-right">কমিশন বাদে টাকা</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>মোট বিক্রি</td>
						<td><input type="text" class="form-control text-right input-sale-no-commission" disabled value="{{ toFixed($accountBook->total_sale) }}"></td>
						<td><input type="text" class="form-control text-right input-sale-commission" disabled value="{{ toFixed($accountBook->total_sale_minus_commission) }}"></td>
					</tr>
					<tr>
						<td>মোট ফেরত</td>
						<td><input type="text" class="form-control text-right input-return-no-commission" disabled value="{{ toFixed($accountBook->total_return_amount) }}"></td>
						<td><input type="text" class="form-control text-right input-return-commission" disabled value="{{ toFixed($accountBook->total_return_minus_commission) }}"></td>
					</tr>
					<tr>
						<td><strong>মোট</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-no-commission" disabled value="{{ toFixed($accountBook->total_sale - $accountBook->total_return_amount) }}"></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-commission" disabled value="{{ toFixed($accountBook->total_sale_minus_commission - $accountBook->total_return_minus_commission) }}"></td>
					</tr>
					<tr>
						<td colspan="2">তাগাদা (-)</td>
						<td><input type="text" class="form-control text-right input-total-payment" disabled value="{{ toFixed($accountBook->total_payment) }}"></td>
					</tr>
					<tr>
						<td colspan="2">পাঠানো (+)</td>
						<td><input type="text" class="form-control text-right input-total-transport" disabled value="{{ toFixed($accountBook->total_transport) }}"></td>
					</tr>
					<tr>
						<td colspan="2">অন্যান্য খরচ (-)</td>
						<td><input type="text" class="form-control text-right input-total-expense" disabled value="{{ toFixed($accountBook->total_expense) }}"></td>
					</tr>
					<tr>
						<td colspan="2">ডিসকাউন্ট (-)</td>
						<td><input type="text" class="form-control text-right input-total-discount" disabled value="{{ toFixed($accountBook->total_discount) }}"></td>
					</tr>
					<tr>
						<td colspan="2">সাবেক বাকী (+)</td>
						<td><input type="text" class="form-control text-right input-previous-balance" disabled value="{{ toFixed($accountBook->opening_balance) }}"></td>
					</tr>
					<tr>
						<?php $amount = $accountBook->balance_before_closing; ?>
						<td colspan="2"><strong>মোট বাকী</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-payable" disabled value="{{ toFixed($amount) }}"></td>
					</tr>
					<tr>
						<td colspan="2">কমিশন</td>
						<td><input type="text" name="commission" class="form-control text-right input-commission update-sum taka" value="{{ $accountBook->open ? '0.00' : toFixed($accountBook->commission) }}"></td>
					</tr>
					<tr>
						<?php if(!$accountBook->open) $amount -= $accountBook->commission; ?>
						<td colspan="2"><strong>কমিশন বাদে</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-commission-deducted" disabled value="{{ toFixed($amount) }}"></td>
					</tr>
					<tr>
						<td colspan="2">স্টাফ</td>
						<td><input type="text" name="staff" class="form-control text-right update-sum taka input-staff" value="{{ $accountBook->open ? '0.00' : toFixed($accountBook->staff) }}"></td>
					</tr>
					<tr>
						<?php if(!$accountBook->open) $amount -= $accountBook->staff; ?>
						<td colspan="2"><strong>সর্বমোট বাকী</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-remaining" disabled value="{{ toFixed($amount) }}"></td>
					</tr>
					<tr>
						<td colspan="2">সর্বমোট পরিশোধ</td>
						<td><input type="text" class="form-control text-right input-total-paid" disabled value="{{ toFixed($accountBook->total_closing_payment) }}"></td>
					</tr>
					<tr>
						<?php $amount -= $accountBook->total_closing_payment; ?>
						<td colspan="2"><strong>বাকী</strong></td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-remaining" disabled value="{{ toFixed($amount) }}"></td>
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
					@include('retail-store.closing-tr', compact('transaction', 'bankAccounts', 'index'))
					@endforeach
					@else
					@include('retail-store.closing-tr', compact('bankAccounts'))
					@endif
				</tbody>
				<tfoot>
					<tr>
						<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.retail-store.closing') }}" data-index="1" data-parent-id="#closing-payment-table"><span class="fas fa-plus"></span></button></td>
						<td class="text-right">সর্বমোট পরিশোধ</td>
						<td><input type="text" class="form-control text-right font-weight-bold input-total-paid" disabled value="{{ toFixed($accountBook->total_closing_payment) }}"></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="col-md-12">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td width="60%">
							{{ $accountBook->retailAccount->name }} এর মোট বাকী <strong><span class="span-total-remaining">{{ toFixed($amount) }}</span> টাকা</strong>।<br>
							<label class="mb-0"><input type="radio" name="balance_carry_forward" value="1"> বাকী পরবর্তী খাতায় যুক্ত হবে</label>
							<label class="mb-0"><input type="radio" name="balance_carry_forward" value="0" checked> বাকী পরবর্তী খাতায় যুক্ত হবে না</label>
						</td>
						<td width="20%"><!--<input type="date" name="deadline" class="form-control" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">--></td>
						<td width="20%"><button type="submit" class="btn btn-primary form-control">ক্লোজ করুন</button></td>
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