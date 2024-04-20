@extends('layouts.app', ['title' => 'জুতা বিক্রয়ের রশিদ'])

@section('content')
<h1>জুতা বিক্রয়ের রশিদ</h1>
<table class="table table-striped border">
	<tbody>
		<tr>
			<td style="width:70%">মেমো নং: <strong>{{ $invoice->id ?? $invoice_id }}</strong></td>
			<td style="width:30%">তারিখ: <strong>{{ dateFormat($invoice->created_at ?? date('Y-m-d H:i:s')) }}</strong></td>
		</tr>
		<tr>
			<td>
				নাম: <strong>{{ $invoice->accountBook->account->name ?? $retailStore->name }}</strong><br>
				<!--ঠিকানা: <strong>{{ $invoice->accountBook->account->address ?? $retailStore->address }}</strong><br>-->
				মোবাইল নং: <strong>{{ $invoice->accountBook->account->mobile_no ?? $retailStore->mobile_no }}</strong>
			</td>
			<td>
				<div class="d-print-none">
				<a href="{{ route('invoice.show', ['invoice' => $invoice]) }}" class="btn btn-success btn-sm">আইডি ছাড়া দেখুন</a>
				@include('layouts.crud-buttons', ['model' => 'invoice', 'parameter' =>'invoice',  'object' => $invoice, 'http' => false])
				</div>
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th class="text-center" style="width:15%">আইডি</th>
			<th class="text-center" style="width:20%">টাইপ</th>
			<th class="text-center" style="width:15%">রং</th>
			<th class="text-center" style="width:20%">গায়ের দাম</th>
			<th class="text-center" style="width:15%">জোড়া</th>
			<th class="text-right" style="width:15%">মোট</th>
		</tr>
	</thead>
	<tbody>
		@foreach($invoice->invoiceEntries as $i => $invoiceEntry)
		<tr>
			<td>
				@include('templates.thumbnail-preview', ['small_thumbnail' => $invoiceEntry->shoe->image_url, 'preview' => $invoiceEntry->shoe->preview_url])
			</td>
			<td class="text-center">{{ $invoiceEntry->shoe_id }}</td>
			<td class="text-center">{{ $invoiceEntry->shoe->category->full_name }}</td>
			<td class="text-center">{{ $invoiceEntry->shoe->color->name }}</td>
			<td class="text-center">{{ toFixed($invoiceEntry->shoe->retail_price) }}</td>
			<td class="text-center">{{ $invoiceEntry->count }}</td>
			<td class="text-right">{{ toFixed($invoiceEntry->total_price) }}</td>
		</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
			<td class="text-center">মোট</td>
			<td class="text-right"><strong>{{ toFixed($invoice->total_amount ?? $total_amount) }}</strong></td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">কমিশন ({{ $invoice->commission ?? $commission }}%)</td>
			<td class="text-right">(-) {{ toFixed($invoice->total_commission ?? $total_commission) }}</td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">কমিশন বাদে মোট</td>
			<td class="text-right"><strong>{{ toFixed($invoice->commission_deducted ?? $commission_deducted) }}</strong></td>
		</tr>
		@if($invoice->return_amount ?? $return_amount > 0)
		<tr>
			<td colspan="5">
			<td class="text-center">{{ $invoice->return_count ?? $return_count }} জোড়া ফেরত বাবদ</td>
			<td class="text-right">(-) {{ toFixed($invoice->return_amount ?? $return_amount) }}</td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">ফেরত বাদে মোট</td>
			<td class="text-right"><strong>{{ toFixed($invoice->return_deducted ?? $return_deducted) }}</strong></td>
		</tr>
		@endif
		@if($invoice->transport ?? $transport > 0)
		<tr>
			<td colspan="5">
			<td class="text-center">পাঠানোর খরচ</td>
			<td class="text-right">(+) {{ toFixed($invoice->transport ?? $transport) }}</td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">পাঠানোর খরচ সহ</td>
			<td class="text-right"><strong>{{ toFixed($invoice->transport_added ?? $transport_added) }}</strong></td>
		</tr>
		@endif
		@if($invoice->other_costs ?? $other_costs > 0)
		<tr>
			<td colspan="5">
			<td class="text-center">অন্যান্য খরচ</td>
			<td class="text-right">(-) {{ toFixed($invoice->other_costs ?? $other_costs) }}</td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">অন্যান্য খরচ বাদে</td>
			<td class="text-right"><strong>{{ toFixed($invoice->other_costs_deducted ?? $other_costs_deducted) }}</strong></td>
		</tr>
		@endif
		@if($invoice->discount ?? $discount > 0)
		<tr>
			<td colspan="5">
			<td class="text-center">ডিসকাউন্ট</td>
			<td class="text-right">(-) {{ toFixed($invoice->discount ?? $discount) }}</td>
		</tr>
		<tr>
			<td colspan="5">
			<td class="text-center">সর্বমোট</td>
			<td class="text-right"><strong>{{ toFixed($invoice->total_receivable ?? $total_receivable) }}</strong></td>
		</tr>
		@endif
		@if(isset($invoice) && $invoice->transaction !== null)
		<tr>
			<td colspan="5">
			<td class="text-center">জমা</td>
			<td class="text-right">{{ toFixed($invoice->transaction->amount) }}</td>
		</tr>
		@endif
		<tr>
			<td colspan="5"></td>
			<td class="text-center">সাবেক বাকী</td>
			<td class="text-right">(+) {{ toFixed($invoice->account_book_previous_balance ?? $previous_due) }}</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td class="text-center"><strong>মোট বাকী</strong></td>
			{{-- <td class="text-right"><strong>{{ toFixed($invoice->account_book_balance ?? $total_due) }}</strong></td> --}}
		</tr>
	</tfoot>
</table>
@if($invoice->giftTransactions->count() > 0)
<div class="row">
	<div class="col-md-6">
		<h3>গিফটের বিবরণ</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th style="width:80%">গিফট</th>
					<th style="width:20%" class="text-right">সংখ্যা</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				@foreach($invoice->giftTransactions as $giftTransaction)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $giftTransaction->gift->name }}</td>
					<td class="text-right">{{ $giftTransaction->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection