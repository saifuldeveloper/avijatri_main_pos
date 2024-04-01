@extends('layouts.app', ['title' => 'জুতার ধরণ'])

@section('content')
<h1>জুতার ধরণ <small><a href="{{ route('category.create') }}" class="btn-new" data-toggle="modal" data-target="#category-form">নতুন ধরণ</a></small></h1>
<div class="row">
	@foreach($parents as $parent)
	<div class="col-md-3">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{{ $parent->name }}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($parent->children as $category)
				<tr>
					<td>{{ $category->name }}</td>
					<td class="text-right">
						@include('layouts.crud-buttons', ['model' => 'category',  'parameter' =>'category', 'object' => $category])
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	@endforeach
</div>

<div id="category-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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