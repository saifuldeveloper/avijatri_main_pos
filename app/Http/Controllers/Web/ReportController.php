<?php

namespace App\Http\Controllers\Web;

use Excel;
use App\Models\Transaction;
use App\Views\TransactionReportEntry;
use App\Exports\MonthlyTransactionReport;
use App\Exports\YearlyTransactionReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends \App\Http\Controllers\Main\ReportController
{
    public function __construct() {
        $this->middleware(['permission:manage reports']);
    }

	public function transactionReportPage() {
		$years = Transaction::groupBy(\DB::raw('year(created_at)'))->orderByRaw('year(created_at) desc')->selectRaw('year(created_at) yr')->get();
		return view('report.transaction-page', compact('years'));
	}

	public function cash(Request $request) {
		$vars = parent::cash($request);
    	return view('report.cash', $vars);
	}
}
