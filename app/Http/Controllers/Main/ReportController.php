<?php

namespace App\Http\Controllers\Main;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaction;
use App\Views\TransactionReportEntry;
use App\Exports\MonthlyTransactionReport;
use App\Exports\YearlyTransactionReport;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function monthlyTransactionReport(Request $request) {
	
    	$year = $request->input('year');
    	$month = $request->input('month');
    	$month_fixed = str_pad($month, 2, '0', STR_PAD_LEFT);
    	return Excel::download(new MonthlyTransactionReport($year, $month), "report-{$year}-{$month_fixed}.xlsx");
    }

    public function yearlyTransactionReport(Request $request) {
    	$year = $request->input('year');
    	return Excel::download(new YearlyTransactionReport($year), "report-{$year}.xlsx");
    }

    public function cash(Request $request) {
    	$from = '2000-01-01';
    	$to = \Carbon\Carbon::today()->format('Y-m-d');

    	if($request->has('from')) $from = $request->input('from');
    	if($request->has('to')) $to = $request->input('to');

    	$vars = [
    		'previous_cash' =>BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($from),
    		'total_sale' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->where('account_type', 'retail-store');
	    		})->whereHas('toAccount', function($query) {
	    			$query->where('account_type', 'bank-account');
	    		})->sum('amount'),
    		'total_withdrawal' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->whereHasMorph('account', [\App\Models\BankAccount::class], function($query) {
    					$query->where('account_no', '<>', 'cash');
    				});
	    		})->whereHas('toAccount', function($query) {
	    			$query->whereHasMorph('account', [\App\Models\BankAccount::class, \App\Models\Factory::class, \App\Models\GiftSupplier::class, \App\Models\Cheque::class], function($query, $type) {
	    				if($type === \App\Models\BankAccount::class) {
	    					$query->where('account_no', '=', 'cash');
	    				}
    				});
	    		})->sum('amount'),
    		'total_loan_taken' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->where('account_type', 'loan');
	    		})->whereHas('toAccount', function($query) {
	    			$query->where('account_type', 'bank-account');
	    		})->sum('amount'),
    		'total_payment' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->where('account_type', 'bank-account');
	    		})->whereHas('toAccount', function($query) {
	    			$query->whereIn('account_type', ['factory', 'gift-supplier', 'cheque']);
	    		})->sum('amount'),
    		'total_loan_paid' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->where('account_type', 'bank-account');
	    		})->whereHas('toAccount', function($query) {
	    			$query->where('account_type', 'loan');
	    		})->sum('amount'),
    		'total_deposit' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->whereHasMorph('account', [\App\Models\BankAccount::class, \App\Models\RetailStore::class], function($query, $type) {
	    				if($type === \App\Models\BankAccount::class) {
    						$query->where('account_no', '=', 'cash');
	    				}
    				});
	    		})->whereHas('toAccount', function($query) {
	    			$query->whereHasMorph('account', [\App\Models\BankAccount::class], function($query) {
    					$query->where('account_no', '<>', 'cash');
    				});
	    		})->sum('amount'),
    		'total_staff_expense' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
	    		->whereHas('fromAccount', function($query) {
	    			$query->where('account_type', 'bank-account');
	    		})->whereHas('toAccount', function($query) {
	    			$query->where('account_type', 'employee');
	    		})->sum('amount'),
	    	'expenses' => \App\Models\Expense::join('account_books', function($join) {
	    		$join->on('expenses.id', '=', 'account_books.account_id')->on('account_books.account_type', '=', \DB::raw("'expense'"));
	    	})->join('transactions', 'transactions.to_account_id', '=', 'account_books.id')
	    	->whereBetween('transactions.created_at', [$from, $to])
	    	->groupBy('expenses.name')
	    	->selectRaw('expenses.name, sum(transactions.amount) amount')
	    	->get(),
    	];
    	return $vars;
    }
}
