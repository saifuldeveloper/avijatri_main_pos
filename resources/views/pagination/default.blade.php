@if ($paginator->lastPage() > 1)
<nav aria-label="Page navigation">
	<form class="form-inline justify-content-center">
		<a href="{{ $paginator->url($paginator->currentPage() - 1) }}" class="btn btn-outline-secondary{{ $paginator->currentPage() == 1 ? ' disabled' : '' }}">&larr; আগের পাতা</a>
		<div class="input-group mx-2">
			<div class="input-group-prepend">
				<div class="input-group-text border-secondary">পাতা</div>
			</div>
			<input type="number" name="page" class="form-control border-secondary border-right-0" min="1" max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}">
			<div class="input-group-append">
				<div class="input-group-text border-secondary border-left-0">/ {{ $paginator->lastPage() }}</div>
			</div>
		</div>
		<button type="submit" class="btn btn-secondary mr-2">দেখুন</button>
		<a href="{{ $paginator->url($paginator->currentPage() + 1) }}" class="btn btn-outline-secondary{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">পরের পাতা &rarr;</a>
	</form>
</nav>
@endif