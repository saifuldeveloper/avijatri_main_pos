<?php $i = 0; ?>
<form action="{{ route('inventory-check.resolve', compact('inventoryCheck')) }}" method="post">
	{{ csrf_field() }}
	@if($inventoryCheck->partialMatchEntries->count() > 0)
	<h2>আংশিক মিল</h2>
	<table class="table table-striped text-center">
		<thead>
			<tr>
				<th>আইডি</th>
				<th width="13%">মহাজন </th>
				<th width="10%">টাইপ</th>
				<th width="7%">রং</th>
				<th width="10%">ছবি</th>
				<th width="10%">গায়ের দাম</th>
				<th width="10%">ডজন দাম</th>
				<th width="10%">বাকি</th>
				<th class="text-left" width="30%">অপশন</th>
			</tr>
		</thead>
		<tbody>
			@foreach($inventoryCheck->partialMatchEntries as $entry)

			<tr id="shoe-{{ $entry->id }}" data-remaining="{{ $entry->remaining }}">
				<td>{{ $entry->id }}</td>
				<td>{{ $entry->factory }}</td>
				<td>{{ $entry->category }}</td>
				<td>{{ $entry->color }}</td>

				<td class="text-center"><a href="{{ asset('images/small-thumbnail/'.$entry->image) }}" class="shoe-image-link" data-toggle="modal" data-target="#shoe-image-modal"><img src="{{ asset('images/small-thumbnail/'.$entry->image) }}" height="60"></a></td>
				<td>{{ toFixed($entry->retail_price) }}</td>
				<td>{{ toFixed($entry->purchase_price) }}</td>
				<td>{{ $entry->remaining }}</td>
				<td class="text-left">
					<label class="mb-0"><input type="radio" name="resolve[{{ $i }}][action]" value="adjust" required> জোড়া ঠিক করুন</label>&nbsp;
					<label class="mb-0"><input type="radio" name="resolve[{{ $i }}][action]" value="cancel"> বাতিল</label>
					<input type="hidden" name="resolve[{{ $i }}][shoe_id]" value="{{ $entry->id }}">
					<input type="hidden" name="resolve[{{ $i }}][count]" value="{{ -$entry->remaining }}">
				</td>
			</tr>
			<?php $i++; ?>
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
				<th width="13%">মহাজন </th>
				<th width="10%">টাইপ</th>
				<th width="7%">রং</th>
				<th width="10%">ছবি</th>
				<th width="10%">গায়ের দাম</th>
				<th width="10%">ডজন দাম</th>
				<th width="10%">অতিরিক্ত</th>
				<th class="text-left" width="30%">অপশন</th>
			</tr>
		</thead>
		<tbody>
			@foreach($inventoryCheck->extraMatchEntries as $entry)
			<tr id="shoe-{{ $entry->id }}" data-remaining="{{ $entry->remaining }}">
				<td>{{ $entry->id }}</td>
				<td>{{ $entry->factory }}</td>
				<td>{{ $entry->category }}</td>
				<td>{{ $entry->color }}</td>
				<td class="text-center"><a href="{{ $entry->full_image_url }}" class="shoe-image-link" data-toggle="modal" data-target="#shoe-image-modal"><img src="{{ $entry->image_url }}"></a></td>
				<td>{{ toFixed($entry->retail_price) }}</td>
				<td>{{ toFixed($entry->purchase_price) }}</td>
				<td>{{ -$entry->remaining }}</td>
				<td class="text-left">
					<label class="mb-0"><input type="radio" name="resolve[{{ $i }}][action]" value="adjust" required> জোড়া ঠিক করুন</label>&nbsp;
					<label class="mb-0"><input type="radio" name="resolve[{{ $i }}][action]" value="cancel"> বাতিল</label>
					<input type="hidden" name="resolve[{{ $i }}][shoe_id]" value="{{ $entry->id }}">
					<input type="hidden" name="resolve[{{ $i }}][count]" value="{{ -$entry->remaining }}">
				</td>
			</tr>
			<?php $i++; ?>
			@endforeach
		</tbody>
	</table>
	@endif
	<button type="submit" class="btn btn-primary">ইনভেন্টরি সমন্বয় করুন</button>
</form>
<div id="shoe-image-modal" class="modal fade shoe-image-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog text-center" role="document">
		<img src="" style="max-width:500px">
	</div>
</div>