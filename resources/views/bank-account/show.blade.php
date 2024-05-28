@extends('layouts.app', ['title' => 'ব্যাংক খাতা - ' . $bankAccount->name])

@section('content')
<h1>ব্যাংক খাতা</small></h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:80%">
				@if($bankAccount->account_no == 'cash')
				<strong>{{ $bankAccount->bank }}</strong><br>
				@else
				<strong>{{ $bankAccount->bank }}, {{ $bankAccount->branch }} শাখা</strong><br>
				অ্যাকাউন্ট নং: <strong>{{ $bankAccount->account_no }}</strong><br>
				@endif
				ব্যালেন্স: <strong>{{ isset($bankAccount->current_balance[0]) ? number_format($bankAccount->current_balance[0], 2) : '0.00' }}</strong>

			</td>
			<td style="width:20%">
				@include('layouts.crud-buttons', ['model' => 'bank-account',  'parameter' =>'bank_account'  ,   'object' => $bankAccount])
				<a href="#" class="btn btn-success btn-sm">ক্লোজিং</a>
			</td>
		</tr>
	</tbody>
</table>

<table class="table table-striped table-bordered table-account-book text-center">
	<thead>
		<tr>
			<th>#</th>
			<th width="18%">তারিখ</th>
			<th class="text-left" width="52%">বাবদ</th>
			<th width="10%">জমা</th>
			<th width="10%">খরচ</th>
			<th width="10%">ব্যালেন্স</th>
		</tr>
	</thead>
	<tbody>
		@foreach($bankAccount->entries as $i => $entry)
		<tr>
			<td>{{ $i + 1 }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			<td class="text-left">
				@if($entry->account_type == 'bank-account')
				@if(($bankAccount->account_no == 'cash' && $entry->entry_type == 1) || ($bankAccount->account_no != 'cash' && $entry->entry_type == 0))
				ক্যাশ জমা {{ $entry->description === null ? '' : ' (' . $entry->description . ')' }}
				@else
				ক্যাশ তোলা {{ $entry->description === null ? '' : ' (' . $entry->description . ')' }}
				@endif
				@else
				{{ $entry->account_name . ($entry->description === null ? '' : ' (' . $entry->description . ')') }}
				@endif
			</td>
			@if($entry->entry_type == 0)
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@else
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@endif
			<td>{{ toFixed($bankAccount->current_balance[$i]) }}</td>
		</tr>
		@endforeach
		{{-- @if($bankAccount->current_book->entries->currentPage() == $bankAccount->current_book->entries->lastPage()
			&& $bankAccount->current_book->opening_balance != 0)
		<tr>
			<td>{{ isset($i) ? $i + 2 : 1 }}</td>
			<td>-</td>
			<td class="text-left">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($bankAccount->current_book->opening_balance) }}</td>
		</tr>
		@endif --}}
	</tbody>
</table>
{{-- {{ $bankAccount->current_book->entries->links('pagination.default') }} --}}

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