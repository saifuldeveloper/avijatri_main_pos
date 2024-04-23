@extends('layouts.app', ['title' => 'জুতা বিক্রয়'])

@php
if(!isset($invoice)) $invoice = null; $index = 0;
$retailStore = session()->get('retail-store', null);
@endphp

@section('content')
@if($retailStore !== null)
<h1>খুচরা বিক্রি</h1>
@else
<h1>
	জুতা বিক্রয়
	@if($invoice === null)
	<small><a href="{{ route('retail-store.create') }}?onetime=1" class="btn-new" data-toggle="modal" data-target="#retail-store-form">খুচরা বিক্রি</a></small>
	@endif
</h1>
@endif
<form action="{{ $invoice === null ? route('invoice.store') : route('invoice.update', ['invoice' => $invoice]) }}" method="POST" enctype="multipart/form-data" id="memo-form" autocomplete="off">
	{{ csrf_field() }}
	@if($invoice !== null)
		{{ method_field('PUT') }}
	@endif
	{{ input($errors, 'hidden', 'retail_store_id', 'memo-to-id', $invoice->accountBook->account->id ?? $retailStore->id ?? '') }}
	<button type="submit" name="submit" value="submit" class="btn-invisible"></button>
	<div class="row mb-3">
		<div class="col-md-9">
			{{ inputGroupBegin('memo-to', 'পার্টি') }}
			{{ input($errors, 'text', 'memo_to_name', 'memo-to', $invoice->accountBook->account->name ?? $retailStore->name ?? '', '', ['data-datalist-id' => 'retail-store-list', 'data-datalist' => route('datalist.retail-store') . '?extend=extend', 'disabled' => ($invoice === null ? ($retailStore->onetime_buyer ?? false) : ($invoice->accountBook->account->onetime_buyer || !$invoice->accountBook->open)), 'autofocus' => ($invoice === null) ]) }}
			{{ error($errors, 'retail_store_id') }}
			{{ inputGroupEnd() }}
		</div>
		<div class="col-md-3 input-group justify-content-end align-items-start">
			<div class="input-group-prepend">
				<span class="input-group-text bg-white">মেমো নং</span>
			</div>
			<div class="input-group-append">
				<span class="input-group-text bg-white"><strong>{{ $invoice->id ?? $memoNo }}</strong></span>
			</div>
		</div>
	</div>
	<fieldset id="form-table"{{ (!$errors->has('sales.*.*') && $invoice === null && $retailStore === null) ? ' disabled' : '' }}>
		<table id="memo-table" class="table memo-input invoice-table">
			<thead>
				<tr>
					<th></th>
					<th>আইডি</th>
					<th>টাইপ</th>
					<th>রং</th>
					<th>গায়ের দাম</th>
					<th>জোড়া আছে</th>
					<th>জোড়া</th>
					<th>মোট দাম</th>
				</tr>
			</thead>
			<tbody>
				@php $index = -1; @endphp
				@if($errors->has('sales.*.*'))
				@foreach(old('sales') as $index => $oldvals)
					@include('invoice.tr', compact('index', 'oldvals'))
				@endforeach
				@elseif($invoice !== null)
				@foreach($invoice->invoiceEntries as $index => $invoiceEntry)
					@include('invoice.tr', compact('index', 'invoiceEntry'))
				@endforeach
				@endif
				@include('invoice.tr', ['index' => ++$index, 'invoiceEntry' => null])
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.invoice') }}" data-index="{{ ++$index }}"><span class="fas fa-plus"></span></button></td>
					<td colspan="6" class="text-right"><div class="form-control-plaintext">মোট</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->total_amount ?? 0), 'text-right input-total-amount font-weight-bold') }}
					</td>
				</tr>
				<tr>
					<td colspan="5" class="text-right"><div class="form-control-plaintext">কমিশন (%)</div></td>
					<td>
						{{ input($errors, 'text', 'commission', '', toFixed($invoice->commission ?? 28), 'text-right input-commission taka update-sum') }}
					</td>
					<td class="text-right"><div class="form-control-plaintext">মোট কমিশন</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->total_commission ?? 0), 'text-right input-total-commission') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">কমিশন বাদে মোট</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->commission_deducted ?? 0), 'text-right input-commission-deducted font-weight-bold') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext"><span class="input-return-count">{{ $invoice->return_count ?? 0 }}</span> জোড়া ফেরত বাবদ</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->return_amount ?? 0), 'text-right input-return-amount') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">ফেরত বাদে মোট</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->return_deducted ?? 0), 'text-right input-return-deducted font-weight-bold') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">পাঠানোর খরচ</div></td>
					<td>
						{{ input($errors, 'text', 'transport', '', toFixed($invoice->transport ?? ''), 'text-right input-transport taka update-sum') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">অন্যান্য খরচ</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->other_costs ?? 0), 'text-right input-other-costs') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">ডিসকাউন্ট</div></td>
					<td>
						{{ input($errors, 'text', 'discount', '', toFixed($invoice->discount ?? ''), 'text-right input-discount taka update-sum') }}
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-right"><div class="form-control-plaintext">সর্বমোট</div></td>
					<td>
						{{ disabledInput($errors, 'text', '', '', toFixed($invoice->total_receivable ?? 0), 'text-right input-total-receivable font-weight-bold') }}
						<input type="hidden" name="total_amount" value="{{  toFixed($invoice->total_receivable ?? 0) }}" class="input-total-receivable">
					</td>
				</tr>
				<tr>
					<td colspan="8">
						<h3>জমার বিবরণ</h3>
						<table id="payment-table" class="table table-striped payment-table">
							<thead>
								<tr>
									<th></th>
									<th width="70%">মাধ্যম</th>
									<th width="15%">চেক নং</th>
									<th width="15%">টাকা</th>
								</tr>
							</thead>
							<tbody>
								@php
								$index = 0;
								@endphp
								@include('invoice.payment-tr', compact('index', 'bankAccounts'))
							</tbody>
							<tfoot>
								<tr>
									<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.payment') }}" data-index="{{ ++$index }}" data-parent-id="#payment-table"><span class="fas fa-plus"></span></button></td>
									<td colspan="2" class="text-right"><div class="form-control-plaintext">মোট জমা</div></td>
									<td>{{ disabledInput($errors, 'text', '', '', '0.00', 'input-total-payment text-right') }}</td>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
				<tr id="unlisted-returns" style="display:none">
					<td colspan="8">
					</td>
				</tr>
				<tr>
					<td colspan="8">
						<h3>গিফটের বিবরণ</h3>
						<table id="gift-table" class="table table-striped gift-table">
							<thead>
								<tr>
									<th></th>
									<th width="70%">গিফট</th>
									<th width="15%"></th>
									<th width="15%">সংখ্যা</th>
								</tr>
							</thead>
							<tbody>
								@php
								$index = 0;
								@endphp
								@include('invoice.gift-tr', compact('index', 'gifts'))
							</tbody>
							<tfoot>
								<tr>
									<td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.gift') }}" data-index="{{ ++$index }}" data-parent-id="#gift-table"><span class="fas fa-plus"></span></button></td>
									<td></td>
									<td><button type="submit" name="submit" value="preview" class="btn btn-secondary form-control" formtarget="_blank">প্রিভিউ</button></td>
									<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
				<!--<tr>
					<td colspan="6"></td>
					<td><button type="submit" name="submit" value="preview" class="btn btn-secondary form-control" formtarget="_blank">প্রিভিউ</button></td>
					<td><button type="submit" name="submit" value="submit" class="btn btn-primary form-control">সাবমিট</button></td>
				</tr>-->
			</tfoot>
		</table>
	</fieldset>
</form>

<div id="retail-store-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="form-modal-title" class="modal-title">অপেক্ষা করুন ...</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/memo.js') }}"></script>
<script src="{{ asset('js/invoice/form.js') }}"></script>
@endsection