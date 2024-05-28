@extends('layouts.app', ['title' => 'মহাজন খাতা - ' . $factory->name])

@section('content')
    <h1>মহাজন খাতা</h1>
    <table class="table table-striped">
        <tbody>
            <tr>
                <td style="width:80%">
                    নাম: <strong>{{ $factory->name }}</strong><br>
                    ঠিকানা: <strong>{{ $factory->address }}</strong><br>
                    মোবাইল নং: <strong>{{ $factory->mobile_no }}</strong>
                </td>
                <td style="width:20%">
                    @include('layouts.crud-buttons', [
                        'model' => 'factory',
                        'parameter' => 'factory',
                        'object' => $factory,
                    ])
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width:70%">তারিখ</th>
                        <th style="width:30%" class="text-right">ব্যালেন্স</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factory->accountBooks->reverse() as $accountBook)
                        <tr>
                            <td><a
                                    href="{{ route('account-book.show', ['account_book' => $accountBook->id]) }}">{{ $accountBook->description }}</a>
                            </td>
                            <td class="text-right">
                                @php
                                $entries_data = App\Models\View\FactoryAccountEntry::where('account_book_id', $accountBook->id)->where('status',1)->get();
                                $payment_amount = $entries_data->where('entry_type', '2')->sum('total_amount');
                                $purchase_amount = $entries_data->where('entry_type', '0')->sum('total_amount');
                                $return_amount = $entries_data->where('entry_type', '1')->sum('total_amount');
                                $total_balance = $purchase_amount - ($payment_amount + $return_amount);
                                @endphp
                                {{ toFixed($total_balance) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="factory-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title"
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
