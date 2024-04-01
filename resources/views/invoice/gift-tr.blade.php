@php
if(!isset($index)) $index = 0;
@endphp
<tr class="tr-main" data-index="{{ $index }}">
	<td><button class="btn btn-danger btn-remove-row" data-parent-id="#gift-table"><span class="fas fa-minus"></span></button></td>
	<td colspan="2">{{ select($errors, "gifts.{$index}.gift_id", '', '', $gifts, 'input-gift required-value empty-row') }}</td>
	<td>{{ input($errors, 'text', "gifts.{$index}.count", '', '', 'input-gift-count number required-value empty-row') }}</td>
</tr>