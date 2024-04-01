@extends('layouts.app', ['title' => 'ইনভেন্টরি চেক'])

@section('content')
<h1>ইনভেন্টরি চেক <small>{{ $inventoryCheck->start_date }}</small></h1>
@if($inventoryCheck->complete)
	@include('inventory-check.resolve', compact('inventoryCheck'))
@else
	@include('inventory-check.check-form', compact('inventoryCheck'))
@endif
@endsection
@section('page-script')
<script type="text/javascript" src="{{ asset('js/inventory-check/show.js') }}"></script>
@endsection