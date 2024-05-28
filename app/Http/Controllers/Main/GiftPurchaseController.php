<?php

namespace App\Http\Controllers\Main;

use App\Models\GiftPurchase;
use App\Models\GiftSupplier;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\GiftPurchaseRequest;
use App\Models\BankAccount;
use App\Models\GiftSupplierAccountEntry;
use App\Models\Cheque;
use App\Models\Gift;

class GiftPurchaseController extends Controller
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
    public function store(GiftPurchaseRequest $request)
    {

     
        // dd($request->all());
   
        $request->validated();
        $giftSupplier = GiftSupplier::find($request->input('gift_supplier_id'));
        $accountBook = $giftSupplier->getCurrentAccountBook();
 

        $bank_account = BankAccount::find($request->input('payment_method'));

        $giftPurchase = new GiftPurchase;
        $accountBook->giftPurchases()->save($giftPurchase);

        $count =0;
        $total_amount =0;
        $gift_name =[];
        foreach($request->input('gift_purchases') as $row) {
            if(empty($row['unit_price'])) {
                $row['unit_price'] = 0;
            }
            $giftTransaction = new GiftTransaction();
            $giftTransaction->fill($row);
            $giftTransaction->type = 'purchase';
            $total_price = $row['count'] * $row['unit_price'];
            $count += $row['count'];
            $total_amount += $total_price;
            $gift  =Gift::find($row['gift_id']);
            $gift_name[] = $gift->name;
            $giftPurchase->giftTransactions()->save($giftTransaction);  
        }

        if ($request->filled('payment_amount') && $request->input('payment_amount') > 0) {
            if ($request->input('payment_method') == 'cheque') {
                Cheque::issue($request->input('cheque_no'), $accountBook, $request->input('payment_amount'), $request->input('cheque_date'), $giftPurchase);
            } else {
                $description = $request->has('cheque_no') ? 'চেক নং ' . $request->input('cheque_no') : null;
                $transaction= Transaction::createTransaction('gift-supplier', $giftSupplier->id, 'expense', $request->input('payment_method'), $request->input('payment_amount'), $description, $giftPurchase);
            }
        }
        $entry                   =new GiftSupplierAccountEntry;
        $entry->entry_id         =$giftSupplier->id;
        $entry->entry_type       =0;
        $entry->account_book_id  =$accountBook->id;
        $entry->gift_purchase_id =$giftPurchase->id;
        $entry->gift_name        = json_encode($gift_name);
        $entry->count            =$count;
        $entry->total_amount     =$total_amount;
        $entry->account_id       =$request->input('payment_method');
        $entry->account_name     =$bank_account->bank;
        $entry->save();

        if ($request->has('payment_amount') && $request->payment_amount !== null) {
            $entry = new GiftSupplierAccountEntry;
            $entry->entry_id = $giftSupplier->id;
            $entry->entry_type = 2;
            $entry->account_book_id = $accountBook->id;
            $entry->gift_purchase_id = $giftPurchase->id;
            $entry->total_amount = $request->payment_amount;
            $entry->account_id = $request->input('payment_method');
            $entry->account_name = $bank_account->bank;
            $entry->save();
        }
        $giftPurchase->load('accountBook.account', 'giftTransactions.gift');
        return $giftPurchase;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(GiftPurchase $giftPurchase)
    {
        $giftPurchase->load('accountBook.giftSupplierAccount', 'giftTransactions.gift');
        return $giftPurchase;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(GiftPurchaseRequest $request, GiftPurchase $giftPurchase)
    {
        $request->validated();
        
        $giftPurchase->load('accountBook');
        $survived = [];

        if($giftPurchase->accountBook->open && $giftPurchase->accountBook->account_id != $request->input('gift_supplier_id')) {
            $giftSupplier = GiftSupplier::find($request->input('gift_supplier_id'));
            $accountBook = $giftSupplier->getCurrentAccountBook();
            $giftPurchase->accountBook()->associate($accountBook);
            $giftPurchase->save();
        }

        foreach($request->input('gift_purchases') as $row) {
            if(isset($row['id'])) {
                $giftTransaction = GiftTransaction::find($row['id']);
                $giftTransaction->fill($row);
                $giftTransaction->save();
            } else {
                $giftTransaction = new GiftTransaction();
                $giftTransaction->fill($row);
                $giftTransaction->type = 'purchase';
                $giftPurchase->giftTransactions()->save($giftTransaction);
            }
            $survived[] = $giftTransaction->id;
        }

        $giftTransactions = $giftPurchase->giftTransactions()->get();
        foreach($giftTransactions as $giftTransaction) {
            if(!in_array($giftTransaction->id, $survived)) {
                $giftTransaction->delete();
            }
        }

        $giftTransactions = $giftPurchase->giftTransactions()->get();
        $count =0;
        $unit_price =0;
        foreach($giftTransactions as $item){
            $unit_price += $item->unit_price ;
            $count += $item->count;

        }
        $entry                 = GiftSupplierAccountEntry::where('gift_purchase_id', $giftPurchase->id)->first();
        $entry->count          = $count;
        $entry->total_amount   = $unit_price;
        $entry->save();

        $giftPurchase->load('accountBook.account', 'giftTransactions.gift');
        return $giftPurchase;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(GiftPurchase $giftPurchase)
    {
        //
    }
}
