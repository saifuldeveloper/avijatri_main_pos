@extends('layouts.app', ['title' => 'গিফট মহাজন'])

@section('content')
    <h1>গিফট মহাজন <small><a href="{{ route('gift-supplier.create') }}" class="btn-new" data-toggle="modal"
                data-target="#gift-supplier-form">নতুন গিফট মহাজন</a></small></h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width:20%">নাম</th>
                <th style="width:30%">ঠিকানা</th>
                <th style="width:25%">মোবাইল নং</th>
                <th style="width:25%">অপশন</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($giftSuppliers as $giftSupplier)
                <tr>
                    {{-- <td><a href="{{ route('gift-supplier.show', ['gift-supplier' => $giftSupplier]) }}">{{ $giftSupplier->name }}</a></td> --}}
                    <td><a
                            href="{{ route('gift-supplier.show', ['gift_supplier' => $giftSupplier->id]) }}">{{ $giftSupplier->name }}</a>
                    </td>

                    <td>{{ $giftSupplier->address }}</td>
                    <td>{{ $giftSupplier->mobile_no }}</td>
                    <td>
                        @include('layouts.crud-buttons', [
                            'model' => 'gift-supplier',
                            'parameter' => 'gift_supplier',
                            'object' => $giftSupplier,
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($trashGiftSuppliers->count() > 0)
        <div class="mt-5">
            <h2> ডিলিট গিফট মহাজন </h2>
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th style="width:20%">নাম</th>
                        <th style="width:30%">ঠিকানা</th>
                        <th style="width:25%">মোবাইল নং</th>
                        <th style="width:25%">অপশন</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($trashGiftSuppliers as $giftSupplier)
                        <tr>
                            <td>{{ $giftSupplier->name }}</td>
                            <td>{{ $giftSupplier->address }}</td>
                            <td>{{ $giftSupplier->mobile_no }}</td>
                            <td>
                                @include('layouts.crud-buttons', [
                                    'model' => 'gift-supplier',
                                    'parameter' => 'gift_supplier',
                                    'object' => $giftSupplier,
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif

    <div id="gift-supplier-form" class="modal fade form-modal" tabindex="-1" role="dialog"
        aria-labelledby="form-modal-title" aria-hidden="true">
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
