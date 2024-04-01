@extends('layouts.app', ['title' => 'ইনভেন্টরি চেক'])

@section('content')
<h1>ইনভেন্টরি চেক শুরু করতে চান?</h1>
<form action="{{ route('inventory-check.store') }}" method="post">
	{{ csrf_field() }}
	<input type="hidden" name="start_date" value="{{ \Carbon\Carbon::today()->format("Y-m-d") }}">
	<button type="submit" class="btn btn-primary">হ্যাঁ</button>
	<a href="{{ url()->previous() }}" class="btn btn-outline-primary">না</a>
</form>
@endsection