@extends('layouts.app', ['title' => 'অন্যান্য খরচ খাতা'])

@section('content')
    <h1>অন্যান্য খরচ খাতা <small><a href="{{ route('expense.create') }}" class="btn-new" data-toggle="modal"
                data-target="#expense-form">নতুন খরচের খাত</a></small></h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width:60%">খরচের খাত</th>
                <th style="width:40%">অপশন</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td><a href="{{ route('expense.show', ['expense' => $expense]) }}">{{ $expense->name }}</a></td>
                    <td>
                        @include('layouts.crud-buttons', [
                            'model' => 'expense',
                            'parameter' => 'expense',
                            'object' => $expense,
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($trashExpenses->count() > 0)
        <div class="mt-5">
            <h2> ডিলিট অন্যান্য খরচ খাতা </h2>
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th style="width:60%">খরচের খাত</th>
                        <th style="width:40%">অপশন</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($trashExpenses as $expense)
                        <tr>
                            <td>{{ $expense->name }}</td>
                            <td>
                                @include('layouts.crud-buttons', [
                                    'model' => 'expense',
                                    'parameter' => 'expense',
                                    'object' => $expense,
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif

    <div id="expense-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title"
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
