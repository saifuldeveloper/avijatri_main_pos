@extends('layouts.app', ['title' => 'জুতার রং'])

@section('content')
    <h1>জুতার রং <small><a href="{{ route('color.create') }}" class="btn-new" data-toggle="modal" data-target="#color-form">নতুন
                রং</a></small></h1>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width:60%">জুতার রং</th>
                    <th style="width:40%">অপশন</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($colors as $color)
                    <tr>
                        <td>{{ $color->name }}</td>
                        <td>@include('layouts.crud-buttons', [
                            'model' => 'color',
                            'parameter' => 'color',
                            'object' => $color,
                        ])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @if ($trashColor->count() > 0)
        <div class="mt-5">
            <h2> ডিলিট জুতার রং </h2>
            <table class="table table-striped">

                <thead>
                    <tr>
                        <th style="width:60%">জুতার রং</th>
                        <th style="width:40%">অপশন</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($trashColor as $color)
                        <tr>
                            <td>{{ $color->name }}</td>
                            <td>@include('layouts.crud-buttons', [
                                'model' => 'color',
                                'parameter' => 'color',
                                'object' => $color,
                            ])</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif



    <div id="color-form" class="modal fade form-modal" tabindex="-1" role="dialog" aria-labelledby="form-modal-title"
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
