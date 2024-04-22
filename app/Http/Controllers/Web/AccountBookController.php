<?php

namespace App\Http\Controllers\Web;

use App\Models\AccountBook;
use App\Models\BankAccount;
use App\Models\ReturnToFactoryEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseEntry;
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

        $purchases = Purchase::with('purchaseEntries.shoe')->where('account_book_id', $accountBook->id)->orderBy('created_at', 'desc')->get();
        $qty = 0;
        $qty = $purchases->flatMap(function ($item) {
            return $item->purchaseEntries->pluck('count'); })->sum();
        $payment_amount = $purchases->map(function ($item) {
            return $item;
        })->sum('payment_amount');

        $purchases_amount = $purchases->flatMap(function ($item) {
            return $item->purchaseEntries->map(function ($entry) {
                return $entry->shoe->purchase_price * $entry->count / 12;
            });
        })->sum();

        $factoryEntries = $accountBook->factoryentries()->paginate(50); 
        $returnsum = $factoryEntries->filter(fn($entry) => $entry->entry_type == 1)
                        ->flatMap(fn($entry) => $entry->returnshoe?->returnentries)
                        ->sum(fn($item) => ($item->shoe->purchase_price * $item->count) / 12);
  
        switch ($accountBook->account_type) {
            case 'factory':
                return view('factory.account-book', compact('accountBook','purchases_amount', 'payment_amount','factoryEntries','returnsum'));

            case 'retail-store':
                $entries =$accountBook->retailEntries()->with('invoices.invoiceEntries.shoe','invoices.transactions')->paginate(50);
                return view('retail-store.account-book', compact('accountBook','entries'));
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
                $accountBook->load('closingCheques');
                return view('factory.closing', compact('accountBook', 'bankAccounts'));

            case 'retail-store':
                return view('retail-store.closing', compact('accountBook', 'bankAccounts'));
        }
    }

    public function closing(Request $request, AccountBook $accountBook)
    {
        $accountBook = parent::closing($request, $accountBook);
        return redirect()->route('account-book.show', compact('accountBook'))->with('success-alert', 'ক্লোজিং সম্পন্ন হয়েছে।');
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
