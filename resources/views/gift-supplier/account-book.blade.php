@extends('layouts.app', ['title' => 'গিফট মহাজন খাতা - ' . $accountBook->giftSupplierAccount->name])

@section('content')
<h1>গিফট মহাজন খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:30%">
				নাম: <strong>{{ $accountBook->giftSupplierAccount->name }}</strong><br>
				ঠিকানা: <strong>{{ $accountBook->giftSupplierAccount->address }}</strong><br>
				মোবাইল নং: <strong>{{ $accountBook->giftSupplierAccount->mobile_no }}</strong>
			</td>
			<td style="width:50%">
				মোট দেনা: <strong>
					{{ isset($accountBook->giftSupplierAccount->current_balance[0]) ? toFixed($accountBook->giftSupplierAccount->current_balance[0]) : '0.00' }}
				</strong><br>
				তাগাদা: <strong>
					@if ($purchase_amount != 0)
		               	{{ toFixed($payment_amount / $purchase_amount * 100, 2) }} %
		            @else 0.00 % @endif
			</td>
			<td style="width:20%">
				<a href="{{ route('account-book.closing-page', compact('accountBook')) }}" class="btn btn-success btn-sm">ক্লোজিং</a>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped table-bordered table-account-book text-center">
	<thead>
		<tr>
			<th>#</th>
			<th style="width:10%">তারিখ</th>
			<th style="width:10%">টাইপ</th>
			<th style="width:5%">মেমো</th>
			<th class="text-left" style="width:28%">গিফট</th>
			<th style="width:10%">পরিমাণ</th>
			{{-- <th></th> --}}
			{{-- <th style="width:10%">দর</th> --}}
			<th style="width:10%">মোট দাম</th>
			<th style="width:10%">তাগাদা</th>
			<th style="width:10%">ব্যালেন্স</th>
		</tr>
	</thead>
	<tbody>
		@foreach($entries as $i => $entry)
		<tr>
			<td>{{ $i + 1 }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			@if($entry->entry_type == 0)
			<td>ক্রয়</td>
			<td><a href="{{ route('gift-purchase.show', ['gift_purchase' => $entry->gift_purchase_id]) }}">{{ $entry->gift_purchase_id }}</a></td>
			<td class="text-left">
				@foreach (json_decode($entry->gift_name) as $gift_name)
					{{ $gift_name }}
					@if (!$loop->last)
						, 
					@endif
				@endforeach
			</td>
			
			<td>{{ $entry->count }}</td>
			{{-- <td></td> --}}
			
			{{-- <td>{{ $entry->unit_price > 0 ? toFixed($entry->unit_price) : 'পেন্ডিং' }}</td> --}}
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 2)
			<td>  
				{{ isset($entry->closing_id) ? 'ক্লোজিং তাগাদা' : 'তাগাদা' }}


			</td>
			{{-- <td>-</td> --}}
			<td class="text-left" colspan="3">{{ $entry->account_name . ($entry->description === null ? '' : ' (' . $entry->description . ')') }}
			 
			
			</td>
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@elseif($entry->entry_type == 3)
			<td>তোলা</td>
			<td>-</td>
			<td class="text-left" colspan="3">{{ $entry->account_name . ($entry->description === null ? '' : ' (' . $entry->description . ')') }}</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@endif
			<td>{{ toFixed($total_balance[$i]) }}</td>
		</tr>
		@endforeach
		{{-- @if($entries->currentPage() == $entries->lastPage()
			&& $giftSupplier->current_book->opening_balance != 0)
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left" colspan="2">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($giftSupplier->current_book->balance) }}</td>
		@endif --}}
	</tbody>
</table>
{{ $entries->links('pagination.default') }}

{{-- <div id="gift-supplier-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="form-modal-title" class="modal-title">অপেক্ষা করুন ...</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div> --}}
@endsection