@extends('layouts.app', ['title' => 'জুতা ক্রয়ের রশিদ'])

@php
    if (!isset($preview)) {
        $preview = false;
    }
@endphp

@section('content')
    <h1>জুতা ক্রয়ের রশিদ</h1>
    <table class="table table-striped border">
        <tbody>
            <tr>
                <td style="width:70%">মেমো নং: <strong>{{ $purchase->id ?? $purchase_id }}</strong></td>
                <td style="width:30%">তারিখ: <strong>{{ dateFormat($purchase->created_at ?? date('Y-m-d H:i:s')) }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    নাম: <strong>{{ $purchase->accountBook->account->name ?? $factory->name }}</strong><br>
                    ঠিকানা: <strong>{{ $purchase->accountBook->account->address ?? $factory->address }}</strong><br>
                    মোবাইল নং: <strong>{{ $purchase->accountBook->account->mobile_no ?? $factory->mobile_no }}</strong>
                </td>
                <td>
                    <div class="d-print-none">
                        @if (!$preview)
                            <a href="{{ route('purchase.barcode', compact('purchase')) }}"
                                class="btn btn-success btn-sm">বারকোড প্রিন্ট</a>
                            @include('layouts.crud-buttons', [
                                'model' => 'purchase',
                                'parameter' => 'purchase',
                                'object' => $purchase,
                                'http' => false,
                            ])
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th class="text-center" style="width:10%">আইডি</th>
                <th class="text-center" style="width:15%">টাইপ</th>
                <th class="text-center" style="width:15%">রং</th>
                <th class="text-center" style="width:15%">গায়ের দাম</th>
                <th class="text-center" style="width:15%">ডজন দাম</th>
                <th class="text-center" style="width:15%">জোড়া</th>
                <th class="text-right" style="width:10%">মোট</th>
            </tr>
        </thead>
        <tbody>
            @if ($preview)
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>
                          
                                {{-- @include('templates.thumbnail-preview', ['small_thumbnail' =>'images/small-thumbnail/'. $purchase['image'] ?? $purchase['shoe']->image_url, 'preview' => $purchase['preview_url'] ?? $purchase['shoe']->preview_url]) --}}
                                @if (isset($purchase['image']))
                                    @include('templates.thumbnail-preview', [
                                        'small_thumbnail' => 'images/small-thumbnail/' . $purchase['image'],
                                    ])
                                @else
                                    @include('templates.thumbnail-preview', [
                                        'small_thumbnail' => $purchase['shoe']->image_url,
                                        'preview' => $purchase['preview_url'] ?? $purchase['shoe']->preview_url,
                                    ])
                                @endif
                        </td>
                        <td class="text-center">{{ $purchase['shoe_id'] }}</td>
                        <td class="text-center">{{ $purchase['category'] ?? $purchase['shoe']->category->full_name }}</td>
                        <td class="text-center">{{ $purchase['color'] ?? $purchase['shoe']->color->name }}</td>
                        <td class="text-center">{{ toFixed($purchase['retail_price'] ?? $purchase['shoe']->retail_price) }}
                        </td>
                        <td class="text-center">
                            {{ isset($purchase['purchase_price']) ? toFixed($purchase['purchase_price']) : (isset($purchase['shoe']->purchase_price) && $purchase['shoe']->purchase_price > 0 ? toFixed($purchase['shoe']->purchase_price) : 'পেন্ডিং') }}
                        </td>
                        <td class="text-center">{{ $purchase['count'] }}</td>
                        <td class="text-right">{{ toFixed($purchase['total_price']) }}</td>
                    </tr>
                @endforeach
            @else
                @foreach ($purchase->purchaseEntries as $i => $purchaseEntry)
                    <tr>
                        <td>
                            @include('templates.thumbnail-preview', [
                                'small_thumbnail' => $purchaseEntry->shoe->image_url,
                                'preview' => $purchaseEntry->shoe->preview_url,
                            ])
                        </td>
                        <td class="text-center">{{ $purchaseEntry->shoe_id }}</td>
                        <td class="text-center">{{ $purchaseEntry->shoe->category->full_name }}</td>
                        <td class="text-center">{{ $purchaseEntry->shoe->color->name }}</td>
                        <td class="text-center">{{ toFixed($purchaseEntry->shoe->retail_price) }}</td>
                        <td class="text-center">
                            {{ $purchaseEntry->shoe->purchase_price > 0 ? toFixed($purchaseEntry->shoe->purchase_price) : 'পেন্ডিং' }}
                        </td>
                        <td class="text-center">{{ $purchaseEntry->count }}</td>
                        <td class="text-right">{{ toFixed($purchaseEntry->total_price) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">মোট =
                    <strong>{{ toFixed($purchase->total_amount ?? $total_payable) }}</strong></td>
            </tr>
            @if (isset($purchase->transaction) && $purchase->transaction !== null)
                <tr>
                    <td colspan="8" class="text-right">তাগাদা = {{ toFixed($purchase->transaction->amount) }} </td>
                </tr>
            @elseif(isset($purchase->cheque) && $purchase->cheque !== null)
                <tr>
                    <td colspan="8" class="text-right">তাগাদা = {{ toFixed($purchase->cheque->amount) }}</td>
                </tr>
            @endif
            @if (isset($payment_amount))
                <tr>
                    <td colspan="8" class="text-right">তাগাদা = {{ toFixed($payment_amount) }}</td>
                </tr>
            @endif
        </tfoot>
    </table>
@endsection
