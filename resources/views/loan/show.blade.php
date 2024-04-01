@extends('layouts.app', ['title' => 'হাওলাত খাতা - ' . $loan->name])

@section('content')
<h1>হাওলাত খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td style="width:80%">
				নাম: <strong>{{ $loan->name }}</strong><br>
				মোট পাওনা: <strong>{{ toFixed(@$loan->current_book->balance) }}</strong>
			</td>
			<td style="width:20%">
				@include('layouts.crud-buttons', ['model' => 'loan', 'parameter' => 'loan',  'object' => $loan])
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
			<th class="text-left" width="52%">বিবরণ</th>
			<th width="10%">হাওলাত</th>
			<th width="10%">পরিশোধ</th>
			<th width="10%">ব্যালেন্স</th>
		</tr>
	</thead>
	<tbody>
		{{-- @foreach($loan->current_book->entries as $i => $entry)
		<tr>
			<td>{{ $i + 1 }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			<td class="text-left">{{ $entry->description === null ? '-' : $entry->description }}</td>
			@if($entry->entry_type == 0)
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@else
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@endif
			<td>{{ toFixed($entry->balance) }}</td>
		</tr>
		@endforeach
		@if($loan->current_book->entries->currentPage() == $loan->current_book->entries->lastPage()
			&& $loan->current_book->opening_balance != 0)
		<tr>
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($loan->current_book->opening_balance) }}</td>
		</tr>
		@endif --}}
	</tbody>
</table>
{{-- {{ $loan->current_book->entries->links('pagination.default') }} --}}

<div id="loan-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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