@if ($object->deleted_at == null)

@if($model !=='invoice') 
    <a href="{{ route("{$model}.edit", [$parameter => $object->id]) }}"
        class="btn btn-primary btn-sm{{ isset($http) ? '' : ' btn-edit' }}"<?php if(!isset($http)): ?> data-toggle="modal"
        data-target="#{{ $model }}-form"<?php endif; ?>>এডিট</a>
@endif

    <form action="{{ route("{$model}.destroy", [$parameter => $object->id]) }}" method="POST"
        class="delete-form d-inline">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <button type="submit" class="btn btn-danger btn-sm btn-delete">ডিলিট</button>
    </form>
@endif


@if ($object->trashed())
    <a href="{{ route("{$model}.restore", [$parameter => $object->id]) }}"
        class="btn btn-success btn-sm btn-restore">রিস্টোর</a>

    <a href="{{ route("{$model}.forceDelete", [$parameter => $object->id]) }}"
        onclick="return confirm('আপনি কি নিশ্চিত?')" class="delete-form btn btn-danger btn-sm btn-force-delete">ফোর্স
        ডিলিট</a>
@endif
