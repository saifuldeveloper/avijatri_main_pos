@if(!$inventoryCheck->resolved)
<form action="{{ route('inventory-check-entry.store') }}" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="inventory_check_id" value="{{ $inventoryCheck->id }}">
	<table class="table table-striped">
		<tbody>
			<tr>
				<td style="width:25%">
					<div class="form-group">
						<label for="shoe-check-id">আইডি</label>
						<input type="text" name="shoe_id" id="shoe-check-id" class="form-control number" data-shoe-details="{{ route('ajax.shoe.show', ['shoe' => '#']) }}" autofocus required>
					</div>
					<div class="form-group">
						<label for="shoe-check-count">জোড়া</label>
						<input type="text" name="count" id="shoe-check-count" class="form-control number" required>
					</div>
				</td>
				<td style="width:25%">
					<div class="form-group">
						<label for="shoe-check-available">জোড়া আছে</label>
						<input type="text" id="shoe-check-available" class="form-control" disabled>
					</div>
					<div class="form-group">
						<label for="shoe-check-remaining">জোড়া বাকি</label>
						<input type="text" id="shoe-check-remaining" class="form-control" disabled>
					</div>
				</td>
				<td style="width:50%">
					মহাজন : <span class="shoe-check-span" id="shoe-check-factory"></span><br>
					টাইপ: <span class="shoe-check-span" id="shoe-check-category"></span><br>
					রং: <span class="shoe-check-span" id="shoe-check-color"></span><br>
					গায়ের দাম: <span class="shoe-check-span" id="shoe-check-retail-price"></span><br>
					ডজন দাম: <span class="shoe-check-span" id="shoe-check-purchase-price"></span>
				</td>
				<td><img src="{{ asset('img/shoe.png') }}" id="shoe-check-thumbnail"></td>
			</tr>
			<tr>
				<td><button type="submit" class="btn btn-primary form-control">যোগ করুন</button></td>
				<td><a href="{{ route('inventory-check.complete', compact('inventoryCheck')) }}" class="btn btn-danger form-control btn-delete">চেক শেষ করুন</button></td>
				<td colspan="2"></td>
			</tr>
		</tbody>
	</table>
</form>
@endif


@if($inventoryCheck->partialMatchEntries->count() > 0)
<h2>আংশিক মিল</h2>
<table class="table table-striped text-center">
	<thead>
		<tr>
			<th>আইডি</th>
			<th width="20%">মহাজন </th>
			<th width="10%">টাইপ</th>
			<th width="10%">রং</th>
			<th width="10%">গায়ের দাম</th>
			<th width="10%">ডজন দাম</th>
			<th width="30%">জোড়া</th>
			<th width="10%">কম</th>
		</tr>
	</thead>
	<tbody>
		
		@foreach($inventoryCheck->partialMatchEntries as $entry)

	
		<tr id="shoe-{{ $entry->id }}" data-remaining="{{ $entry->remaining }}">
			<td>{{ $entry->id }}</td>
			<td>{{ $entry->factory }}</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>{{ $entry->total_count_breakdown }} = {{ $entry->count }}</td>
			<td>{{ $entry->remaining }}</td>
		</tr>
	
		@endforeach
	</tbody>
</table>
@endif


@if($inventoryCheck->extraMatchEntries->count() > 0)
<h2>অতিরিক্ত</h2>
<table class="table table-striped text-center">
	<thead>
		
		<tr>
			<th>আইডি</th>
			<th width="20%">মহাজন </th>
			<th width="10%">টাইপ</th>
			<th width="10%">রং</th>
			<th width="10%">গায়ের দাম</th>
			<th width="10%">ডজন দাম</th>
			<th width="30%">জোড়া</th>
			<th width="10%">অতিরিক্ত</th>
		</tr>

	</thead>
	<tbody>
		
		@foreach($inventoryCheck->extraMatchEntries as $entry)
		
		<tr id="shoe-{{ $entry->id }}" data-remaining="{{ $entry->remaining }}">
			<td>{{ $entry->id }}</td>
			<td>{{ $entry->factory }}</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>{{ $entry->total_count_breakdown }} = {{ $entry->count }}</td>
			<td>{{ -$entry->remaining }}</td>
		</tr>

		@endforeach
	</tbody>
</table>
@endif
@if($inventoryCheck->fullMatchEntries->count() > 0)
<h2>সম্পূর্ণ মিল</h2>
<table class="table table-striped text-center">
	<thead>
		<tr>
			<th>আইডি</th>
			<th width="20%">মহাজন </th>
			<th width="10%">টাইপ</th>
			<th width="10%">রং</th>
			<th width="10%">গায়ের দাম</th>
			<th width="10%">ডজন দাম</th>
			<th width="40%">জোড়া</th>
		</tr>
	</thead>
	<tbody>
		@foreach($inventoryCheck->fullMatchEntries as $entry)
	
		<tr id="shoe-{{ $entry->id }}" data-remaining="{{ $entry->remaining }}">
			<td>{{ $entry->id }}</td>
			<td>{{ $entry->factory }}</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>{{ $entry->total_count_breakdown }} = {{ $entry->count }}</td>
		</tr>

		@endforeach
	</tbody>
</table>
@endif