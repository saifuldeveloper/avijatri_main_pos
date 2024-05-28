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
            {{-- @foreach ($expenses as $expense)
                <th style="text-align: center; font-weight: bold; width: 120px; background:#c7c7c7">{{ $expense->name }}
                </th>
            @endforeach --}}
        </tr>
    </thead>
    <tbody>
        @php
            $today = today();
            $dates = [];

            for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                $dates[] = \Carbon\Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
            }

        @endphp
        @foreach ($dates as $date)
            @php
                $invoice = \App\Models\Transaction::whereDate('created_at', $date)
                    ->where('transaction_type', 'income')
                    ->where('payment_type', 'retail-store')
                    ->sum('amount');

                $factory = \App\Models\Transaction::query()
                    ->whereDate('created_at', $date)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'factory')
                    ->sum('amount');

                $bank_entries_expense = \App\Models\View\BankAccountEntry::query()
                    ->whereDate('created_at', $date)
                    ->whereIn('type', ['expense', 'withdraw'])
                    ->sum('total_amount');

                $bank_entries_income = \App\Models\View\BankAccountEntry::query()
                    ->whereDate('created_at', $date)
                    ->whereIn('type', ['income', 'deposit'])
                    ->sum('total_amount');

                $employee_entries_expense = \App\Models\Transaction::query()
                    ->whereDate('created_at', $date)
                    ->where('transaction_type', 'expense')
                    ->where('payment_type', 'employee')
                    ->sum('amount');

                $loan_entries_in = \App\Models\LoanAccountEntry::query()
                    ->whereDate('created_at', $date)
                    ->where('type', 'in')
                    ->sum('total_amount');

                $loan_entries_out = \App\Models\LoanAccountEntry::query()
                    ->whereDate('created_at', $date)
                    ->where('type', 'out')
                    ->sum('total_amount');

                // $expensesEntries = \App\Models\ExpenseAccountEntry::query()->whereDate('created_at', $date)->get();

                $expensesEntries = \App\Models\Transaction::query()
                    ->whereDate('created_at', $date)
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
                <td style="text-align: center">{{ $date }}</td>
                <td style="text-align: center">{{ $invoice }}</td>
                <td style="text-align: center">{{ $bank_entries_expense }}</td>
                <td style="text-align: center">{{ $loan_entries_in }}</td>
                <td style="text-align: center">{{ $loan_entries_out }}</td>

                <td style="text-align: center">{{ $bank_entries_income }}</td>
                <td style="text-align: center">{{ $factory }}</td>
                <td style="text-align: center">{{ $employee_entries_expense }}</td>
                <td style="text-align: center">{{ $expensesEntries }}</td>
                {{-- @foreach ($expenses as $expense)
                    <td style="text-align: center">
                        {{ $expensesEntries->where('entry_id', $expense->id)->sum('total_amount') }}
                    </td>
                @endforeach --}}
            </tr>
        @endforeach

    </tbody>
</table>
