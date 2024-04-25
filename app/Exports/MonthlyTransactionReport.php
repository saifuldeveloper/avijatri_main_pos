<?php

namespace App\Exports;

use App\Models\Expense;
use App\Views\MonthlyTransactionReportEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthlyTransactionReport implements FromView
{
	public function __construct($year, $month) {
		$this->year = $year;
		$this->month = $month;
	}

    public function view(): View
    {
        $expenses = Expense::all();
        $entries = MonthlyTransactionReportEntry::excelEntries($this->year, $this->month);

        return view('excel.transaction-report', compact('expenses', 'entries'));
    }

    protected $year, $month;
}
