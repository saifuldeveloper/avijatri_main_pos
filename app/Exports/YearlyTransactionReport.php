<?php

namespace App\Exports;

use App\Models\Expense;
use App\Views\YearlyTransactionReportEntry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class YearlyTransactionReport implements FromView
{
	public function __construct($year) {
		$this->year = $year;
	}

    public function view(): View
    {
        $expenses = Expense::all();
        $entries = YearlyTransactionReportEntry::excelEntries($this->year);

        return view('excel.transaction-report', compact('expenses', 'entries'));
    }

    protected $year;
}
