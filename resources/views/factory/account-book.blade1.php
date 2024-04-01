@extends('layouts.app', ['title' => 'মহাজন খাতা - ' . $accountBook->account->name])

@section('content')
    <h1>মহাজন খাতা</h1>
    <table class="table table-striped">
        <tbody>
            <tr>
                <td style="width:40%">
                    <p>নাম: <strong>{{ $accountBook->account->name }}</strong><br>
                        ঠিকানা: <strong>{{ $accountBook->account->address }}</strong><br>
                        মোবাইল নং: <strong>{{ $accountBook->account->mobile_no }}</strong></p>
                    তারিখ: <strong>{{ $accountBook->description }}</strong><br>
                </td>
                <td style="width:45%">
                    {{-- মোট মাল: <strong>{{ toFixed($accountBook->total_products_worth) }}</strong><br> --}}
                    মোট মাল: <strong>: {{ $purchases_amount }} </strong><br>
                    তাগাদা: <strong>{{ $payment_amount}} </strong>
                    <strong>
                        @if ($purchases_amount != 0)
                            {{ number_format(($payment_amount / $purchases_amount) * 100, 2) }}%
                        @else
                            0
                        @endif
                    </strong>
                </td>
                <td style="width:15%">
                    <a href="{{ route('account-book.closing-page', compact('accountBook')) }}"
                        class="btn btn-success btn-sm">ক্লোজিং</a>
                </td>
            </tr>
        </tbody>
    </table>
    @if (!$accountBook->open)
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>এই খাতার সকল পাওনা পরিশোধ করা হয়েছে।</td>
                </tr>
            </tbody>
        </table>
    @endif
    <table class="table table-striped table-bordered table-account-book text-center">
        <thead>
            <tr>
                <th>#</th>
                <th style="width:13%">তারিখ</th>
                <th style="width:6%">মেমো</th>
                <th style="width:7%">বিবরণ</th>
                <th style="width:10%">টাইপ</th>
                <th style="width:7%">রং</th>
                <th style="width:7%">জোড়া</th>
                <th style="width:10%">গায়ের দাম</th>
                <th style="width:10%">ডজন দাম</th>
                <th style="width:10%">মোট দাম</th>
                <th style="width:10%">তাগাদা</th>
                <th style="width:10%">ব্যালেন্স</th>
            </tr>
        </thead>
        <tbody>
            <?php $inc = 1; ?>
            @if (!$accountBook->open && $accountBook->entries->currentPage() == 1)
                @if ($accountBook->commission > 0)
                    <tr>
                        <td>{{ $inc++ }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>ক্লোজিং</td>
                        <td class="text-left" colspan="5">কমিশন</td>
                        <td>-</td>
                        <td>{{ toFixed($accountBook->commission) }}</td>
                        <td>{{ toFixed($accountBook->description_balance) }}</td>
                    </tr>
                @endif
                @if ($accountBook->staff > 0)
                    <tr>
                        <td>{{ $inc++ }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>ক্লোজিং</td>
                        <td class="text-left" colspan="5">স্টাফ খরচ</td>
                        <td>-</td>
                        <td>{{ toFixed($accountBook->staff) }}</td>
                        <td>{{ toFixed($accountBook->description_balance + $accountBook->commission) }}</td>
                    </tr>
                @endif
            @endif
            {{-- @if ($accountBook->entries !== null)
		@foreach ($accountBook->entries as $i => $entry)
		<tr>
			<td>{{ $i + $inc }}</td>
			<td>{{ dateTimeFormat($entry->created_at) }}</td>
			@if ($entry->entry_type == 0)
			<td><a href="{{ route('purchase.show', ['purchase' => $entry->purchase_id]) }}">{{ $entry->purchase_id }}</a></td>
			<td>ক্রয়</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ $entry->count }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@elseif($entry->entry_type == 1)
			<td>-</td>
			<td>ফেরত</td>
			<td>{{ $entry->category }}</td>
			<td>{{ $entry->color }}</td>
			<td>{{ $entry->count }}</td>
			<td>{{ toFixed($entry->retail_price) }}</td>
			<td>{{ toFixed($entry->purchase_price) }}</td>
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@elseif($entry->entry_type == 2)
			<td>-</td>
			<td>তাগাদা{{ $entry->closing_id === null ? '' : ' (ক্লোজিং)' }}</td>
			<td class="text-left" colspan="5">{{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>-</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			@else
			<td>-</td>
			<td>তোলা</td>
			<td class="text-left" colspan="5">{{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}</td>
			<td>{{ toFixed($entry->total_amount) }}</td>
			<td>-</td>
			@endif
			<td>{{ toFixed($entry->balance) }}</td>
		</tr>
		@endforeach
		@endif --}}

        
            @foreach ($purchases as $purchase)
                <tr>
                    @if ($purchase->payment_amount > 0)
                <tr>
                    <td></td>
                    <td>{{ $purchase->created_at }}</td>
                    <td>-</td>
                    <td> তাগাদা </td>
                    <td> ক্যাশ </td>
                    <td colspan="5"></td>
                    <td>{{ @$purchase->payment_amount }}</td>
                    <td>
                        @php
                            $sumprice = $purchase->purchaseEntries->sum(function ($entry) {
                                return ($entry->shoe->purchase_price * $entry->count) / 12;
                            });
                        @endphp
                        {{ $sumprice - @$purchase->payment_amount }}
                    </td>
                </tr>
            @endif
            <td></td>
            <td>{{ $purchase->created_at }}</td>

            <td>
                @if ($purchase && $purchase->id)
                    <a href="{{ route('purchase.show', ['purchase' => $purchase->id]) }}">{{ $purchase->id }}</a>
                @endif
            </td>
            </td>
            <td>ক্রয়</td>
            <td>
                @php
                $printedCategories = [];
            @endphp
            
            @foreach ($purchase->purchaseEntries as $key => $entry)
                @if (!in_array($entry->shoe->category->name, $printedCategories))
                    {{ $entry->shoe->category->full_name }}
                    @php $printedCategories[] = $entry->shoe->category->name; @endphp
                    @if (!$loop->last)
                        -
                    @endif
                @endif
            @endforeach
            </td>
            <td>
                @php
                    $printedColors = [];
                @endphp

                @foreach ($purchase->purchaseEntries as $key => $entry)
                    @if (!in_array($entry->shoe->color->name, $printedColors))
                        {{ $entry->shoe->color->name }}
                        @php $printedColors[] = $entry->shoe->color->name; @endphp
                        @if (!$loop->last)
                            -
                        @endif
                    @endif
                @endforeach
            </td>
            <td>
                {{ $purchase->purchaseEntries->sum('count') }}

            </td>
            <td>
                @php
                    $totalRetailPrice = $purchase->purchaseEntries->sum(function ($entry) {
                        return $entry->shoe->retail_price;
                    });
                @endphp
                {{ $totalRetailPrice }}
            </td>
            <td>
                @php
                    $totalRetailPrice = $purchase->purchaseEntries->sum(function ($entry) {
                        return $entry->shoe->purchase_price;
                    });
                @endphp
                {{ $totalRetailPrice }}

            </td>
            <td>
                @php
                    $sumprice = $purchase->purchaseEntries->sum(function ($entry) {
                        return ($entry->shoe->purchase_price * $entry->count) / 12;
                    });

                @endphp
                {{ $sumprice }}

            </td>
            <td>-</td>
            <td>{{ $sumprice }}</td>

            </tr>
            @endforeach
            {{-- @if ($accountBook->entries->currentPage() == $accountBook->entries->lastPage() && $accountBook->opening_balance != 0)
		<tr>
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left">-</td>
			<td class="text-left" colspan="5">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->opening_balance) }}</td>
		</tr>
		@endif --}}
        </tbody>
    </table>
    {{-- {{ $accountBook->entries->links('pagination.default') }} --}}
@endsection
