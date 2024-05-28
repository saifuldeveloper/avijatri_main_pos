<table>
    <thead>
        <tr>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">তারিখ</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">বিক্রি</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">ব্যাংক তোলা</th>
            <th style="text-align: center; font-weight: bold; width: 150px; background:#c7c7c7">হাওলাত আনা</th>
            <th style="text-align: center; font-weight: bold; width: 150px; background:#c7c7c7">হাওলাত ফেরত</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">ব্যাংক জমা</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">তাগাদা</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">স্টাফ খরচ</th>
            <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">অন্যান্য খরচ</th>
        </tr>
    </thead>
    <tbody>
        @php
            $months = range(1, 12);
        @endphp
        @foreach ($months as $month)
            @php
                $monthName = \Carbon\Carbon::createFromDate(null, $month, 1)->format('F Y');

                $invoice = \App\Models\Transaction::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'income')
                    ->where('payment_type', 'retail-store')
                    ->sum('amount');

                $factory = \App\Models\Transaction::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'factory')
                    ->sum('amount');

                $bank_entries_expense = \App\Models\View\BankAccountEntry::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereIn('type', ['expense', 'withdraw'])
                    ->sum('total_amount');

                $bank_entries_income = \App\Models\View\BankAccountEntry::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereIn('type', ['income', 'deposit'])
                    ->sum('total_amount');

                $employee_entries_expense = \App\Models\Transaction::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'employee')
                    ->sum('amount');

                $loan_entries_in = \App\Models\Transaction::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'loan-receipt')
                    ->sum('amount');

                $loan_entries_out = \App\Models\Transaction::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'loan-payment')
                    ->sum('amount');

                $expensesEntries = \App\Models\Transaction::query()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'expense')
                    ->sum('amount');

            @endphp

            @if (
                $invoice == 0 &&
                    $bank_entries_expense == 0 &&
                    $loan_entries_in == 0 &&
                    $loan_entries_out == 0 &&
                    $bank_entries_income == 0 &&
                    $factory == 0 &&
                    $employee_entries_expense == 0 &&
                    $expensesEntries == 0)
                @continue
            @endif
            <tr>
                <td style="text-align: center">{{ $monthName }}</td>
                <td style="text-align: center">{{ $invoice }}</td>
                <td style="text-align: center">{{ $bank_entries_expense }}</td>
                <td style="text-align: center">{{ $loan_entries_in }}</td>
                <td style="text-align: center">{{ $loan_entries_out }}</td>

                <td style="text-align: center">{{ $bank_entries_income }}</td>
                <td style="text-align: center">{{ $factory }}</td>
                <td style="text-align: center">{{ $employee_entries_expense }}</td>
                <td style="text-align: center">{{ $expensesEntries }}</td>

            </tr>
        @endforeach



    </tbody>
</table>
