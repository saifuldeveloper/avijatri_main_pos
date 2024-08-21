@extends('layouts.app', ['title' => 'টাকা লেনদেনের হিসাব'])

@section('content')
    <h1>টাকা লেনদেনের হিসাব</h1>


    <form action="{{ route('report.transaction.daily') }}" method="get" class="form-inline mb-4">
        <span>প্রতিদিনের হিসাব </span>
        <input type="date" class="form-control mx-3" name="date">
        <button type="submit" class="btn btn-primary ml-3">ডাউনলোড</button>
    </form>


    <form action="{{ route('report.transaction.monthly') }}" method="get" class="form-inline mb-4">
        <span>মাসিক হিসাব</span>
        <label for="tm-year" class="sr-only">বছর</label>
        <select id="tm-year" name="year" class="form-control mx-3">
            <option value="">(বছর)</option>
            @foreach ($years as $year)
                <option value="{{ $year->yr }}">{{ $year->yr }}</option>
            @endforeach
        </select>
        <label for="tm-month" class="sr-only">মাস</label>
        <select id="tm-month" name="month" class="form-control mr-3">
            <option value="">(মাস)</option>
            <option value="1">জানুয়ারি</option>
            <option value="2">ফেব্রুয়ারি</option>
            <option value="3">মার্চ</option>
            <option value="4">এপ্রিল</option>
            <option value="5">মে</option>
            <option value="6">জুন</option>
            <option value="7">জুলাই</option>
            <option value="8">আগস্ট</option>
            <option value="9">সেপ্টেম্বর</option>
            <option value="10">অক্টোবর</option>
            <option value="11">নভেম্বর</option>
            <option value="12">ডিসেম্বর</option>
        </select>
        <button type="submit" class="btn btn-primary">ডাউনলোড</button>
    </form>
    <form action="{{ route('report.transaction.yearly') }}" method="get" class="form-inline">
        <span>বাৎসরিক হিসাব</span>
        <label for="ty-year" class="sr-only">বছর</label>
        <select id="ty-year" name="year" class="form-control mx-3">
            <option value="">(বছর)</option>
            @foreach ($years as $year)
                <option value="{{ $year->yr }}">{{ $year->yr }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">ডাউনলোড</button>
    </form>
@endsection
