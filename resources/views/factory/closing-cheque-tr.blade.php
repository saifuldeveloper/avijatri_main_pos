@php
if(!isset($index)) $index = 0;
if(!isset($cheque)) $cheque = null;
@endphp
<tr class="tr-main" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row" data-parent-id="#closing-cheque-table"><span class="fas fa-minus"></span></button></td>
	<td><input type="text" name="cheque[{{ $index }}][id]" class="form-control required-value empty-row number" value="{{ $cheque === null ? '' : $cheque->id }}"></td>
	<td><input type="date" name="cheque[{{ $index }}][due_date]" class="form-control required-value empty-row" value="{{ $cheque === null ? '' : $cheque->due_date }}"></td>
	<td><input type="text" name="cheque[{{ $index }}][amount]" class="form-control text-right taka required-value empty-row input-cheque-amount" value="{{ $cheque === null ? '' : toFixed($cheque->amount) }}"></td>
</tr>