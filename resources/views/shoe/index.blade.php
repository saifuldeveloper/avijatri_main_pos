@extends('layouts.app', ['title' => 'ইনভেন্টরি'])

@section('content')
<h1>ইনভেন্টরি</h1>
<div class="row">
	<div class="col-md-10">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td style="width:10%">মোট মাল</td>
					<td class="text-center" style="width:15%"><strong id="stock">{{ $stock }}</strong></td>
					<td style="width:15%">গায়ের দামে মাল</td>
					<td class="text-center" style="width:20%"><strong id="stock-purchase-price">{{ toFixed($total_retail_price) }}</strong></td>
					<td style="width:15%">ডজন দামে মাল</td>
					<td class="text-center" style="width:30%"><strong id="stock-purchase-price">{{ toFixed($stock_purchase_price) }}</strong></td>
				</tr>
				<div id="error-message"></div>


			</tbody>
		</table>
	</div>
</div>
<form action="{{ route('shoe.index') }}" method="GET" id="search-form"></form>
<table id="inventory-table" class="table table-striped">
	<thead>
		<tr class="sort">
			<th class="text-center">চেক </th>
			<th class="text-center" style="width:10%"><a href="{{ orderUrl('id', $orderby, $order) }}">আইডি {{ orderArrow('id', $orderby, $order) }}</a></th>
			<th class="text-center" style="width:12.5%"><a href="{{ orderUrl('factory', $orderby, $order) }}">মহাজন {{ orderArrow('factory', $orderby, $order) }}</a></th>
			<th class="text-center" style="width:12.5%"><a href="{{ orderUrl('category', $orderby, $order) }}">টাইপ {{ orderArrow('category', $orderby, $order) }}</a></th>
			<th class="text-center" style="width:10%"><a href="{{ orderUrl('color', $orderby, $order) }}">রং {{ orderArrow('color', $orderby, $order) }}</a></th>
			<th class="text-center">ছবি</th>
			<th class="text-center" style="width:12.5%"><a href="{{ orderUrl('retail_price', $orderby, $order) }}">গায়ের দাম {{ orderArrow('retail_price', $orderby, $order) }}</a></th>
			<th class="text-center" style="width:12.5%"><a href="{{ orderUrl('purchase_price', $orderby, $order) }}">ডজন দাম {{ orderArrow('purchase_price', $orderby, $order) }}</a></th>
			<th class="text-center" style="width:10%"><a href="{{ orderUrl('count', $orderby, $order) }}">জোড়া {{ orderArrow('count', $orderby, $order) }}</a></th>
			<th style="width:10%"><a href="{{ route('show.download') }}" id="download-images-btn">ডাউনলোড</a></th>
			<th style="width:10%"><a  href="{{ orderUrl('stock', $orderby, $order) }}">স্টক</a></th>
		</tr>
		<tr>
			<th><input type="checkbox" name="check_all" id="check_all"></th>
			<th><input type="text" name="id" class="form-control text-center search-id" value="{{ request()->input('id') }}" form="search-form"></th>
			<th><input type="text" name="factory" class="form-control text-center search-factory" value="{{ request()->input('factory') }}" form="search-form" data-datalist="{{ route('datalist.factory') }}"></th>
			<th><input type="text" name="category" class="form-control text-center search-category" value="{{ request()->input('category') }}" form="search-form" data-datalist="{{ route('datalist.category') }}"></th>
			<th><input type="text" name="color" class="form-control text-center search-color" value="{{ request()->input('color') }}" form="search-form" data-datalist="{{ route('datalist.color') }}"></th>
			<th></th>
			<th><input type="text" name="retail_price" class="form-control text-center search-retail-price number" value="{{ request()->input('retail_price') }}" form="search-form"></th>
			<th><input type="text" name="purchase_price" class="form-control text-center search-purchase-price number" value="{{ request()->input('purchase_price') }}" form="search-form"></th>
			<th><input type="text" name="count" class="form-control text-center search-count number" value="{{ request()->input('count') }}" form="search-form"></th>
			<th></th>
			<th><input type="checkbox" class="search-count " name="stock" id="stock" form="search-form" style="height:35px;width:30px"></th>
			<th>
				<!--<button type="submit" class="btn btn-primary form-control" form="search-form">খোঁজ করুন</button>-->
				<input type="hidden" name="orderby" value="{{ $orderby }}" form="search-form">
				<input type="hidden" name="order" value="{{ $order }}" form="search-form">
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($shoes as $shoe)
		<tr>
			<td><input type="checkbox" class="selected_shoes" name="selected_shoes[]" value="{{ $shoe->id }}"></td>
			<td class="text-center"><a href="{{ route('shoe.show', compact('shoe')) }}">{{ $shoe->id }}</a></td>
			<td class="text-center">{{ $shoe->factory }}</td>
			<td class="text-center">{{ $shoe->category }}</td>
			<td class="text-center">{{ $shoe->color }}</td>
			<td class="text-center">
				@include('templates.thumbnail-preview', ['href' => $shoe->full_image_url, 'small_thumbnail' => $shoe->image_url, 'preview' => $shoe->preview_url])
			</td>
			<td class="text-center">{{ toFixed($shoe->retail_price) }}</td>
			<td class="text-center">{{ $shoe->purchase_price > 0 ? toFixed($shoe->purchase_price) : 'পেন্ডিং' }}</td>
			<td class="text-center">{{ $shoe->count }}</td>
			<td></td>
			<td>
				<a href="{{ route("shoe.edit", $shoe->id) }}" class="btn btn-primary btn-sm{{ isset($http) ? '' : ' btn-edit' }}"<?php if(!isset($http)): ?> data-toggle="modal" data-target="#shoe-form"<?php endif; ?>>এডিট</a>
				{{-- @include('layouts.crud-buttons', ['model' => 'shoe', 'parameter' => 'shoe',   'object' => $shoe]) --}}
			</td>
			
		</tr>
		@endforeach
	</tbody>
</table>
<div id="pagination-wrapper">
	{{ $shoes->links('pagination.search-form', ['form_id' => 'search-form']) }}
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
		<img src="" style="max-width:100%">
	</div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('js/commons/form-paginator.js') }}"></script>
<script src="{{ asset('js/shoe/index.js') }}"></script>
@endsection