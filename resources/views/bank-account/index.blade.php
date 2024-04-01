@extends('layouts.app', ['title' => 'ব্যাংক খাতা'])

@section('content')
<h1>ব্যাংক খাতা <small><a href="{{ route('bank-account.create') }}" class="btn-new" data-toggle="modal" data-target="#bank-account-form">নতুন ব্যাংক অ্যাকাউন্ট</a></small></h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:40%">ব্যাংক</th>
			<th style="width:30%">অ্যাকাউন্ট নং</th>
			<th style="width:30%">অপশন</th>
		</tr>
	</thead>
	<tbody>
		@foreach($bankAccounts as $bankAccount)
		<tr>
			<td>
					<a href="{{ route('bank-account.show', ['bank_account' => $bankAccount->id]) }}">
					@if($bankAccount->account_no == 'cash')
					{{ $bankAccount->bank }}
					@else
					{{ $bankAccount->bank }}, {{ $bankAccount->branch }} শাখা
					@endif
				</a>
			</td>
			<td>
				@if($bankAccount->account_no == 'cash')
				-
				@else
				{{ $bankAccount->account_no }}
				@endif
			</td>
			<td>
				@include('layouts.crud-buttons', ['model' => 'bank-account',  'parameter' =>'bank_account', 'object' => $bankAccount])
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div id="bank-account-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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