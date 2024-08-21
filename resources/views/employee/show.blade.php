@extends('layouts.app', ['title' => 'স্টাফ খাতা - ' . $employee->name])

@section('content')
<h1>স্টাফ খাতা</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			@if(!empty($employee->image))
			<td>
				<img src="{{ asset('images/staff-image/' . $employee->image) }} " style="height: 100px;">
			</td>
			@endif
			<td style="width:70%">
				নাম: <strong>{{ $employee->name }}</strong><br>
				ঠিকানা: <strong>{{ $employee->address }}</strong><br>
				মোবাইল নং: <strong>{{ $employee->mobile_no }}</strong><br>
				বর্তমান লিমিট: <strong>{{ $employee->limit }}</strong><br>
				বেতন : <strong>{{ $employee->salary }}</strong><br>
				মোট খরচ: <strong>{{ toFixed($total) }}</strong>
			</td>
			<td style="width:30%">
				@include('layouts.crud-buttons', ['model' => 'employee',  'parameter' => 'employee','object' => $employee])
				{{-- <a href="#" class="btn btn-success btn-sm">ক্লোজিং</a> --}}
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

		@foreach($entries as $i => $entry)
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
	</tbody>
</table>
{{ $entries->links('pagination.default') }}

<div id="employee-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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