<?php

namespace App\Http\Controllers\Main;

use App\Models\Cheque;
use App\Models\Factory;
use App\Models\Transaction;
use App\Models\PurchaseEntry;
use App\Models\AccountBook;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('date')) {
            $date = \Carbon\Carbon::parse($request->input('date'));
        } else {
            $date = \Carbon\Carbon::today();
        }

        $purchases = PurchaseEntry::getPurchasesOn($date);
        $purchaseSummary = PurchaseEntry::getPurchaseSummaryOn($date);
        // $incomes = Transaction::getIncomesOn($date);
        // $expenses = Transaction::getExpensesOn($date);
        // $incomesSum = Transaction::sumIncomesWithPreviousBalanceOn($date);
        // $expensesSum = Transaction::sumExpensesOn($date);
        // $initialCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
        // $finalCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date->addDay());
        return (object)compact('purchases', 'purchaseSummary');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        if($request->input('account_type') == 'withdraw' || $request->input('account_type') == 'deposit') {
            $transaction = Transaction::createBankToCashTransaction(
                $request->input('account_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
        } else if($request->input('account_type') == 'factory' && $request->input('payment_method') == 'cheque') {
            $factory = Factory::find($request->input('account_id'));
            $transaction = Cheque::issue(
                $request->input('cheque_no'),
                $factory->getCurrentAccountBook(),
                $request->input('amount'),
                $request->input('due_date')
            );
        } else if($request->input('account_type') == 'cheque') {
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('cheque_no'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
        } else if($request->input('account_type') == 'retail-closing') {
            $transaction = Transaction::createTransaction(
                'account-book',
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description'),
                null,
                $request->input('account_id')
            );
            $accountBook = AccountBook::find($request->input('account_id'));
            $accountBook->closing_balance -= $transaction->amount;
            $accountBook->save();
        } else {
            $accountType = $request->input('account_type');
            if($accountType == 'loan-receipt' || $accountType == 'loan-payment') {
                $accountType = 'loan';
            }
            $transaction = Transaction::createTransaction(
                $accountType,
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
        }
        return $transaction;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
