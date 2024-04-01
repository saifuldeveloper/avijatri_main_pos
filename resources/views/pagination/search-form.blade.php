@if ($paginator->lastPage() > 1)
<nav aria-label="Page navigation">
	<div class="form-inline justify-content-center">
		<button type="submit" id="goto-prev" class="btn btn-outline-secondary" form="{{ $form_id }}"{{ $paginator->currentPage() == 1 ? ' disabled' : '' }}>&larr; আগের পাতা</button>
		<div class="input-group mx-2">
			<div class="input-group-prepend">
				<div class="input-group-text border-secondary">পাতা</div>
			</div>
			<input type="number" name="page" id="goto-page" class="form-control border-secondary border-right-0" min="1" max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}" form="{{ $form_id }}">
			<div class="input-group-append">
				<div class="input-group-text border-secondary border-left-0">/ {{ $paginator->lastPage() }}</div>
			</div>
		</div>
		<button type="submit" class="btn btn-secondary mr-2" form="{{ $form_id }}">দেখুন</button>
		<button type="submit" id="goto-next" class="btn btn-outline-secondary" form="{{ $form_id }}"{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}>পরের পাতা &rarr;</button>
	</div>
</nav>
@endif