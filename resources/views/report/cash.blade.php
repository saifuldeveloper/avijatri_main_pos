@extends('layouts.app', ['title' => 'ক্যাশ আদান-প্রদানের হিসাব'])

@section('content')
    <h1>ক্যাশ আদান-প্রদানের হিসাব</h1>
    <form action="{{ route('report.cash') }}" method="get" class="form-inline mb-3">
		<label for="cash-from">শুরুর তারিখ</label>
		<input type="date" name="from" id="cash-from" class="form-control mx-3" value="{{ request('from') }}" required>
		<label for="cash-to">শেষ তারিখ</label>
		<input type="date" name="to" id="cash-to" class="form-control mx-3" value="{{ request('to') }}" required>
		<button type="submit" class="btn btn-primary">রিপোর্ট দেখুন</button>
	</form>
	
    <div class="row">
        <div class="col-6">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>ক্যাশ, সাবেক</td>
                        <td class="text-right font-weight-bold">{{ $previous_cash }}</td>
                    </tr>
                    <tr>
                        <td>বিক্রি (+)</td>
                        <td class="text-right">{{ $total_sale }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $previous_cash + $total_sale }}</td>
                    </tr>
                    <tr>
                        <td>ব্যাংক তোলা (+)</td>

                        <td class="text-right">{{ $total_withdrawal }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum + $total_withdrawal }}</td>
                    </tr>
                    <tr>
                        <td>হাওলাত আনা (+)</td>
                        <td class="text-right">{{ $total_loan_taken }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum + $total_loan_taken }}</td>
                    </tr>
                    <tr>
                        <td>তাগাদা (-)</td>
                        <td class="text-right">{{ $total_payment }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum - $total_payment }}</td>
                    </tr>
                    <tr>
                        <td>হাওলাত ফেরত (-)</td>
                        <td class="text-right">{{ $total_loan_paid }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum - $total_loan_paid }}</td>
                    </tr>
                    <tr>
                        <td>ব্যাংক জমা (-)</td>
                        <td class="text-right">{{ $total_deposit }}</td>

                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum - $total_deposit }}</td>

                    </tr>
                    <tr>
                        <td>স্টাফ (-)</td>
                        <td class="text-right">{{ $total_staff_expense }}</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ $sum = $sum - $total_staff_expense }}</td>
                    </tr>

                    @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->name }} (-)</td>
                            <td class="text-right">{{ $expense->amount }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-right font-weight-bold">{{ $sum = $sum - $expense->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
