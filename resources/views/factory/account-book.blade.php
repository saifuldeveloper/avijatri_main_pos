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
                    মোট মাল: <strong>{{ toFixed($purchases_amount) }}</strong><br>
                    তাগাদা:  <strong>{{ $payment_amount}} </strong>
                    <strong>
                        ({{ $purchases_amount != 0 ? number_format(($payment_amount / $purchases_amount) * 100, 2) : '0' }})%
                    </strong><br>
                    ফেরত:  <strong>{{ $returnsum}} </strong>
                </td>
                <td style="width:15%">
                    <a href="{{ route('account-book.closing-page', compact('accountBook')) }}"
                        class="btn btn-success btn-sm">ক্লোজিং</a><br>
                        <br>
                        ব্যালেন্স: <strong>{{ toFixed($purchases_amount - $payment_amount - $returnsum ) }}</strong><br>
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
            @foreach ($factoryEntries as $i => $entry)
           

                <tr>
                    <td>{{ $i + $inc }}</td>
                    <td>{{ dateTimeFormat($entry->created_at) }}</td>
                    @if ($entry->entry_type == 0)
                        <td><a
                                href="{{ route('purchase.show', ['purchase' => $entry->entry_id]) }}">{{ $entry->entry_id }}</a>
                        </td>
                        <td>ক্রয়</td>
                        <td>
                            @php
                                $printedColors = [];
                            @endphp
                            @foreach ($entry->purchase->purchaseEntries as $key => $item)
                                @if (!in_array($item->shoe->category->name, $printedColors))
                                    {{ $item->shoe->category->name }}
                                    @php $printedColors[] = $item->shoe->category->name; @endphp
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
                            @foreach ($entry->purchase->purchaseEntries as $key => $item)
                                @if (!in_array($item->shoe->color->name, $printedColors))
                                    {{ $item->shoe->color->name }}
                                    @php $printedColors[] = $item->shoe->color->name; @endphp
                                    @if (!$loop->last)
                                        -
                                    @endif
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $entry->purchase->purchaseEntries->sum('count') }}
                        </td>
                        <td>
                            @php
                                $totalRetailPrice = $entry->purchase->purchaseEntries->sum(function ($item) {
                                    return $item->shoe->retail_price;
                                });
                            @endphp
                            {{ $totalRetailPrice }}

                        </td>
                        <td>
                            @php
                                $purchase_price = $entry->purchase->purchaseEntries->sum(function ($item) {
                                    return $item->shoe->retail_price;
                                });
                            @endphp
                            {{ toFixed($purchase_price) }}</td>
                        <td>
                            @php
                                $sumprice = $entry->purchase->purchaseEntries->sum(function ($entry) {
                                    return ($entry->shoe->purchase_price * $entry->count) / 12;
                                });
                            @endphp
                            {{ toFixed($sumprice) }}
                        </td>
                        <td>-</td>
                        <td>{{ toFixed($sumprice) }}</td>
					
                    @elseif($entry->entry_type == 1)
                        <td>-</td>
                        <td>ফেরত</td>
                        <td>{{ $entry->category }}</td>
                        <td>{{ $entry->color }}</td>
                        <td>{{ $entry->returnshoe?->returnentries->sum('count') ?? '0' }}</td>
                        <td>
                            @php
                            $totalRetailPrice = $entry->returnshoe?->returnentries->sum(fn ($item) => $item->shoe->retail_price) ?? 0;
                        @endphp
                        {{ toFixed($totalRetailPrice) }}
                      </td>
                        
                        <td>
                            @php
                            $purchase_price = $entry->returnshoe?->returnentries->sum(fn ($item) => $item->shoe->retail_price) ?? 0;
                        @endphp
                        {{ toFixed($purchase_price) }}
                    </td>
                        </td>
                        <td>-</td>
                        <td>
                            @php
                            $returnsum = $entry->returnshoe?->returnentries->sum(fn ($item) => ($item->shoe->purchase_price * $item->count) / 12) ?? 0;
                            @endphp
                        {{ toFixed($returnsum) }}
                        </td>
                        <td>
                            {{ toFixed($returnsum) }}
                            
                        </td>
		
                    @elseif($entry->entry_type == 2)
                        <td>-</td>
                        <td>তাগাদা{{ $entry->closing_id === null ? '' : ' (ক্লোজিং)' }}</td>
                        <td class="text-left" colspan="5">
                            {{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}
                        </td>
                        <td>-</td>
                        <td>
                            {{ toFixed($entry->purchase->payment_amount) }}
                        </td>
                        <td>
                            @php
                            $sumprice = $entry->purchase->purchaseEntries->sum(function ($entry) {
                                return ($entry->shoe->purchase_price * $entry->count) / 12;
                            });
                          @endphp
                            {{ toFixed($sumprice -$entry->purchase->payment_amount) }}
                        </td>

                    @else
                        <td>-</td>
                        <td>তোলা</td>
                        <td class="text-left" colspan="5">
                            {{ $entry->account_name . (empty($entry->description) ? '' : ' (' . $entry->description . ')') }}
                        </td>
                        <td>{{ toFixed($entry->total_amount) }}</td>
                        <td>-</td>
                         <td></td>
                    </td>
					
                    @endif
                    
                </tr>
            @endforeach

            @if ($factoryEntries->currentPage() == $factoryEntries->lastPage() && $accountBook->opening_balance != 0)
		<tr>
			<td>{{ $i + 2 }}</td>
			<td>-</td>
			<td class="text-left">-</td>
			<td class="text-left" colspan="5">সাবেক</td>
			<td>-</td>
			<td>-</td>
			<td>{{ toFixed($accountBook->opening_balance) }}</td>
		</tr>
		@endif
        </tbody>
    </table>
    {{ $factoryEntries->links('pagination.default') }}
@endsection
