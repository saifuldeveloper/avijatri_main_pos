<a href="{{ route("{$model}.edit", [$parameter => $object->id]) }}" class="btn btn-primary btn-sm{{ isset($http) ? '' : ' btn-edit' }}"<?php if(!isset($http)): ?> data-toggle="modal" data-target="#{{ $model }}-form"<?php endif; ?>>এডিট</a>
<form action="{{ route("{$model}.destroy", [$parameter => $object->id]) }}" method="POST" class="delete-form d-inline">
	{{ method_field('DELETE') }}
	{{ csrf_field() }}
	<button type="submit" class="btn btn-danger btn-sm btn-delete">ডিলিট</button>
</form>