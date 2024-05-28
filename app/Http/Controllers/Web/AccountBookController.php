<?php

namespace App\Http\Controllers\Web;

use App\Models\AccountBook;
use App\Models\BankAccount;
use App\Models\ReturnToFactoryEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cheque;
use App\Models\GiftSupplierAccountEntry;
use App\Models\Purchase;
use App\Models\PurchaseEntry;
use App\Models\View\FactoryAccountEntry;
use Illuminate\Support\Collection;

class AccountBookController extends \App\Http\Controllers\Main\AccountBookController
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $accountBook = parent::show($accountBook);
        switch ($accountBook->account_type) {
            case 'factory':
                $entries       = FactoryAccountEntry::where('account_book_id', $accountBook->id)->orderBy('created_at', 'desc')->where('status',1)->paginate(10);
                $final_balance = 0;
                $total_balance = [];
                foreach ($entries->reverse() as $entry) {
                if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
                } else {
                $final_balance -= $entry->total_amount;
                }
                $total_balance[] = $final_balance;
                }
                $payment_amount = $entries->where('account_book_id',$accountBook->id)
                                  ->where('entry_type', '2')->sum('total_amount');
                $purchase_amount = $entries->where('account_book_id',$accountBook->id)
                                  ->where('entry_type', '0')->sum('total_amount');
                $return_amount = $entries->where('account_book_id',$accountBook->id)
                                  ->where('entry_type', '1')->sum('total_amount');
                $total_balance = array_reverse($total_balance);
                return view('factory.account-book', compact('accountBook','entries','purchase_amount','payment_amount','return_amount','total_balance'));

            case 'retail-store':
                return view('retail-store.account-book', compact('accountBook'));
            case 'gift-supplier':
                $entries  = GiftSupplierAccountEntry::where('account_book_id', $accountBook->id)->orderBy('id', 'desc')->paginate(10);
                $final_balance = 0;
                $total_balance = [];
                foreach ($entries->reverse() as $entry) {
                if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
                } else {
                $final_balance -= $entry->total_amount;
                }
                $total_balance[] = $final_balance;
                }
                $total_balance = array_reverse($total_balance);

                $payment_amount = $entries->where('account_book_id',$accountBook->id)
                ->where('entry_type', '2')->sum('total_amount');
                $purchase_amount = $entries->where('account_book_id',$accountBook->id)
                ->where('entry_type', '0')->sum('total_amount');
                return view('gift-supplier.account-book',compact('accountBook','entries','total_balance','payment_amount','purchase_amount'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountBook  $accountBook
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountBook $accountBook)
    {
        //
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

    public function closingPage(AccountBook $accountBook)
    {

        $accountBook->load('closingTransactions');
        $accountBook->appendClosingAttributes();
        $bankAccounts = BankAccount::all();
        switch ($accountBook->account_type) {
            case 'factory':
                $entries = FactoryAccountEntry::where('account_book_id',$accountBook->id)->get();
                $payment_amount = $entries->where('entry_type', '2')->sum('total_amount');
                $purchase_amount = $entries->where('entry_type', '0')->sum('total_amount');
                $return_amount = $entries->where('entry_type', '1')->sum('total_amount');
                $total_balance  =$purchase_amount -($payment_amount + $return_amount);
                return view('factory.closing', compact('accountBook', 'bankAccounts','total_balance'));

            case 'retail-store':
                return view('retail-store.closing', compact('accountBook', 'bankAccounts'));
            case 'gift-supplier':
                $entries =GiftSupplierAccountEntry::where('account_book_id',$accountBook->id)->get();
                $payment_amount = $entries->where('entry_type', '2')->sum('total_amount');
                $purchase_amount = $entries->where('entry_type', '0')->sum('total_amount');
                $total_balance  =$purchase_amount -$payment_amount ;

                $total_cheque_payment = Cheque::where('account_book_id', $accountBook->id)
                              ->where('closing_id', $accountBook->id)
                              ->sum('amount');

             return view('gift-supplier.closing', compact('accountBook', 'bankAccounts','purchase_amount','payment_amount','total_balance','total_cheque_payment'));

        }
    }

    public function closing(Request $request, AccountBook $accountBook)
    {
        $accountBook = parent::closing($request, $accountBook);
        // return redirect()->route('account-book.show', compact('accountBook'))->with('success-alert', 'ক্লোজিং সম্পন্ন হয়েছে।');
        return redirect()->route('account-book.show', ['account_book' => $accountBook->id])->with('success-alert', 'ক্লোজিং সম্পন্ন হয়েছে।');
    }

    public function forwardBalance(AccountBook $accountBook)
    {
        $accountBook = parent::forwardBalance($accountBook);
        if ($accountBook === null) {
            return back()->with('error-alert', 'এই কাজটি করা সম্ভব নয়।');
        }
        return back()->with('success-alert', 'পরিবর্তন সম্পন্ন হয়েছে।');
    }
}
