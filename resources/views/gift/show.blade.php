@extends('layouts.app', ['title' => 'জুতার বিবরণ'])

@section('content')
<h1>গিফট  বিবরণ</h1>
<table class="table table-striped">
	<tbody>
		<tr>
			<td>
				<strong>নাম: </strong> <span id="shoe-category">{{ $gift->name }}</span><br>
				<strong> ক্রয়:</strong> <span id="shoe-category">{{ $gift->giftTransactions->where('type','purchase')->sum('count') }} জোড়া</span><br>
                <strong> বিক্রয় :</strong> <span id="shoe-category">{{ $gift->giftTransactions->where('type','sale')->sum('count') }} জোড়া</span><br>
                <strong>  জোলাপ :</strong> <span id="shoe-category">{{ $gift->giftTransactions->where('type','waste')->sum('count') }} জোড়া</span><br>
                <strong> স্টক :</strong> <span id="shoe-category">
                    @php
                     $stock =   $gift->giftTransactions->where('type','purchase')->sum('count') - 
                     ($gift->giftTransactions->where('type','sale')->sum('count') + $gift->giftTransactions->where('type','waste')->sum('count'))
                    @endphp
                  {{ $stock }}  জোড়া  
                </span><br>
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
				@foreach($gift->giftTransactions->where('type','purchase') as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td><a href="{{ route('gift-purchase.show', ['gift_purchase' => $entry->attachment_id]) }}">{{ $entry->attachment_id  }}</a></td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

    <div class="col-md-6">
		<h3>বিক্রয় </h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:25%">মেমো নং</th>
					<th style="width:25%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($gift->giftTransactions->where('type','sale') as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td><a href="{{ route('invoice.show', ['invoice' => $entry->attachment_id]) }}">{{ $entry->attachment_id  }}</a></td>
					<td>{{ $entry->count }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>



	<div class="col-md-6">
        <h3>জোলাপ </h3>
		<table class="table table-striped text-center">
			<thead>
				<tr>
					<th style="width:50%">তারিখ</th>
					<th style="width:25%">বিবরন</th>
					<th style="width:25%">জোড়া</th>
				</tr>
			</thead>
			<tbody>
				@foreach($gift->giftTransactions->where('type','waste') as $entry)
				<tr>
					<td>{{ $entry->created_at }}</td>
					<td>{{ $entry->description }}</td>
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
{{-- <div id="shoe-image-modal" class="modal fade shoe-image-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog text-center" role="document">
		<img src="{{asset('images/small-thumbnail/'. $shoe->image) }}">
	</div>
</div> --}}


<script>
	$(".shoe-image-link").click(function (e) {
        e.preventDefault();
        var originalImagePath = $(this).find("img").prop("src");
        $("#shoe-image-modal img").prop("src", originalImagePath);
    });
</script>
@endsection