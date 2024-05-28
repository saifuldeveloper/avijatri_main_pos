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
use App\Models\ChequeAccountEntries;
use App\Models\Employee;
use App\Models\EmployeeAccountEntry;
use App\Models\Expense;
use App\Models\View\BankAccountEntry;
use App\Models\ExpenseAccountEntry;
use App\Models\GiftSupplier;
use App\Models\GiftSupplierAccountEntry;
use App\Models\Loan;
use App\Models\LoanAccountEntry;
use App\Models\RetailStore;
use App\Models\View\FactoryAccountEntry;
use App\Models\View\RetailStoreAccountEntry;

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
        $incomes = Transaction::getIncomesOn($date);
        $expenses = Transaction::getExpensesOn($date);;
        $incomesSum = Transaction::sumIncomesWithPreviousBalanceOn($date);
        $expensesSum = Transaction::sumExpensesOn($date);
        $initialCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
        $finalCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date->addDay());
        return (object)compact('purchases', 'purchaseSummary','incomes','expenses','expensesSum','incomesSum','initialCashBalance','finalCashBalance');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


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
        }
        else if($request->input('account_type') == 'factory' && $request->input('payment_method') !== 'cheque') {
            $factory = Factory::find($request->input('account_id'));
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
            $entry                  = new FactoryAccountEntry;
            $entry->account_id      = $factory->id;
            $entry->account_book_id = $factory->getCurrentAccountBook()->id;
            $entry->entry_type      = 2;
            $entry->entry_id        = $transaction->id;
            $entry->total_amount    = $request->input('amount');
            $entry->save();
        }else if($request->input('account_type') == 'gift-supplier' && $request->input('payment_method') !== 'cheque') {
           
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
            $account                = GiftSupplier::find($request->input('account_id'));
            $bankAccount            = BankAccount::find($request->input('payment_method'));
            $entry                  = new GiftSupplierAccountEntry;
            $entry->entry_id        = $account->id;    
            $entry->entry_type      = 2;    
            $entry->account_book_id = $account->getCurrentAccountBook()->id;  
            $entry->total_amount    = $request->input('amount');
            $entry->description     = $request->input('description');
            $entry->account_id      = $bankAccount->id;
            $entry->account_name    = $bankAccount->bank;
            $entry->save();

        }
        else if($request->input('account_type') == 'gift-supplier' && $request->input('payment_method') == 'cheque') {
            $account = GiftSupplier::find($request->input('account_id'));
            $transaction = Cheque::issue(
                $request->input('cheque_no'),
                $account->getCurrentAccountBook(),
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
            $cheque      = Cheque::find($request->cheque_no);
            $accountBook = AccountBook::find($cheque->account_book_id);


            $entry                  =new ChequeAccountEntries;
            $entry->entry_id        =$cheque->id;
            $entry->entry_type      =0;
            $entry->account_book_id =$cheque->account_book_id;
            $entry->total_amount    = $request->input('amount');
            $entry->save();

              if($accountBook->account_type =='factory'){
                $entry                  = new FactoryAccountEntry;
                $entry->account_id      = $accountBook->account_id;
                $entry->account_book_id = $accountBook->id;
                $entry->entry_type      = 2;
                $entry->entry_id        = $transaction->id;
                $entry->total_amount    = $request->input('amount');
                $entry->save();
              }else{
                $entry                  = new GiftSupplierAccountEntry;
                $entry->account_id      = $accountBook->account_id;
                $entry->account_book_id = $accountBook->id;
                $entry->entry_type      = 2;
                $entry->entry_id        = $transaction->id;
                $entry->total_amount    = $request->input('amount');
                $entry->save();
              }

        } else if($request->input('account_type') == 'retail-closing') {
            $transaction = Transaction::createTransaction(
                'account-book',
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description'), null,
                $request->input('account_id')
            );
            $accountBook = AccountBook::find($request->input('account_id'));
            $accountBook->closing_balance -= $transaction->amount;
            $accountBook->save();
        }else if($request->input('account_type') == 'employee'){
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
            $employe =Employee::find($request->account_id);
            $entry = new EmployeeAccountEntry;
            $entry->entry_id        =$employe->id;
            $entry->entry_type      ='0';
            $entry->account_book_id =$employe->getCurrentAccountBook()->id;
            $entry->account_name    =$employe->name;
            $entry->account_id      =$request->input('account_id');
            $entry->account_type    =$request->input('payment_type');
            $entry->description     =$request->input('description');
            $entry->total_amount    = $request->input('amount');
            $entry->save();
        }else if($request->input('account_type') == 'expense'){
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );

            $expence =Expense::find($request->input('account_id'));
            $bankAccount =BankAccount::find($request->input('payment_method'));
            $entry                  =new ExpenseAccountEntry;
            $entry->entry_id        =$expence->id;
            $entry->account_book_id =$expence->getCurrentAccountBook()->id;
            $entry->account_name    =$expence->name;
            $entry->account_id      =$expence->id;
            $entry->account_type    =$request->input('payment_type');
            $entry->description     =$request->input('description');
            $entry->total_amount    =$request->input('amount');
            $entry->save();
        }else if($request->input('account_type') == 'loan-receipt' ||   $accountType = $request->input('account_type') == 'loan-payment'){
  
            $transactionType = ($request->input('account_type') == 'loan-receipt') ? 'income' : 'expense';
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $transactionType,
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );

            $loan                   = Loan::find($request->account_id);
            $bankAccount            = BankAccount::with('accountType')->find($request->payment_method);
            $entry                  = new LoanAccountEntry;
            $entry->entry_id        = $loan->id;
            $entry->entry_type      = $request->input('account_type') == 'loan-receipt' ? 0 : 1;
            $entry->account_book_id = $loan->getCurrentAccountBook()->id;
            $entry->account_name    = $bankAccount->bank;
            $entry->account_id      = $bankAccount->id;
            $entry->account_type    = $bankAccount->accountType->type;
            $entry->description     = $request->description;
            $entry->total_amount    = $request->amount;
            $entry->type            = $request->input('account_type') == 'loan-receipt' ? 'in' : 'out';
            $entry->save();

        } else if($request->input('account_type') == 'retail-store'){
       
            $transaction = Transaction::createTransaction(
                $request->input('account_type'),
                $request->input('account_id'),
                $request->input('payment_type'),
                $request->input('payment_method'),
                $request->input('amount'),
                $request->input('description')
            );
           $ratail_account         =RetailStore::find($request->account_id);
           $bankAccount            = BankAccount::with('accountType')->find($request->payment_method);
           $entry                  = new RetailStoreAccountEntry;
           $entry->account_book_id = $ratail_account->getCurrentAccountBook()->id;
           $entry->entry_type      = '3';
           $entry->description     = $request->input('description');
           $entry->account_name    = $bankAccount->bank;
           $entry->paid_amount     = $request->input('amount');
           $entry->save();
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
