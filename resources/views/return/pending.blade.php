@extends('layouts.app', ['title' => 'পেন্ডিং ফেরত'])

@section('content')
<h1>পেন্ডিং ফেরত</h1>

@if($returnToFactoryEntries->count() > 0)
<h2>মহাজন ফেরত</h2>
<table class="table table-striped text-center">
	<thead>
		<tr>
			<th>#</th>
			<th width="5%">আইডি</th>
			<th width="20%" class="text-left">মহাজন</th>
			<th width="10%">টাইপ</th>
			<th width="10%">রং</th>
			<th width="10%">গায়ের দাম</th>
			<th width="10%">ডজন দাম</th>
			<th width="5%">জোড়া</th>
			<th width="30%"></th>
		</tr>
	</thead>
	<tbody>
		@foreach($returnToFactoryEntries as $i => $returnEntry)
		<tr>
			<td>{{ $i + 1 }}</td>
			@if($returnEntry->shoe === null)
			<td colspan="6" class="text-left">{{ $returnEntry->shoe_id }}</td>
			@else
			<td><a href="{{ route('shoe.show', ['shoe' => $returnEntry->shoe->id]) }}">{{ $returnEntry->shoe->id }}</a></td>
			<td class="text-left">{{ $returnEntry->accountBook->account->name ?? '??' }}<br>
				{{ $returnEntry->accountBook->account->address ?? '??' }}
			</td>
			<td>{{ $returnEntry->shoe->category->full_name }}</td>
			<td>{{ $returnEntry->shoe->color->name }}</td>
			<td>{{ $returnEntry->shoe->retail_price }}</td>
			<td>{{ $returnEntry->shoe->purchase_price }}</td>
			@endif
			<td>{{ $returnEntry->count }}</td>
			<td>
				@if($returnEntry->shoe !== null)
				<form action="{{ route('return.pending.factory') }}" method="post" class="form-inline">
					{{ csrf_field() }}
					<input type="hidden" name="id" value="{{ $returnEntry->id }}">
					<div class="btn-group" role="group">
						<button type="submit" name="accept" value="accept" class="btn btn-success btn-sm">নগদ</button>
						<button type="submit" name="accept" value="waste" class="btn btn-warning btn-sm">জোলাপ</button>
						<button type="submit" name="accept" value="reject" class="btn btn-danger btn-sm">বাতিল</button>
					</div>
				</form>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif

@if($returnFromRetailEntries->count() > 0)
<h2>পার্টি ফেরত</h2>
<table class="table table-striped text-center">
	<thead>
		<tr>
			<th>#</th>
			<th width="5%">আইডি</th>
			<th width="12%" class="text-left">পার্টি</th>
			<th width="15%" class="text-left">মহাজন </th>
			<th width="7%">টাইপ</th>
			<th width="7%">রং</th>
			<th width="7%">গায়ের দাম</th>
			<th width="7%">ডজন দাম</th>
			<th width="5%">জোড়া</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($returnFromRetailEntries as $i => $returnEntry)
		<tr>
			<td>{{ $i + 1 }}</td>
			@if($returnEntry->shoe === null)
			<td colspan="6" class="text-left">{{ $returnEntry->shoe_id }}</td>
			@else
			<td><a href="{{ route('shoe.show', ['shoe' => $returnEntry->shoe->id]) }}">{{ $returnEntry->shoe->id }}</a></td>
			<td class="text-left">{{ $returnEntry->accountBook->retailAccount->shop_name ?? '??' }}<br>
				{{ $returnEntry->accountBook->retailAccount->address ?? '??' }}<br>
			</td>
			<td class="text-left">{{ $returnEntry->shoe->factory->name}}<br>
				{{ $returnEntry->shoe->factory->address}}
			
			</td>
			<td>{{ $returnEntry->shoe->category->full_name }}</td>
			<td>{{ $returnEntry->shoe->color->name }}</td>
			<td>{{ $returnEntry->shoe->retail_price }}</td>
			<td>{{ $returnEntry->shoe->purchase_price }}</td>
			@endif
			<td>{{ $returnEntry->count }}</td>
			<td>
				@if($returnEntry->shoe !== null)
				<form action="{{ route('return.pending.retail-store') }}" method="post" class="form-inline">
					{{ csrf_field() }}
					<input type="hidden" name="id" value="{{ $returnEntry->id }}">
					<div class="btn-group" role="group">
						@if(strtolower(substr($returnEntry->shoe_id, 0, 1)) !== 'x')
						<button type="submit" name="destination" value="inventory" class="btn btn-primary btn-sm">ইনভেন্টরি</button>
					 	@endif
						<button type="submit" name="destination" value="factory-return" class="btn btn-secondary btn-sm">মহাজন ফেরত</button>
						<button type="submit" name="destination" value="waste" class="btn btn-warning btn-sm">জোলাপ</button>
						<button type="submit" name="destination" value="reject" class="btn btn-danger btn-sm">বাতিল</button>
					</div>
				</form>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif

@if($returnToFactoryEntries->count() == 0 && $returnFromRetailEntries->count() == 0)
<div class="alert alert-danger">কোন ফেরত পেন্ডিং নেই।</div>
@endif
@endsection