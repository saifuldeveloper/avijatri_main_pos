@extends('layouts.app', ['title' => 'মহাজন খাতা'])

@section('content')
    <h1>মহাজন খাতা <small><a href="{{ route('factory.create') }}" class="btn-new" data-toggle="modal"
                data-target="#factory-form">নতুন মহাজন</a></small></h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width:20%">নাম</th>
                <th style="width:30%">ঠিকানা</th>
                <th style="width:25%">মোবাইল নং</th>
                <th style="width:25%">অপশন</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($factories as $factory)
                <tr>
                    <td><a href="{{ route('factory.show', ['factory' => $factory->id]) }}">{{ $factory->name }}</a></td>
                    <td>{{ $factory->address }}</td>
                    <td>{{ $factory->mobile_no }}</td>
                    <td>
                        @include('layouts.crud-buttons', [
                            'model' => 'factory',
                            'parameter' => 'factory',
                            'object' => $factory,
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($trashFactories->count() > 0)
        <div class="mt-5">
            <h2> ডিলিট মহাজন খাতা </h2>
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th style="width:20%">নাম</th>
                        <th style="width:30%">ঠিকানা</th>
                        <th style="width:25%">মোবাইল নং</th>
                        <th style="width:25%">অপশন</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($trashFactories as $factory)
                        <tr>
                            <td>{{ $factory->name }}</td>
                            <td>{{ $factory->address }}</td>
                            <td>{{ $factory->mobile_no }}</td>
                            <td>@include('layouts.crud-buttons', [
                                'model' => 'factory',
                                'parameter' => 'factory',
                                'object' => $factory,
                            ])</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif


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
