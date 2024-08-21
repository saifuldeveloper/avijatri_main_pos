<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> আজকের লেনদেনের হিসাব</title>

    <style>
        body {
            font-family: solaimanlipi;
        }

        .container {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
          
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-lg-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }



        .col-lg-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }


        .table {
            font-family: solaimanlipi, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        .table td,
        .table th {
            border: 1px solid #ddd;
            padding: 7px;
            font-size: 14px;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #ddd;
        }

        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #495057 !important;
            color: white;
        }

        .custom_header {
            text-align: center;
            /* margin-bottom: 20px; */
        }
        .secon-page {
                font-size: 13px;

            }
    
        @media print {
            .col-6 {
                width: 50% !important;
                float: left;

            }
        .secon-page {
            /* margin-top: 700px; */
            font-size: 13px !important;
        }
    
    }

    .printable-section {
    }
        
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom_header">
                    <h1>প্রতিদিনের হিসাব </h1>
                    <h3>তারিখ:{{ $date }}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>মাল</h3>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width:22%" class="text-left">মহাজন</th>
                            <th style="width:13%">টাইপ</th>
                            <th style="width:13%">গায়ের দাম</th>
                            <th style="width:13%">ডজন দাম</th>
                            <th style="width:13%">জোড়া</th>
                            <th style="width:13%">মোট দাম</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $i => $purchase)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-left">{{ $purchase->factory }}</td>
                                <td>{{ $purchase->category }}</td>
                                <td>{{ toFixed($purchase->retail_price) }}</td>
                                <td>{{ toFixed($purchase->purchase_price) }}</td>
                                <td>{{ $purchase->count }}</td>
                                <td>{{ toFixed($purchase->total_price_a) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-bottom">
                            <td colspan="4"></td>
                            <td><strong>মোট</strong></td>
                            <td><strong>{{ $purchaseSummary->total_count ?? 0 }}</strong></td>
                            <td><strong>{{ toFixed($purchaseSummary->total_price ?? 0) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

          
                
                <h3>গিফট মাল</h3>
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-center" style="width:40%">মহাজন</th>
                            <th style="width:10%">নাম </th>
                            <th style="width:10%">টাইপ</th>
                            <th style="width:15%">পরিমান </th>
                            <th style="width:15%">মোট দাম</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totol_price = 0;
                        @endphp
                        @foreach ($giftTransaction as $i => $purchase)
                            <tr>
                                <td class="text-left"> {{ $i + 1 }}</td>
                                <td>{{ $purchase->giftPurchase->accountBook->giftSupplierAccount->name }}</td>
                                <td>{{ $purchase->gift->name }}</td>
                                <td>{{ $purchase->gift->giftType->name }}</td>
                                <td>{{ $purchase->count }}</td>

                                <td>{{ toFixed($purchase->count * $purchase->unit_price) }}</td>

                                @php
                                    $totol_price += $purchase->count * $purchase->unit_price;

                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-bottom">
                            <td colspan="3"></td>
                            <td><strong>মোট</strong></td>
                            <td><strong>{{ $giftTransaction->sum('count') ?? 0 }}</strong></td>
                            <td><strong> {{ $totol_price }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
               

            </div>
        </div>
    </div>


    <div class="container ">
        <div class="row secon-page printable-section">
            <div class="col-6">
                <h3>বিক্রি</h3>
                <table class="table table-striped"  style="margin-right: 10px;">
                    <thead>
                        <tr>
                            <th >নাম</th>
                            <th>বিবরণ</th>
                            <th class="text-right">টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>সাবেক</td>
                            <td></td>
                            <td class="text-right">{{ toFixed($initialCashBalance) }}</td>
                        </tr>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($incomes as $i => $income)
                            @if ($income->amount == 0)
                                @continue
                            @endif
                            <tr>
                                @php
                                    if ($income->fromAccount->account_type == 'bank-account') {
                                        $account = App\Models\BankAccount::find($income->fromAccount->account_id);
                                    } elseif ($income->fromAccount->account_type == 'retail-store') {
                                        $account = App\Models\RetailStore::find($income->fromAccount->account_id);
                                    } elseif ($income->fromAccount->account_type == 'Cheque') {
                                        $account = App\Models\Cheque::find($income->fromAccount->account_id);
                                    } elseif ($income->fromAccount->account_type == 'loan') {
                                        $account = App\Models\Loan::find($income->fromAccount->account_id);
                                    }
                                @endphp
                                <td>
                                    @if ($income->fromAccount->account_type == 'bank-account')
                                        {{ @$account->bank }} ({{ @$account->account_no }})
                                    @elseif($income->fromAccount->account_type == 'retail-store')
                                        {{ $account->shop_name }}
                                    @elseif ($income->fromAccount->account_type == 'gift-supplier')
                                        {{ $account->name }}
                                    @elseif ($income->fromAccount->account_type == 'Cheque')

                                    @elseif ($income->fromAccount->account_type == 'loan')
                                        {{ $account->name }}
                                    @endif
                                </td>
                                <td>

                                    @if ($income->fromAccount->account_type == 'bank-account')
                                        ব্যাংক তোলা
                                    @elseif($income->fromAccount->account_type == 'retail-store')
                                        পার্টি জমা
                                    @elseif ($income->fromAccount->account_type == 'gift-supplier')
                                        গিফট মহাজন তাগাদা
                                    @elseif ($income->fromAccount->account_type == 'Cheque')
                                        check
                                    @elseif ($income->fromAccount->account_type == 'loan')
                                        হাওলাত আনা
                                    @endif
                                </td>
                                <td class="text-right">{{ toFixed($income->amount) }}</td>
                                @php
                                    $total += toFixed($income->amount);
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>মোট</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{ toFixed($incomesSum) }}</strong></td>
                        </tr>
                        @if ($expenses->sum('amount') > 0)
                            <tr>


                                <td>খরচ বাদ</td>
                                <td></td>
                                <td class="text-right">{{ toFixed($expensesSum) }}</td>
                            </tr>
                            <tr>


                                <td><strong>ক্যাশ</strong></td>
                                <td></td>
                                <td class="text-right"><strong>{{ toFixed($finalCashBalance) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-6" style="margin-left: 10px;">
                <h3>তাগাদা</h3>
                <table class="table table-striped" style="margin-left: 10px;">
                    <thead>
                        <tr>
                            <th>নাম</th>
                            <th>বিবরণ</th>
                            <th class="text-right">টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $i => $expense)
                            @if ($expense->amount == 0)
                                @continue
                            @endif

                            <tr>
                                @php
                                    if ($expense->toAccount->account_type == 'bank-account') {
                                        $account = App\Models\BankAccount::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'factory') {
                                        $account = App\Models\Factory::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'gift-supplier') {
                                        $account = App\Models\GiftSupplier::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'cheque') {
                                        $account = App\Models\Cheque::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'employee') {
                                        $account = App\Models\Employee::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'loan') {
                                        $account = App\Models\Loan::find($expense->toAccount->account_id);
                                    } elseif ($expense->toAccount->account_type == 'expense') {
                                        $account = App\Models\Expense::find($expense->toAccount->account_id);
                                    }
                                @endphp
                                <td>


                                    @if ($expense->toAccount->account_type == 'bank-account')
                                        {{ $account->bank }} ({{ $account->account_no }})
                                    @elseif($expense->toAccount->account_type == 'factory')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'gift-supplier')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'cheque')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'employee')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'loan')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'expense')
                                        {{ $account->name }}
                                    @endif
                                </td>

                                <td>
                                    @if ($expense->toAccount->account_type == 'bank-account')
                                        ব্যাংক জমা
                                    @elseif($expense->toAccount->account_type == 'factory')
                                        মহাজন তাগাদা
                                    @elseif ($expense->toAccount->account_type == 'gift-supplier')
                                        গিফট মহাজন তাগাদা
                                    @elseif ($expense->toAccount->account_type == 'cheque')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'employee')
                                        স্টাফ
                                    @elseif ($expense->toAccount->account_type == 'loan')
                                        হাওলাত ফেরত
                                    @elseif ($expense->toAccount->account_type == 'expense')
                                        অন্যান্য খরচ
                                    @endif
                                </td>
                                <td class="text-right">{{ toFixed($expense->amount) }}</td>
                            </tr>
                        @endforeach
                        <tr>

                            <td><strong>মোট</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{ toFixed($expensesSum) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


  
</body>

</html>
