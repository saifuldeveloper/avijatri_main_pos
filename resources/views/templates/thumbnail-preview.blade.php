<div class="hover-preview">
	@if(isset($href))
	<a href="{{ $href }}" class="shoe-image-link" data-toggle="modal" data-target="#shoe-image-modal">
		<img src="{{ $small_thumbnail }}" height="60">
	</a>
	@else
		<img src="{{ $small_thumbnail }}" height="60">
	@endif
	<div class="preview-window">
		<img src="{{ $small_thumbnail }}" height="200">
	</div>
</div>