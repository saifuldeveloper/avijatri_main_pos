@foreach($shoes as $shoe)
<tr>
	<td><input type="checkbox" name="selected_shoes[]" value="{{ $shoe->id }}"></td>
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
	<td>
		{{-- @include('layouts.crud-buttons', ['model' => 'shoe', 'parameter' => 'shoe',    'object' => $shoe]) --}}
		<a href="{{ route("shoe.edit", $shoe->id) }}" class="btn btn-primary btn-sm{{ isset($http) ? '' : ' btn-edit' }}"<?php if(!isset($http)): ?> data-toggle="modal" data-target="#shoe-form"<?php endif; ?>>এডিট</a>
	</td>
</tr>
@endforeach