@extends('layouts.app', ['title' => 'বাকী খাতা - ' . $retailStore->name])

@section('content')
<h1>বাকী খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:80%">
				নাম: <strong>{{ $retailStore->name }}</strong><br>
				মোবাইল নং: <strong>{{ $retailStore->mobile_no }}</strong>
			</td>
			<td style="width:20%">
				@include('layouts.crud-buttons', ['model' => 'retail-store', 'object' => $retailStore])
			</td>
		</tr>
	</tbody>
</table>

<div class="row">
	<div class="col-md-8">
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width:70%">তারিখ</th>
					<th style="width:30%" class="text-right">ব্যালেন্স</th>
				</tr>
			</thead>
			<tbody>
				{{-- @for($i = $retailStore->accountBooks->count() - 1; $i >= 0; $i--)
				<?php $accountBook = $retailStore->accountBooks[$i]; ?>
				<tr>
					<td><a href="{{ route('account-book.show', ['account_book' => $accountBook->id]) }}">{{ $accountBook->description }}</a></td>
					<td class="text-right">{{ toFixed($accountBook->description_balance) }}</td>
				</tr>
				@endfor --}}
				@foreach ($retailStore->accountBooks->reverse() as $accountBook)
					<tr>
						<td><a href="{{ route('account-book.show', ['account_book' => $accountBook]) }}">{{ $accountBook->description }}</a></td>
						<td class="text-right">{{ toFixed($accountBook->description_balance) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

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