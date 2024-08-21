@extends('layouts.app', ['title' => 'জুতা বিক্রয়ের রশিদ'])

@php
    if (!isset($preview)) {
        $preview = false;
    }
@endphp


@section('content')

<style>
    
    @media print {
    thead, th, tr, td {
        padding: 15px;
        text-align: left;
        border: 2px solid #000;
      }
    }
    </style>
    {{-- <h1 class="mt-5 print-top-space">জুতা বিক্রয়ের রশিদ</h1> --}}
    <table class="table table-striped border print-top-space" >
        <tbody>
            <tr>
                <td style="width:70%">মেমো নং: <strong>{{ $invoice->id ?? $invoice_id }}</strong></td>
                <td style="width:30%">তারিখ: <strong>{{ dateFormat($invoice->created_at ?? date('Y-m-d H:i:s')) }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    নাম: <strong>{{ $invoice->accountBook->retailAccount->shop_name ?? $retailStore->name }}</strong><br>
                    ঠিকানা:
                    <strong>{{ $invoice->accountBook->retailAccount->address ?? $retailStore->address }}</strong><br>
                    মোবাইল নং:
                    <strong>{{ $invoice->accountBook->retailAccount->mobile_no ?? $retailStore->mobile_no }}</strong>
                </td>
                <td>
                    <div class="d-print-none">
                        @if (!$preview)
                            <a href="{{ route('invoice.show', ['invoice' => $invoice, 'view' => 'id']) }}"
                                class="btn btn-success btn-sm">আইডিসহ দেখুন</a>
                            @include('layouts.crud-buttons', [
                                'model' => 'invoice',
                                'parameter' => 'invoice',
                                'object' => $invoice,
                                'http' => false,
                            ])
                            <a class="btn btn-primary btn-sm text-white" onclick="window.print()">প্রিন্ট </a>
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th style="width:55%">বিবরণ</th>
                <th class="text-center" style="width:15%">জোড়া</th>
                <th class="text-center" style="width:15%">দর</th>
                <th class="text-right" style="width:15%">মোট</th>
            </tr>
        </thead>
        <tbody>
            @if ($preview)
                <?php $i = 0;
                $total_pairs = 0; ?>
                @foreach ($sales as $parent => $pg)
                    @if (empty($parent))
                        @continue
                    @endif
                    @foreach ($pg as $retail_price => $rpg)
                        <tr class="border_top">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $parent }}-<?php
                            $j = 0;
                            $total_count = 0;
                            $total_price = 0;
                            foreach ($rpg as $category => $count) {
                                if ($j > 0) {
                                    echo e('+');
                                }
                                echo e($category);
                                $total_count += $count;
                                $total_price += doubleval($retail_price) * $count;
                                $j++;
                            }
                            $total_pairs += $total_count;
                            ?></td>
                            <td class="text-center">{{ $total_count }}</td>
                            <td class="text-center">{{ toFixed($retail_price) }}</td>
                            <td class="text-right">{{ toFixed($total_price) }}</td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @endforeach
            @else
                @foreach ($invoice->invoiceItems as $i => $invoiceItem)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $invoiceItem->description }}</td>
                        <td class="text-center">{{ $invoiceItem->count }}</td>
                        <td class="text-center">{{ toFixed($invoiceItem->retail_price) }}</td>
                        <td class="text-right">{{ toFixed($invoiceItem->total_price) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="text-center"><strong>= {{ $invoice->total_pairs ?? $total_pairs }}</strong></td>
                <td class="text-center"><strong>মোট</strong></td>
                <td class="text-right"><strong>{{ toFixed($invoice->total_amount ?? $total_amount) }}</strong></td>
            </tr>
            <tr class="hide-on-print">
                <td colspan="3"></td>
                <td class="text-center">কমিশন ({{ $invoice->commission ?? $commission }}%)</td>
                <td class="text-right">(-) {{ toFixed($invoice->total_commission ?? $total_commission) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center"><strong>কমিশন বাদে মোট</strong></td>
                <td class="text-right">
                    <strong>{{ toFixed($invoice->commission_deducted ?? $commission_deducted) }}</strong>
                </td>
            </tr>
            @if ($invoice->return_amount ?? $return_amount > 0)
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">{{ $invoice->return_count ?? $return_count }} জোড়া ফেরত বাবদ</td>
                    <td class="text-right">(-) {{ toFixed($invoice->return_amount ?? $return_amount) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>ফেরত বাদে মোট</strong></td>
                    <td class="text-right"><strong>{{ toFixed($invoice->return_deducted ?? $return_deducted) }}</strong>
                    </td>
                </tr>
            @endif
            @if ($invoice->transport ?? $transport > 0)
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">পাঠানোর খরচ</td>
                    <td class="text-right">(+) {{ toFixed($invoice->transport ?? $transport) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>পাঠানোর খরচ সহ</strong></td>
                    <td class="text-right"><strong>{{ toFixed($invoice->transport_added ?? $transport_added) }}</strong>
                    </td>
                </tr>
            @endif
            @if ($invoice->other_costs ?? $other_costs > 0)
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">অন্যান্য খরচ</td>
                    <td class="text-right">(-) {{ toFixed($invoice->other_costs ?? $other_costs) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>অন্যান্য খরচ বাদে</strong></td>
                    <td class="text-right">
                        <strong>{{ toFixed($invoice->other_costs_deducted ?? $other_costs_deducted) }}</strong>
                    </td>
                </tr>
            @endif
            @if ($invoice->discount ?? $discount > 0)
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">ডিসকাউন্ট</td>
                    <td class="text-right">(-) {{ toFixed($invoice->discount ?? $discount) }}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>সর্বমোট</strong></td>
                    <td class="text-right"><strong>{{ toFixed($invoice->total_receivable ?? $total_receivable) }}</strong>
                    </td>
                </tr>
            @endif
            @if ((isset($invoice) && $invoice->total_payment > 0) || (!isset($invoice) && $payment_amount > 0))
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center">জমা</td>
                    <td class="text-right">(-) {{ toFixed(isset($invoice) ? $invoice->total_payment : $payment_amount) }}
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="3"></td>
                <td class="text-center">সাবেক বাকী</td>
                <td class="text-right">(+) {{ toFixed($invoice->account_book_previous_balance ?? $previous_due) }}</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center"><strong>মোট বাকী</strong></td>
                <td class="text-right"><strong>{{ toFixed($invoice->account_book_balance ?? $total_due) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    {{-- @if (($preview && count($gifts) > 0) || (!$preview && $invoice->giftTransactions->count() > 0))

<?php
if ($preview) {
    $giftTransactions = $gifts;
} else {
    $giftTransactions = $invoice->giftTransactions;
}
?>
@endif --}}
    <div class="row mb-3">
        <div class="col-md-4 col-sm-4">
            <h3>গিফটের বিবরণ</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width:80%">গিফট</th>
                        <th style="width:20%" class="text-right">সংখ্যা</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($gifts ?? $giftTransactions as $giftTransaction)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $giftTransaction->gift->name ?? $giftTransaction['gift']->name }}</td>
                            <td class="text-right">{{ $giftTransaction->count ?? $giftTransaction['count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- @endif --}}
        {{-- @if ((isset($transactions) && $transactions !== null) || (isset($validPayments) && $transactions !== null)) --}}
        <div class="col-md-8 col-sm-8">
            <h3>জমার বিবরণ</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width:60%">ধরণ</th>
                        <th style="width:20%">চেক নং</th>
                        <th style="width:20%" class="text-right">টাকা</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($validPayments ?? $transactions as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $item['payment_method'] ?? ($item->toAccount->BankAccount->bank ?? 'চেক') }}
                            </td>

                            <td>{{ @$item['cheque_no'] ?? @$item->description }}</td>
                            <td style="width:20%" class="text-right">{{ $item['amount'] ?? $item->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- @endif --}}
    </div>

@endsection
