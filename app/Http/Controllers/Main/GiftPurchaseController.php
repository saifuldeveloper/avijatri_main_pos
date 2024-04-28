<?php

namespace App\Http\Controllers\Main;

use App\Models\GiftPurchase;
use App\Models\GiftSupplier;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GiftPurchaseRequest;
use App\Models\GiftSupplierAccountEntry;

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


        $request->validated();
        
        $giftSupplier = GiftSupplier::find($request->input('gift_supplier_id'));
        $accountBook = $giftSupplier->getCurrentAccountBook();
 

        $giftPurchase = new GiftPurchase;
        $accountBook->giftPurchases()->save($giftPurchase);

        $count =0;
        $total_amount =0;
        foreach($request->input('gift_purchases') as $row) {
            if(empty($row['unit_price'])) {
                $row['unit_price'] = 0;
            }
            $giftTransaction = new GiftTransaction();
            $giftTransaction->fill($row);
            $giftTransaction->type = 'purchase';
            $count +=$row['count'];
            $total_amount +=$row['unit_price'];
            $giftPurchase->giftTransactions()->save($giftTransaction);  
        }

        $entry                   =new GiftSupplierAccountEntry;
        $entry->entry_id         =0;
        $entry->entry_type       =0;
        $entry->account_book_id  =$accountBook->id;
        $entry->gift_purchase_id =$giftPurchase->id;
        $entry->gift_name        ='s';
        $entry->count            =$count;
        $entry->total_amount     =$total_amount;
        $entry->account_id       =$giftSupplier->id;
        $entry->account_name     =$giftSupplier->name;
        $entry->save();

        if($request->payment_amount  !=='null'){
            $entry                   =new GiftSupplierAccountEntry;
            $entry->entry_id         =0;
            $entry->entry_type       =2;
            $entry->account_book_id  =$accountBook->id;
            $entry->gift_purchase_id =$giftPurchase->id;
            $entry->total_amount     =$request->payment_amount;
            $entry->account_id       =$giftSupplier->id;
            $entry->account_name     =$giftSupplier->name;
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
