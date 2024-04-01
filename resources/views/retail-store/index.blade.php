@extends('layouts.app', ['title' => 'বাকী খাতা'])

@section('content')
<h1>বাকী খাতা <small><a href="{{ route('retail-store.create') }}" class="btn-new" data-toggle="modal" data-target="#retail-store-form">নতুন পার্টি</a></small></h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:50%">নাম</th>
			<th style="width:25%">মোবাইল নং</th>
			<th style="width:25%">অপশন</th>
		</tr>
	</thead>
	<tbody>
		@foreach($regular as $retailStore)
		<tr>
			<td><a href="{{ route('retail-store.show', ['retail_store' => $retailStore->id]) }}">{{ $retailStore->name }}</a></td>
			<td>{{ $retailStore->mobile_no }}</td>
			<td>
				@include('layouts.crud-buttons', ['model' => 'retail-store',  'parameter' =>'retail_store',  'object' => $retailStore])
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<h3>খুচরা পার্টি</h3>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:50%">নাম</th>
			<th style="width:25%">মোবাইল নং</th>
			<th style="width:25%">অপশন</th>
		</tr>
	</thead>
	<tbody>
		@foreach($onetime as $retailStore)
		<tr>
			<td><a href="{{ route('retail-store.show', ['retail_store' => $retailStore->id]) }}">{{ $retailStore->name }}</a></td>
			<td>{{ $retailStore->mobile_no }}</td>
			<td>
				@include('layouts.crud-buttons', ['model' => 'retail-store',  'parameter' =>'retail_store',  'object' => $retailStore])
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

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