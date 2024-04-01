<table>
	<thead>
		<tr>
			<th>তারিখ</th>
			<th>বিক্রি</th>
			<th>ব্যাংক তোলা</th>
			<th colspan="4">হাওলাত</th>
			<th>ব্যাংক জমা</th>
			<th>তাগাদা</th>
			<th>স্টাফ খরচ</th>
			@foreach($expenses as $expense)
			<th>{{ $expense->name }}</th>
			@endforeach
		</tr>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th colspan="2">হাওলাত আনা</th>
			<th colspan="2">হাওলাত ফেরত</th>
		</tr>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th>বাবদ</th>
			<th>টাকা</th>
			<th>বাবদ</th>
			<th>টাকা</th>
		</tr>
	</thead>
	<tbody>
		@foreach($entries as $entry)
		<tr>
			@foreach($entry as $row => $column)
			@if($row == 'loan' || $row == 'loan_payment')
			<td></td>
			@endif
			<td>{{ $column }}</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>