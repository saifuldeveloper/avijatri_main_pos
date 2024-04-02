@extends('layouts.app', ['title' => 'গিফট ক্রয়ের রশিদ'])

@section('content')
<h1>গিফট ক্রয়ের রশিদ</h1>
<table class="table table-striped border">
	<tbody>
		<tr>
			<td style="width:80%">মেমো নং: <strong>{{ $giftPurchase->id }}</strong></td>
			<td style="width:20%">তারিখ: <strong>{{ dateFormat($giftPurchase->created_at) }}</strong></td>
		</tr>
		<tr>
			<td>
				নাম: <strong>{{ $giftPurchase->accountBook->supplierAccount->name }}</strong><br>
				ঠিকানা: <strong>{{ $giftPurchase->accountBook->supplierAccount->address }}</strong><br>
				মোবাইল নং: <strong>{{ $giftPurchase->accountBook->supplierAccount->mobile_no }}</strong>
			</td>
			<td>
				<div class="d-print-none">
				@include('layouts.crud-buttons', ['model' => 'gift-purchase', 'parameter' =>'gift_purchase', 'object' => $giftPurchase, 'http' => false])
				</div>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>বিবরণ</th>
			<th class="text-center">পরিমাণ</th>
			<th class="text-center">দর</th>
			<th class="text-right">টাকা</th>
		</tr>
	</thead>
	<tbody>
		@foreach($giftPurchase->giftTransactions as $i => $giftTransaction)
		<tr>
			<td>{{ $i + 1 }}</td>
			<td style="width:64%">{{ $giftTransaction->gift->name }}</td>
			<td class="text-center" style="width:12%">{{ $giftTransaction->count }}</td>
			<td class="text-center" style="width:12%">{{ $giftTransaction->unit_price > 0 ? toFixed($giftTransaction->unit_price) : 'পেন্ডিং' }}</td>
			<td style="width:12%" class="text-right">{{ toFixed($giftTransaction->amount) }}</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"></td>
			<td class="text-right">মোট = <strong>{{ toFixed($giftPurchase->total_amount) }}</strong></td>
		</tr>
	</tfoot>
</table>
@endsection