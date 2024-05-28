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
            padding-right: 15px;
            padding-left: 15px;
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
        }

        .table td,
        .table th {
            border: 1px solid #ddd;
            padding: 7px;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="custom_header">
                    <h1>আজকের লেনদেনের হিসাব</h1>
                    <h3>তারিখ: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</h3>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-lg-6">
                <h3>বিক্রি</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>বিবরণ</th>
                            <th class="text-right">টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>সাবেক</td>
                            <td class="text-right">{{ toFixed($data->initialCashBalance) }}</td>
                        </tr>
                        @php
                            $total = 0;
                        @endphp

                        {{-- @dd($data->incomes) --}}

                        @foreach ($data->incomes as $i => $income)
                            @if ($income->amount == 0)
                                @continue
                            @endif
                            <tr>

                                <td>
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

                                    @if ($income->fromAccount->account_type == 'bank-account')
                                        ব্যাংক তোলা - {{ @$account->bank }} ({{ @$account->account_no }})
                                    @elseif($income->fromAccount->account_type == 'retail-store')
                                        পার্টি জমা -{{ $account->shop_name }}
                                    @elseif ($income->fromAccount->account_type == 'gift-supplier')
                                        গিফট মহাজন তাগাদা - {{ $account->name }}
                                    @elseif ($income->fromAccount->account_type == 'Cheque')
                                        check
                                    @elseif ($income->fromAccount->account_type == 'loan')
                                        হাওলাত আনা {{ $account->name }}
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
                            <td class="text-right"><strong>{{ toFixed($data->incomesSum) }}</strong></td>
                        </tr>
                        @if ($data->expenses->sum('amount') > 0)
                            <tr>
                                <td>খরচ বাদ</td>
                                <td class="text-right">{{ toFixed($data->expensesSum) }}</td>
                            </tr>
                            <tr>
                                <td><strong>ক্যাশ</strong></td>
                                <td class="text-right"><strong>{{ toFixed($data->finalCashBalance) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-lg-6">
                <h3>তাগাদা</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>বিবরণ</th>
                            <th class="text-right">টাকা</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->expenses as $i => $expense)
                            @if ($expense->amount == 0)
                                @continue
                            @endif
                            <tr>
                                <td>
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

                                    @if ($expense->toAccount->account_type == 'bank-account')
                                        ব্যাংক জমা - {{ $account->bank }} ({{ $account->account_no }})
                                    @elseif($expense->toAccount->account_type == 'factory')
                                        মহাজন তাগাদা -{{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'gift-supplier')
                                        গিফট মহাজন তাগাদা - {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'cheque')
                                        {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'employee')
                                        স্টাফ -{{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'loan')
                                        হাওলাত ফেরত {{ $account->name }}
                                    @elseif ($expense->toAccount->account_type == 'expense')
                                        অন্যান্য খরচ-{{ $account->name }}
                                    @endif
                                </td>
                                <td class="text-right">{{ toFixed($expense->amount) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td><strong>মোট</strong></td>
                            <td class="text-right"><strong>{{ toFixed($data->expensesSum) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
