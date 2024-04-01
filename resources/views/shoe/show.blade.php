@extends('layouts.app', ['title' => 'জুতার বিবরণ'])

@section('content')
<h1>জুতার বিবরণ</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td width="150"><a href="#" class="shoe-image-link" data-toggle="modal" data-target="#shoe-image-modal"><img src="{{asset('images/small-thumbnail/'. $shoe->image) }}" id="shoe-thumbnail"></a></td>
			<td>
				আইডি: <span id="shoe-factory"><strong>{{ $shoe->id }}</strong></span><br>
				মহাজন: <span id="shoe-factory">{{ $shoe->factory->name ?? '-' }}</span><br>
				টাইপ: <span id="shoe-category">{{ $shoe->category->full_name }}</span><br>
				রং: <span id="shoe-color">{{ $shoe->color->name }}</span><br>
				গায়ের দাম: <span id="shoe-retail-price">{{ toFixed($shoe->retail_price) }}</span><br>
				ডজন দাম: <span id="shoe-purchase-price">{{ toFixed($shoe->purchase_price) }}</span>
			</td>
			<td style="width:20%">
				@include('layouts.crud-buttons', ['model' => 'shoe', 'parameter' =>'shoe',  'object' => $shoe])
			</td>
		</tr>
	</tbody>
</table>
<div class="row">
	<div class="col-md-6">
		<h3>ক্রয়</h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:25%">মেমো নং</th>
					<th style="width:25%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($shoe->purchaseEntries as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td><a href="{{ route('purchase.show', ['purchase' => $entry->purchase_id]) }}">{{ $entry->purchase_id }}</a></td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<h3>মহাজন ফেরত</h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:50%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($shoe->acceptedFactoryReturnEntries as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h3>বিক্রয়</h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:25%">মেমো নং</th>
					<th style="width:25%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($shoe->invoiceEntries as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td><a href="{{ route('invoice.show', ['invoice' => $entry->invoice_id]) }}">{{ $entry->invoice_id }}</a></td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<h3>পার্টি ফেরত</h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:50%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($shoe->acceptedRetailReturnEntries as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
<div id="shoe-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title" aria-hidden="true">
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
<div id="shoe-image-modal" class="modal fade shoe-image-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog text-center" role="document">
		<img src="{{ $shoe->full_image_url }}" style="max-width:100%">
	</div>
</div>
@endsection