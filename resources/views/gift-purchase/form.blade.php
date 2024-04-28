@extends('layouts.app', ['title' => 'গিফট ক্রয়'])

@php
    if (!isset($giftPurchase)) {
        $giftPurchase = null;
    }
    $index = 0;
@endphp

@section('content')
    <h1>গিফট ক্রয়</h1>
    <form
        action="{{ $giftPurchase === null ? route('gift-purchase.store') : route('gift-purchase.update', ['gift_purchase' => $giftPurchase]) }}"
        method="POST" autocomplete="off">
        {{ csrf_field() }}
        @if ($giftPurchase !== null)
            {{ method_field('PUT') }}
        @endif
        {{-- {{ input($errors, 'hidden', 'gift_supplier_id', 'memo-to-id', $giftPurchase->accountBook->account->id ?? '') }} --}}
        <input type="hidden" name="gift_supplier_id" id="memo-to-id"
            value="{{ $giftPurchase->accountBook->account->id ?? '' }}">
        <button type="submit" name="submit" value="submit" class="btn-invisible"></button>
        <div class="row mb-3">
            <div class="col-md-9">
                {{ inputGroupBegin('memo-to', 'গিফট মহাজন') }}
                {{ input($errors, 'text', 'memo_to_name', 'memo-to', $giftPurchase->accountBook->account->name ?? '', '', ['data-datalist-id' => 'gift-supplier-list', 'data-datalist' => route('datalist.gift-supplier'), 'disabled' => !($giftPurchase->accountBook->open ?? true), 'autofocus' => $giftPurchase === null]) }}
                {{ inputGroupEnd() }}
                {{ error($errors, 'gift_supplier_id') }}
            </div>
            <div class="col-md-3 input-group justify-content-end align-items-start">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white">মেমো নং</span>
                </div>
                <div class="input-group-append">
                    <span class="input-group-text bg-white"><strong>{{ $giftPurchase->id ?? $memoNo }}</strong></span>
                </div>
            </div>
        </div>
        <fieldset id="form-table"{{ !$errors->has('gift_purchases.*.*') && $giftPurchase === null ? ' disabled' : '' }}>
            <table id="memo-table" class="table memo-input gift-purchase-table">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width:40%">গিফট</th>
                        <th style="width:20%">পরিমাণ</th>
                        <th style="width:20%">দর</th>
                        <th style="width:20%">টাকা</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($errors->has('gift_purchases.*.*'))
                        @foreach (old('gift_purchases') as $index => $oldvals)
                            @include('gift-purchase.tr', compact('index', 'oldvals'))
                        @endforeach
                    @elseif($giftPurchase !== null)
                        @foreach ($giftPurchase->giftTransactions as $index => $giftTransaction)
                            @include('gift-purchase.tr', compact('index', 'giftTransaction'))
                        @endforeach
                    @else
                        @include('gift-purchase.tr')
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td><button class="btn btn-success btn-add-row" data-tr="{{ route('tr.gift-purchase') }}"
                                data-index="{{ ++$index }}"><span class="fas fa-plus"></span></button></td>
                        <td colspan="3" class="text-right">
                            <div class="form-control-plaintext">মোট</div>
                        </td>
                        <td>
                            {{ disabledInput($errors, 'text', '', '', toFixed($giftPurchase->total_amount ?? 0), 'text-right input-total') }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3">
                            <div class="form-inline justify-content-end">
                                <label for="payment-method">তাগাদা</label>
                                {{ select($errors, 'payment_method', 'payment-method', '', $bankAccounts, 'mx-3', ['required' => true, 'style' => 'width:200px']) }}
                                <label for="cheque-no">চেক নং</label>
                                {{ disabledInput($errors, 'text', 'cheque_no', 'cheque-no', old('cheque_no', ''), 'number mx-3', ['style' => 'width:100px']) }}
                                <label for="cheque-date">পরিশোধের তারিখ</label>
                                {{ disabledInput($errors, 'date', 'cheque_date', 'cheque-date', old('cheque_date', ''), 'mx-3', ['style' => 'width:150px']) }}
                            </div>
                        </td>
                        <td>{{ input($errors, 'text', 'payment_amount', 'payment-amount', old('payment_amount', ''), 'text-right taka') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><button type="submit" class="btn btn-primary form-control">সাবমিট</button></td>
                    </tr>
                </tfoot>
            </table>
        </fieldset>
    </form>
@endsection

@section('page-script')
    <script src="{{ asset('js/commons/memo.js') }}"></script>
    <script src="{{ asset('js/gift-purchase/form.js') }}"></script>
@endsection
