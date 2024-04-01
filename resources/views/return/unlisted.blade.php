<h3>ফেরতের বিবরণ</h3>
<table class="table table-striped text-center">
	<thead>
		<tr>
			<th>আইডি</th>
			<th>টাইপ</th>
			<th>রং</th>
			<th>গায়ের দাম</th>
			<th>জোড়া</th>
			<th>মোট গায়ের দাম</th>
			<th>কমিশন</th>
			<th>কমিশন বাদে দাম</th>
		</tr>
	</thead>
	<tbody>
		@foreach($returns as $return)
		<tr>
			<td>{{ $return->shoe->id }}</td>
			<td>{{ $return->shoe->category->full_name }}</td>
			<td>{{ $return->shoe->color->name }}</td>
			<td>{{ toFixed($return->shoe->retail_price) }}</td>
			<td>{{ $return->count }}</td>
			<td>{{ toFixed($return->shoe->retail_price * $return->count) }}</td>
			<td>{{ toFixed($return->shoe->retail_price * $return->count * $return->commission / 100) }}</td>
			<td>{{ toFixed($return->commission_deducted) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>