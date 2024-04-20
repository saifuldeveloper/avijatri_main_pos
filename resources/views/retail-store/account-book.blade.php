@extends('layouts.app', ['title' => 'বাকী খাতা - ' . $accountBook->account->name])

@section('content')
<h1>বাকী খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:40%">
				<p>নাম: <strong>{{ $accountBook->retailAccount->shop_name }}</strong><br>
				মোবাইল নং: <strong>{{ $accountBook->retailAccount->mobile_no }}</strong></p>
				তারিখ: <strong>{{ $accountBook->description }}</strong><br>
			</td>
			<td style="width:45%">
				মোট বাকী: <strong>{{ toFixed($accountBook->description_balance) }}</strong><br>
				কমিশন বাদে মাল: <strong>{{ toFixed($accountBook->total_sale_minus_commission - $accountBook->total_return_minus_commission) }}</strong>
			</td>
			<td style="width:15%">
				<a href="{{ route('account-book.closing-page', compact('accountBook')) }}" class="btn btn-success btn-sm">ক্লোজিং</a>
			</td>
		</tr>
	</tbody>
</table>

@if($accountBook->open)
<form action="{{ route('retail-store-expense.store') }}" method="post" class="row">
	@csrf
	<input type="hidden" name="account_book_id" value="{{ $accountBook->id }}">
	<div class="form-group col-md-4">
		<label for="retail-expense-description">অন্যান্য খরচ</label>
		<input type="text" name="description" id="retail-expense-description" class="form-control" required>
	</div>
	<div class="form-group col-md-2">
		<label for="retail-expense-amount">টাকা</label>
		<input type="text" name="amount" id="retail-expense-amount" class="form-control number" required>
	</div>
	<div class="form-group col-md-2">
		<label>&nbsp;</label>
		<button type="submit" class="btn btn-primary form-control">সংরক্ষণ করুন</button>
	</div>
</form>
@else
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:80%">
				@if($accountBook->description_balance == 0)
				এই খাতার সমস্ত বাকী পরিশোধ করা হয়েছে।
				@elseif($accountBook->balance_carry_forward)
				এই খাতার মোট বাকী পরবর্তী খাতায় যুক্ত আছে।
				@else
				এই খাতার মোট বাকী নির্ধারিত সময়ে পরিশোধ করা না হলে পরবর্তী খাতায় যুক্ত হবে।
				@endif
			</td>
			<td style="width:20%">
				@if($accountBook->description_balance > 0)
				<a href="{{ route('account-book.forward-balance', compact('accountBook')) }}" class="btn btn-primary form-control">পরিবর্তন করুন</a>
				@endif
			</td>
		</tr>
	</tbody>
</table>
@endif
<table class="table table-striped table-bordered table-account-book text-center">
	<thead>
		<tr>
			<th rowspan="2">#</th>
			<th rowspan="2" style="width:11%">তারিখ</th>
			<th rowspan="2">মেমো নং</th>
			<th rowspan="2" style="width:7%">জোড়া</th>
			<th rowspan="2" style="width:7%">ফেরত</th>
			<th colspan="2">অন্যান্য খরচ</th>
			<th rowspan="2" style="width:9%">মোট গায়ের দাম</th>
			<th rowspan="2" style="width:9%">কমিশন বাদে দাম</th>
			<th rowspan="2" style="width:9%">জমা</th>
			<th rowspan="2" style="width:9%">মোট বিল</th>
			<th rowspan="2" style="width:9%">ব্যালেন্স</th>
		</tr>
		<tr>
			<th style="width:10%">বাবদ</th>
			<th style="width:10%">টাকা</th>
		</tr>
	</thead>
	<tbody>
		<?php $inc = 1; ?>
		@if(!$accountBook->open && $accountBook->entries->currentPage() == 1)
		@if($accountBook->commission > 0)
		<tr>
			<td>{{ $inc++ }}</td>
			<td>-</td>
			<td>-</td>
			<td class="text-left" colspan="6">ক্লোজিং কমিশন</td>
			<td>{{ toFixed($accountBook->commission) }}</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->description_balance) }}</td>
		</tr>
		@endif
		@if($accountBook->staff > 0)
		<tr>
			<td>{{ $inc++ }}</td>
			<td>-</td>
			<td>-</td>
			<td class="text-left" colspan="6">ক্লোজিং স্টাফ খরচ</td>
			<td>{{ toFixed($accountBook->staff) }}</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->description_balance + $accountBook->commission) }}</td>
		</tr>
		@endif
		@endif
		@foreach($accountBook->entries as $i => $entry)
		<tr>
			<td>{{ $i + $inc }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			@if($entry->entry_type == 0)
			<td><a href="{{ route('invoice.show', ['invoice' => $entry->invoice_id]) }}">{{ $entry->invoice_id }}</a></td>
			<td>{{ $entry->count }}</td>
			<td>{{ $entry->return_count }}</td>
			<td>{{ $entry->expense_description }}</td>
			<td>{{ $entry->expense_amount == 0 ? '-' : toFixed($entry->expense_amount) }}</td>
			<td>{{ toFixed($entry->total_retail_price) }}</td>
			<td>{{ toFixed($entry->total_retail_price - $entry->total_commission) }}</td>
			<td>{{ $entry->paid_amount == 0 ? '-' : toFixed($entry->paid_amount) }}</td>
			<td>{{ toFixed($entry->amount) }}</td>
			@elseif($entry->entry_type == 1)
			<td>-</td>
			<td>-</td>
			<td>{{ $entry->return_count }}</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($entry->paid_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 2)
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td>{{ $entry->expense_description }}</td>
			<td>{{ toFixed($entry->expense_amount) }}</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($entry->paid_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 3)
			<td>-</td>
			<td class="text-left" colspan="6">{{ ($entry->closing_id === null ? '' : 'ক্লোজিং তাগাদা: ') . $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>{{ toFixed($entry->paid_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 4)
			<td>-</td>
			<td class="text-left" colspan="6">{{ ($entry->closing_id === null ? '' : 'ক্লোজিং তাগাদা: ') . $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>-</td>
			<td>{{ toFixed($entry->amount) }}</td>
			@endif
			<td>{{ toFixed($entry->balance) }}</td>
		</tr>
		@endforeach
		@if($accountBook->entries->currentPage() == $accountBook->entries->lastPage()
			&& $accountBook->opening_balance != 0)
		<tr>
			<td>{{ ($i ?? -1) + 2 }}</td>
			<td>-</td>
			<td>-</td>
			<td class="text-left" colspan="6">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->opening_balance) }}</td>
		</tr>
		@endif
	</tbody>
</table>
{{ $accountBook->entries->links('pagination.default') }}
@endsection