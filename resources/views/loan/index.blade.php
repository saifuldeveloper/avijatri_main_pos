@extends('layouts.app', ['title' => 'হাওলাত খাতা'])

@section('content')
<h1>হাওলাত খাতা <small><a href="{{ route('loan.create') }}" class="btn-new" data-toggle="modal" data-target="#loan-form">নতুন হাওলাত খাতা</a></small></h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:60%">হাওলাত খাতা</th>
			<th style="width:40%">অপশন</th>
		</tr>
	</thead>
	<tbody>
		@foreach($loans as $loan)
		<tr>
			<td><a href="{{ route('loan.show', ['loan' => $loan]) }}">{{ $loan->name }}</a></td>
			<td>
				@include('layouts.crud-buttons', ['model' => 'loan', 'parameter' => 'loan',   'object' => $loan])
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

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