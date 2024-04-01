@extends('layouts.app', ['title' => 'স্টাফ খাতা'])

@section('content')
<h1>স্টাফ খাতা <small><a href="{{ route('employee.create') }}" class="btn-new" data-toggle="modal" data-target="#employee-form">নতুন স্টাফ</a></small></h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width:60%">স্টাফ</th>
			<th style="width:40%">অপশন</th>
		</tr>
	</thead>
	<tbody>
		@foreach($employees as $employee)
		<tr>
			<td><a href="{{ route('employee.show',  ['employee' => $employee->id]) }}">{{ $employee->name }}</a></td>
			<td>
				@include('layouts.crud-buttons', ['model' => 'employee', 'parameter' => 'employee',  'object' => $employee])
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
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