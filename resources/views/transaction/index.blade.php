@extends('layouts.app', ['title' => 'বিক্রি খাতা'])

@section('content')
    <h1>
        বিক্রি খাতা
        <small>
            {{ $date->format('d/m/Y') }}
            @if ($date != \Carbon\Carbon::today())
                | <a href="{{ route('transaction.index') }}">আজকের খাতা</a>
            @endif
        </small>
    </h1>
    @if ($date == \Carbon\Carbon::today())
        <form action="{{ route('transaction.store') }}" method="post" class="row" autocomplete="off">
            {{ csrf_field() }}
            <div class="col-lg-2 form-group">
                <label for="transaction-account-type">লেনদেনের ধরন</label>
                {{ select($errors, 'account_type', 'transaction-account-type', '', $accountTypes, '', ['data-datalist-url' => route('datalist.factory'), 'required' => true, 'not_selected_label' => true]) }}
            </div>
            <div class="col-lg-4 form-group">
                <label for="transaction-account-name">খাতা</label>
                {{ disabledInput($errors, 'text', '', 'transaction-account-name', '', 'locked', ['required' => true]) }}
                {{ disabledInput($errors, 'hidden', 'account_id', 'transaction-account-id', '', 'locked', ['required' => true]) }}
            </div>
            <div class="col-lg-2 form-group">
                <label for="transaction-payment-type">জমা/খরচ</label>
                {{ disabledSelect($errors, 'payment_type', 'transaction-payment-type', '', $paymentTypes) }}
                <input type="hidden" name="payment_type" id="transaction-hidden-income" value="income" class="locked"
                    disabled>
                <input type="hidden" name="payment_type" id="transaction-hidden-expense" value="expense" class="locked"
                    disabled>
            </div>
            <div class="col-lg-2 form-group">
                <label for="transaction-payment-method">ব্যাংক/ক্যাশ</label>
                {{ disabledSelect($errors, 'payment_method', 'transaction-payment-method', '', $bankAccounts, 'locked', ['required' => true]) }}
            </div>
            <div class="col-lg-2 form-group">
                <label for="transaction-amount">টাকা</label>
                {{ disabledInput($errors, 'text', 'amount', 'transaction-amount', '', 'locked taka', ['required' => true]) }}
            </div>
            <div class="col-lg-6 form-group">
                <label for="transaction-description">মন্তব্য</label>
                {{ disabledInput($errors, 'text', 'description', 'transaction-description', '', 'locked') }}
            </div>
            <div class="col-lg-2 form-group">
                <label for="transaction-cheque-no">চেক নং</label>
                {{ disabledInput($errors, 'text', 'cheque_no', 'transaction-cheque-no', '', 'locked', ['required' => true, 'data-cheque-url' => route('ajax.cheque.show', ['cheque' => '#'])]) }}
            </div>
            <div class="col-lg-2 form-group">
                <label for="transaction-cheque-no">চেক পরিশোধের তারিখ</label>
                {{ disabledInput($errors, 'date', 'due_date', 'transaction-cheque-due-date', \Carbon\Carbon::today()->addMonth()->format('Y-m-d'), 'locked', ['required' => true]) }}
            </div>
            <div class="col-lg-2 form-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control locked" disabled>সাবমিট</button>
            </div>
            <div class="col-12 form-group d-none">
                <div id="infobar" class="form-control">খাতার তথ্য</div>
            </div>
        </form>
    @endif
    <div class="row">
        <div class="col-12">
            <h3>মাল</h3>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width:22%" class="text-left">মহাজন</th>
                        <th style="width:13%">টাইপ</th>
                        <th style="width:13%">রং</th>
                        <th style="width:13%">গায়ের দাম</th>
                        <th style="width:13%">ডজন দাম</th>
                        <th style="width:13%">জোড়া</th>
                        <th style="width:13%">মোট দাম</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->purchases as $i => $purchase)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-left">{{ $purchase->factory }}</td>
                            <td>{{ $purchase->category }}</td>
                            <td>{{ $purchase->color }}</td>
                            <td>{{ toFixed($purchase->retail_price) }}</td>
                            <td>{{ toFixed($purchase->purchase_price) }}</td>
                            <td>{{ $purchase->count }}</td>
                            <td>{{ toFixed($purchase->total_price_a) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-bottom">
                        <td colspan="5"></td>
                        <td><strong>মোট</strong></td>
                        <td><strong>{{ $data->purchaseSummary->total_count ?? 0 }}</strong></td>
                        <td><strong>{{ toFixed($data->purchaseSummary->total_price ?? 0) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
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
                                    হাওলাত {{ $account->name }}
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
                                    হাওলাত {{ $account->name }}
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

    <form action="{{ route('transaction.index') }}" method="GET" class="form-inline justify-content-center">
        <a href="{{ route('transaction.index', ['date' => $date->subDay()->format('Y-m-d')]) }}"
            class="btn btn-outline-secondary">&larr; আগের খাতা</a>
        <div class="input-group mx-2">
            <div class="input-group-prepend">
                <div class="input-group-text border-secondary">
                    <label for="old-register-date">পুরাতন খাতা</label>
                </div>
            </div>
            <input type="date" name="date" id="old-register-date" class="form-control border-secondary border-left-0"
                value="{{ old('date', $date->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="btn btn-primary mr-2">দেখুন</button>
        <a href="{{ route('transaction.index', ['date' => $date->addDay()->format('Y-m-d')]) }}"
            class="btn btn-outline-secondary{{ $date == \Carbon\Carbon::today() ? ' disabled' : '' }}">পরের খাতা &rarr;</a>
    </form>
@endsection

@section('page-script')
    <script type="text/javascript" src="{{ asset('js/transaction/index.js') }}"></script>
@endsection
