<?php

namespace App\Http\Controllers\Web;

use Excel;
use App\Models\Transaction;
use App\Exports\MonthlyTransactionReport;
use App\Exports\YearlyTransactionReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\BankAccount;
use App\Models\GiftTransaction;
use App\Models\PurchaseEntry;

use Illuminate\Support\Facades\DB;

class ReportController extends \App\Http\Controllers\Main\TransactionController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage reports']);
    // }

    public function transactionReportPage()
    {
        $years = Transaction::groupBy(DB::raw('year(created_at)'))->orderByRaw('year(created_at) desc')->selectRaw('year(created_at) yr')->get();
        return view('report.transaction-page', compact('years'));
    }

    public function dailyTransactionReport(Request $request)
    {

        if($request->date == null){
            return redirect()->back();
        }
        $date= $request->date;
        $data['date'] =$date;
        $data['purchases'] = PurchaseEntry::getPurchasesOn($date);
        $data['giftTransaction'] = GiftTransaction::getGiftPurchasesOn($date);
        $data['purchaseSummary'] = PurchaseEntry::getPurchaseSummaryOn($date);
        $data['incomes'] = Transaction::getIncomesOn($date);
        $data['expenses'] = Transaction::getExpensesOn($date);
        $data['incomesSum'] = Transaction::sumIncomesWithPreviousBalanceOn($date);
        $data['expensesSum'] = Transaction::sumExpensesOn($date);
        $data['initialCashBalance'] = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
        $data['finalCashBalance'] = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $path = public_path() . '/fonts';
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'mode' => 'utf-8',
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [$path]),
            'fontdata' => $fontData + [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'solaimanlipi',
        ]);
        // $mpdf->SetWatermarkText(new \Mpdf\WatermarkText('অভিযাত্রী '));
        $mpdf->showWatermarkText = true;

        // return view('excel.daily-transaction-report', $data);
      
        $mpdf->WriteHTML(view('excel.daily-transaction-report', $data)->render());

        $pdf = $mpdf->Output('daily-report.pdf', 'D');
    }


    public function monthlyTransactionReport(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $month_fixed = str_pad($month, 2, '0', STR_PAD_LEFT);

        $expenses = Expense::all();
        // return view('excel.transaction-report', compact('expenses', 'year', 'month'));

        return Excel::download(new MonthlyTransactionReport($year, $month), "report-{$year}-{$month_fixed}.xlsx");
    }

    public function yearlyTransactionReport(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $expenses = Expense::all();

        return Excel::download(new YearlyTransactionReport($year, $month, $expenses), "report-{$year}.xlsx");
    }


    // public function cash(Request $request)
    // {
    //     $vars = parent::cash($request);
    //     return view('report.cash', $vars);
    // }

    public function cash(Request $request)
    {
        $from = '2000-01-01';
        $to = \Carbon\Carbon::today()->format('Y-m-d');

        if ($request->has('from')) $from = $request->input('from');
        if ($request->has('to')) $to = $request->input('to');

        $vars = [
            'previous_cash' => BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($from),
            'total_sale' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'income')->where('payment_type', 'retail-store')->sum('amount'),

            'total_withdrawal' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'withdraw')
                ->sum('amount'),

            'total_loan_taken' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'income')->where('payment_type', 'loan-receipt')->sum('amount'),

            'total_payment' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'expense')->whereIn('payment_type', ['gift-supplier', 'factory'])->sum('amount'),

            'total_loan_paid' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'expense')->where('payment_type', 'loan-payment')->sum('amount'),

            'total_deposit' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'deposit')
                ->sum('amount'),

            'total_staff_expense' => \App\Models\Transaction::whereBetween('created_at', [$from, $to])
                ->where('transaction_type', 'expense')->where('payment_type', 'employee')->sum('amount'),

            'expenses' => \App\Models\Expense::join('account_books', function ($join) {
                $join->on('expenses.id', '=', 'account_books.account_id')->on('account_books.account_type', '=', \DB::raw("'expense'"));
            })->join('transactions', 'transactions.to_account_id', '=', 'account_books.id')
                ->whereBetween('transactions.created_at', [$from, $to])
                ->groupBy('expenses.name')
                ->selectRaw('expenses.name, sum(transactions.amount) amount')
                ->get(),
        ];


        return view('report.cash', $vars);
    }
}
