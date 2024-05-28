<?php

namespace App\Exports;

use App\Models\Expense;
use App\Views\MonthlyTransactionReportEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthlyTransactionReport implements FromView
{
    protected $year, $month;
    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function view(): View
    {
        $expenses = Expense::all();
        $year = $this->year;
        $month = $this->month;

        $expenses = Expense::all();

        // $entries = MonthlyTransactionReportEntry::excelEntries($year, $month, $data);

        return view('excel.transaction-report', compact('expenses', 'year', 'month'));
    }
}
