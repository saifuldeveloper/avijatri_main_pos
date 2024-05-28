@php
if (!isset($index)) $index = 0;
if (!isset($transaction)) $transaction = null;
@endphp

<tr class="tr-main" data-index="{{ $index }}">
    <td>
        <button class="btn btn-danger btn-remove-row" data-parent-id="#closing-payment-table">
            <span class="fas fa-minus"></span>
        </button>
        @if ($transaction !== null)
            <input type="hidden" name="payment[{{ $index }}][id]" value="{{ $transaction->id }}">
        @endif
    </td>
    <td>
        <select name="payment[{{ $index }}][method]" class="form-control required-value">
            @foreach ($bankAccounts as $bankAccount)
                <option value="{{ $bankAccount->id }}" {{ ($transaction->fromAccount->account->id ?? 0) == $bankAccount->id ? 'selected' : '' }}>
                    @if ($bankAccount->account_no == 'cash')
                        {{ str_replace('cash-', '', $bankAccount->bank) }}
                    @else
                        {{ $bankAccount->account_no }}-{{ $bankAccount->bank }}-{{ $bankAccount->branch }}
                    @endif
                </option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="payment[{{ $index }}][amount]" class="form-control text-right taka required-value allow-empty input-payment-amount" value="{{ toFixed($transaction->amount ?? '') }}">
    </td>
</tr>
