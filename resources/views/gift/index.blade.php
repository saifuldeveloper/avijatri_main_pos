@extends('layouts.app', ['title' => 'গিফট ইনভেন্টরি'])
@section('content')
    <h1>গিফট ইনভেন্টরি <small><a href="{{ route('gift.create') }}" class="btn-new" data-toggle="modal"
                data-target="#gift-form">নতুন গিফট</a> | <a href="{{ route('gift-purchase.create') }}">গিফট ক্রয়</a></small>
    </h1>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:30%">গিফট</th>
                    <th style="width:30%">টাইপ</th>
                    <th style="width:20%">সংখ্যা</th>
                    <th style="width:20%">অপশন</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gifts as $gift)
                    <tr>
                        <td>{{ $gift->name }}</td>
                        <td>{{ $gift->giftType->name }}</td>
                        <td>
                            {{ $gift->giftTransactions->where('type', 'purchase')->sum('count') - $gift->giftTransactions->where('type', 'sale')->sum('count') - $gift->giftTransactions->where('type', 'waste')->sum('count') }}
                        </td>
                        <td>@include('layouts.crud-buttons', [
                            'model' => 'gift',
                            'parameter' => 'gift',
                            'object' => $gift,
                        ])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @if ($trashGifts->count() > 0)
        <div class="mt-5">
            <h2> ডিলিট গিফট ইনভেন্টরি </h2>

            <table class="table table-striped">

                <thead>
                    <tr>
                        <th style="width:30%">গিফট</th>
                        <th style="width:30%">টাইপ</th>
                        <th style="width:20%">সংখ্যা</th>
                        <th style="width:20%">অপশন</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($trashGifts as $gift)
                        <tr>
                            <td>{{ $gift->name }}</td>
                            <td>{{ $gift->giftType->name }}</td>
                            <td>
                                {{ $gift->giftTransactions->where('type', 'purchase')->sum('count') - $gift->giftTransactions->where('type', 'sale')->sum('count') - $gift->giftTransactions->where('type', 'waste')->sum('count') }}
                            </td>
                            <td>@include('layouts.crud-buttons', [
                                'model' => 'gift',
                                'parameter' => 'gift',
                                'object' => $gift,
                            ])</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif


    <div id="gift-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="form-modal-title" class="modal-title">অপেক্ষা করুন ...</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection
