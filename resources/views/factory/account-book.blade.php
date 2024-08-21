@extends('layouts.app', ['title' => 'মহাজন খাতা - ' . $accountBook->account->name])

@section('content')
<h1>মহাজন খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:40%">
				<p>নাম: <strong>{{ $accountBook->account->name }}</strong><br>
				ঠিকানা: <strong>{{ $accountBook->account->address }}</strong><br>
				মোবাইল নং: <strong>{{ $accountBook->account->mobile_no }}</strong></p>
				তারিখ: <strong>{{ $accountBook->description }}</strong><br>
			</td>
			<td style="width:45%">
				মোট মাল: <strong>{{ toFixed($accountBook->total_products_worth) }}</strong><br>
				তাগাদা: 
						@if ($purchase_amount != 0)
		               	{{ toFixed(($payment_amount + $return_amount) / $purchase_amount * 100, 2) }} %
		               @else 0.00 % @endif
				
			</td>
			<td style="width:15%">
				<a href="{{ route('account-book.closing-page', compact('accountBook')) }}" class="btn btn-success btn-sm">ক্লোজিং</a>
			</td>
		</tr>
	</tbody>
</table>

@if(!$accountBook->open)
<table class="table table-striped">
	<tbody>
		<tr><td>এই খাতার সকল পাওনা পরিশোধ করা হয়েছে।</td></tr>
	</tbody>
</table>
@endif
<table class="table table-striped table-bordered table-account-book text-center">
	<thead>
		<tr>
			<th>#</th>
			<th style="width:13%">তারিখ</th>
			<th style="width:6%">মেমো</th>
			<th style="width:7%">বিবরণ</th>
			<th style="width:10%">টাইপ</th>
			<th style="width:7%">রং</th>
			<th style="width:7%">জোড়া</th>
			<th style="width:10%">গায়ের দাম</th>
			<th style="width:10%">ডজন দাম</th>
			<th style="width:10%">মোট দাম</th>
			<th style="width:10%">তাগাদা</th>
			<th style="width:10%">ব্যালেন্স</th>
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
			<td>ক্লোজিং</td>
			<td class="text-left" colspan="5">কমিশন</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->commission) }}</td>
			<td>{{ toFixed($accountBook->description_balance) }}</td>
		</tr>
		@endif
		@if($accountBook->staff > 0)
		<tr>
			<td>{{ $inc++ }}</td>
			<td>-</td>
			<td>-</td>
			<td>ক্লোজিং</td>
			<td class="text-left" colspan="5">স্টাফ খরচ</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->staff) }}</td>
			<td>{{ toFixed($accountBook->description_balance + $accountBook->commission) }}</td>
		</tr>
		@endif
		@endif
		@foreach($entries as $i => $entry)
		<tr>
			<td>{{ $i + $inc }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			@if($entry->entry_type == 0)
			<td><a href="{{ route('purchase.show', ['purchase' => $entry->purchase_id]) }}">{{ $entry->purchase_id }}</a></td>
			<td>ক্রয়</td>
			<td>
				@php
					$purchaeEntry =App\Models\PurchaseEntry::with('shoe')->where('purchase_id' ,$entry->purchase_id)->get();
				@endphp
				 @php
				 $shownCategories = [];
			     @endphp
			 
			  @foreach ($purchaeEntry as $item)
				 @if (!in_array($item->shoe->category->name, $shownCategories))
					 {{ $item->shoe->category->name }}
					 @php $shownCategories[] = $item->shoe->category->name; @endphp
					 @if (!$loop->last)<br>@endif
				 @endif
			  @endforeach
			</td>
			<td>
				@php
				$shownColors = [];
			@endphp
			
			@foreach ($purchaeEntry as $item)
				@if (!in_array($item->shoe->color->name, $shownColors))
					{{ $item->shoe->color->name }}
					@php $shownColors[] = $item->shoe->color->name; @endphp
					@if (!$loop->last)<br>@endif
				@endif
			@endforeach
			</td>
			<td>{{ $entry->count }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 1)
			<td>-</td>
			<td>ফেরত</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ $entry->count }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@elseif($entry->entry_type == 2)
			<td>-</td>
			<td>তাগাদা{{ $entry->closing_id === null ? '' : ' (ক্লোজিং)' }}</td>
			<td class="text-left" colspan="5">{{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@else
			<td>-</td>
			<td>তোলা</td>
			<td class="text-left" colspan="5">{{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@endif
			<td>{{ toFixed($total_balance[$i]) }}</td>
		</tr>
		@endforeach
		@if($accountBook->entries->currentPage() == $accountBook->entries->lastPage()
			&& $accountBook->opening_balance != 0)
		<tr>
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left">-</td>
			<td class="text-left" colspan="5">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->opening_balance) }}</td>
		</tr>
		@endif
	</tbody>
</table>
{{ $accountBook->entries->links('pagination.default') }}
@endsection