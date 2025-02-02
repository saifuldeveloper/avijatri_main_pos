@extends('layouts.app', ['title' => 'চেক খাতা'])

@section('content')
    <h1>চেক খাতা</h1>
    <table class="table table-bordered table-account-book text-center">
        <thead>
            <tr>
                <th>#</th>
                <th style="width:10%">তারিখ</th>
                <th style="width:10%">চেক নং</th>
                <th class="text-left" style="width:22%">নাম</th>
                <th style="width:10%">মোট টাকা</th>
                <th style="width:13%">পরিশোধের তারিখ</th>
                <th style="width:35%" class="p-0">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th style="width:30%" class="border-top-0 border-bottom-0 border-left-0">তাগাদা তারিখ</th>
                                <th style="width:30%" class="border-top-0 border-bottom-0">পরিশোধ</th>
                                <th style="width:40%" class="border-top-0 border-bottom-0 border-right-0">অবস্থা</th>
                            </tr>
                        </thead>
                    </table>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cheques as $i => $cheque)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ dateTimeFormat($cheque->created_at) }}</td>
                    <td>{{ $cheque->id }}</td>
                    <td class="text-left">
                        @if($cheque->attachment_type =='factory')
                        {{ $cheque->accountBook->account->name }}
                        @elseif ($cheque->attachment_type =='gift-supplier')
                        {{ @$cheque->accountBook->giftSupplierAccount->name }}
                        @endif
                    </td>
                    <td>{{ toFixed($cheque->amount) }}</td>
                    <td>{{ dateFormat($cheque->due_date, 'd/m/Y', 'Y-m-d') }}</td>
                    <td class="p-0">
                        <table class="table m-0">
                            <tbody>
                                @php
                                    $size = $cheque->getCurrentAccountBook() ? $cheque->entries->count() : 0;
                                @endphp
                                @if ($size > 0)
                                    @foreach ($cheque->entries as $j => $entry)
                                        <tr>
                                            <td style="width:30%"
                                                class="border-left-0{{ $j == 0 ? ' border-top-0' : '' }}{{ $j == $size - 1 ? ' border-bottom-0' : '' }}">
                                                {{ $entry->created_at }}</td>
                                            <td style="width:30%"
                                                class="{{ $j == 0 ? ' border-top-0' : '' }}{{ $j == $size - 1 ? ' border-bottom-0' : '' }}">
                                                {{ toFixed($entry->total_amount) }}</td>
                                            <td style="width:40%"
                                                class="border-right-0{{ $j == 0 ? ' border-top-0' : '' }}{{ $j == $size - 1 ? ' border-bottom-0' : '' }}">
                                                {{ $cheque->amount - $cheque->current_balance[$j] > 0 ? $cheque->amount - $cheque->current_balance[$j] . ' বাকী' : 'পরিশোধ' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $cheques->links('pagination.default') }}
@endsection
