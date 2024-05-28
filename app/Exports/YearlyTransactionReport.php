<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class YearlyTransactionReport implements FromView
{
    protected $year, $month, $expenses;
    public function __construct($year, $month, $expenses)
    {
        $this->year = $year;
        $this->month = $month;
        $this->expenses = $expenses;
    }

    public function view(): View
    {
        $expenses = $this->expenses;
        $month = $this->month;
        $year = $this->year;
        return view('excel.monthly-transaction-report', compact('expenses', 'year', 'month'));
    }
}
