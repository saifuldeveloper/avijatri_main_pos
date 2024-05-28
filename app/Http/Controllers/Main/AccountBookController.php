<?php

namespace App\Http\Controllers\Main;

use App\Models\AccountBook;
use App\Models\Cheque;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RetailStore;
use App\Models\Factory;
use App\Models\GiftSupplierAccountEntry;
use App\Models\View\FactoryAccountEntry;

class AccountBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountBook  $accountBook
     * @return \Illuminate\Http\Response
     */
    public function show(AccountBook $accountBook)
    {
        $accountBook->load('account','retailAccount',);
        $accountBook->append('entries');
 
        switch($accountBook->account_type) {
            case 'factory':
            $accountBook->append('total_products_worth');
            $accountBook->append('payment');
            $accountBook->append('payment_percentage');
    
            break;

            case 'retail-store':
            $accountBook->append('total_sale_minus_commission');
            $accountBook->append('total_return_minus_commission');
        }
      

        return $accountBook;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountBook  $accountBook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountBook $accountBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountBook  $accountBook
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountBook $accountBook)
    {
        //
    }

    public function closing(Request $request, AccountBook $accountBook) {


        switch($accountBook->account_type) {
            case 'factory':

            $balance = $accountBook->balance;
            $accountBook->fill($request->all());
            $payments = $request->input('payment');
            $payment_sum = 0;
            if ($payments !== null) {
                $payment_amounts = array_column($payments, 'amount');
                $payment_sum = array_sum($payment_amounts);
                foreach ($payments as $payment) {
                    if (empty($payment['amount']) || empty($payment['method'])) {
                        continue;
                    }
                    $transaction = Transaction::createTransaction('account-book', $accountBook->id, 'expense', $payment['method'],  $payment['amount'], '',  null, $accountBook->id
                    );
                }
            }
            $cheques = $request->input('cheque');
            $cheque_sum = 0;
            if ($cheques !== null) {
                $cheque_amounts = array_column($cheques, 'amount');
                $cheque_sum = array_sum($cheque_amounts);
                foreach($cheques as $cheque_entry) {
                    if(empty($cheque_entry['id'])) {
                        continue;
                    }
                    $cheque = Cheque::issue($cheque_entry['id'], $accountBook, $cheque_entry['amount'], $cheque_entry['due_date'], null, $accountBook->id);

                }  
           }
           $accountEntries                  = new FactoryAccountEntry;
           $accountEntries->account_book_id = $accountBook->id;
           $accountEntries->entry_type      = 2;
           $accountEntries->entry_id        = $accountBook->id;
           $accountEntries-> total_amount    = $payment_sum + $cheque_sum;
           $accountEntries->closing_id      = $accountBook->id;
           $accountEntries->save();
           $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum - $cheque_sum;
            break;


            case 'retail-store':
            $balance = $accountBook->balance;
            $accountBook->fill($request->all());
            $payments = $request->input('payment');
            $payment_sum = 0;
            foreach($payments as $payment) {
                if(empty($payment['amount'])) {
                    continue;
                }
                $transaction = Transaction::createTransaction('account-book', $accountBook->id, 'income', $payment['method'], $payment['amount'], '', null, $accountBook->id);
                $payment_sum += $payment['amount'];
            }

            $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum;
            break;
            case 'gift-supplier':
                $balance = $accountBook->balance;
                $accountBook->fill($request->all());
                $payments = $request->input('payment');
                $payment_sum = 0;
                if ($payments !== null) {
                    $payment_amounts = array_column($payments, 'amount');
                    $payment_sum = array_sum($payment_amounts);
                    foreach ($payments as $payment) {
                        if (empty($payment['amount']) || empty($payment['method'])) {
                            continue;
                        }
                        $transaction = Transaction::createTransaction('account-book', $accountBook->id, 'expense', $payment['method'],  $payment['amount'], '',  null, $accountBook->id
                        );
                    }
                }
                $cheques = $request->input('cheque');
                $cheque_sum = 0;
                if ($cheques !== null) {
                    $cheque_amounts = array_column($cheques, 'amount');
                    $cheque_sum = array_sum($cheque_amounts);
                    foreach($cheques as $cheque_entry) {
                        if(empty($cheque_entry['id'])) {
                            continue;
                        }
                        $cheque = Cheque::issue($cheque_entry['id'], $accountBook, $cheque_entry['amount'], $cheque_entry['due_date'], null, $accountBook->id);
    
                    }  
               }
               $accountEntries                  = new GiftSupplierAccountEntry;
               $accountEntries->account_book_id = $accountBook->id;
               $accountEntries->entry_type      = 2;
               $accountEntries->entry_id        = $accountBook->id;
               $accountEntries-> total_amount   = $payment_sum + $cheque_sum;
               $accountEntries->closing_id      = $accountBook->id;
               $accountEntries->save();
               break;
        }
        $accountBook->open = false;
        $accountBook->closing_date = \Carbon\Carbon::today();
        $accountBook->save();
   
        $newBook = new AccountBook();
        $newBook->account_type =$accountBook->account_type;
        $accountBook->account->accountBooks()->save($newBook);
       
        return $newBook;
    }

    public function forwardBalance(AccountBook $accountBook) {
        if($accountBook->account_type != 'retail-store' || $accountBook->open) {
            return null;
        }
        $accountBook->balance_carry_forward = !$accountBook->balance_carry_forward;
        $accountBook->save();
        return $accountBook;
    }
}
