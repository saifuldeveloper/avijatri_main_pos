@extends('layouts.app', ['title' => 'অন্যান্য খরচ খাতা - ' . $expense->name])

@section('content')
<h1>অন্যান্য খরচ খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:80%">
				নাম: <strong>{{ $expense->name }}</strong><br>
				মোট খরচ: <strong>{{ toFixed(@$expense->current_book->balance) }}</strong>
			</td>
			<td style="width:20%">
				@include('layouts.crud-buttons', ['model' => 'expense', 'parameter' => 'expense', 'object' => $expense])
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
			<th class="text-left" width="67%">বাবদ</th>
			<th width="12%">টাকা</th>
		</tr>
	</thead>
	<tbody>
		{{-- @foreach($expense->current_book->entries as $i => $entry)
		<tr>
			<td>{{ $i + 1 }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			<td class="text-left">{{ $entry->description }}</td>
			@if($entry->entry_type == 0)
			<td>{{ toFixed($entry->total_amount) }}</td>
			@else
			<td>{{ toFixed(-$entry->total_amount) }}</td>
			@endif
		</tr>
		@endforeach
		@if($expense->current_book->entries->currentPage() == $expense->current_book->entries->lastPage()
			&& $expense->current_book->opening_balance != 0)
		<tr>
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left">সাবেক</td>
			<td>{{ toFixed($expense->current_book->opening_balance) }}</td>
		</tr>
		@endif --}}
	</tbody>
</table>
{{-- {{ $expense->current_book->entries->links('pagination.default') }} --}}

<div id="expense-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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