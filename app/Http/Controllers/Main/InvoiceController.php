<?php

namespace App\Http\Controllers\Main;

use App\Models\RetailStore;
use App\Models\Shoe;
use App\Models\Invoice;
use App\Models\InvoiceEntry;
use App\Models\Transaction;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;

class InvoiceController extends Controller
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

        $retailStore = RetailStore::find($request->input('retail_store_id'));
        $accountBook = $retailStore->getCurrentAccountBook();

        $invoice = new Invoice;
        $fill = $request->all();
        if(empty($fill['transport'])) unset($fill['transport']);
        if(empty($fill['discount'])) unset($fill['discount']);
        $invoice->fill($fill);
        $accountBook->invoices()->save($invoice);

        foreach($request->input('sales') as $row) {
            if(empty($row['shoe_id'])) {
                continue;
            }
            $invoiceEntry = new InvoiceEntry;
            $invoiceEntry->fill($row);
            $invoice->invoiceEntries()->save($invoiceEntry);

            $shoe = Shoe::find($row['shoe_id']);

            $inventory =Inventory::find($shoe->id);
            $inventory->decrement('count', $row['count']);

            $boxSale = new GiftTransaction;
            $boxSale->fill([
                'gift_id' => $shoe->box_id,
                'count' => $row['count'],
            ]);
            $boxSale->type = 'sale';

            $bagSale = new GiftTransaction;
            $bagSale->fill([
                'gift_id' => $shoe->bag_id,
                'count' => $row['count'],
            ]);
            $bagSale->type = 'sale';

            $invoiceEntry->giftTransactions()->save($boxSale);
            $invoiceEntry->giftTransactions()->save($bagSale);
        }

        $returns = $retailStore->unlistedReturns()->get();
        foreach($returns as $return) {
            $invoice->returns()->save($return);
        }

        $expenses = $retailStore->unlistedExpenses()->get();
        foreach($expenses as $expense) {
            $invoice->retailStoreExpenses()->save($expense);
        }
        
        /*if($request->filled('payment_amount') && $request->input('payment_amount') > 0) {
            $description = $request->has('cheque_no') ? 'চেক নং ' . $request->input('cheque_no') : null;
            Transaction::createTransaction('retail-store', $retailStore->id, 'income', $request->input('payment_method'), $request->input('payment_amount'), $description, $invoice);
        }*/
        $payments = $request->input('payments');
        foreach($payments as $payment) {
            if(empty($payment['amount']))
                contiune;

            $description = isset($payment['cheque_no']) ? 'চেক নং ' . $payment['cheque_no'] : null;
            Transaction::createTransaction('retail-store', $retailStore->id, 'income', $payment['payment_method'], $payment['amount'], $description, $invoice);
        }

        foreach($request->input('gifts') as $row) {
            if(empty($row['gift_id'])) {
                continue;
            }
            $giftSale = new GiftTransaction;
            $giftSale->fill($row);
            $giftSale->type = 'sale';
            $invoice->giftTransactions()->save($giftSale);
        }
        
       


        $invoice->load('accountBook.account', 'invoiceItems', 'transactions');
        return $invoice;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Invoice $invoice)
    {
        if($request->input('view') == 'id') {
            $invoice->load('accountBook.retailAccount', 'invoiceEntries', 'giftTransactions');
        } else {
            $invoice->load('accountBook.retailAccount', 'invoiceItems', 'giftTransactions');
        }
        return $invoice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {

 
        $invoice->load('accountBook.account');
        $survived = [];

        if($invoice->accountBook->open && $invoice->accountBook->account_id != $request->input('retail_store_id')) {
            $retailStore = RetailStore::find($request->input('retail_store_id'));
            $accountBook = $retailStore->getCurrentAccountBook();
            $accountBook->invoices()->save($invoice);
        }

        $invoice->fill($request->all());
        $invoice->save();

        foreach($request->input('sales') as $i => $row) {
            if(isset($row['id'])) {
                /*$shoeTransaction = ShoeTransaction::find($row['id']);
                $shoeTransaction->fill($row);
                $shoeTransaction->commission = $request->input('commission');
                $shoeTransaction->save();*/
                $invoiceEntry = InvoiceEntry::find($row['id']);

                $difference   =$row['count'] -$invoiceEntry->count;
                $inventory =Inventory::find($invoiceEntry->shoe_id);
                $inventory->count = max(0, $inventory->count + $difference);
                $inventory->save();

                $invoiceEntry->fill($row);
                $invoiceEntry->save();
            } else if(empty($row['shoe_id'])) {
                continue;
            } else {
                /*$shoeTransaction = new ShoeTransaction;
                $shoeTransaction->fill($row);
                $shoeTransaction->type = ShoeTransaction::SALE;
                $shoeTransaction->commission = $request->input('commission');
                $invoice->shoeTransactions()->save($shoeTransaction);*/
                $invoiceEntry = new InvoiceEntry;
                $invoiceEntry->fill($row);
                $invoice->invoiceEntries()->save($invoiceEntry);
            }
            //$survived[] = $shoeTransaction->id;
            $survived[] = $invoiceEntry->id;
        }

        $invoiceEntries = $invoice->invoiceEntries()->get();
        foreach($invoiceEntries as $invoiceEntry) {
            if(!in_array($invoiceEntry->id, $survived)) {
                $invoiceEntry->delete();
            }
        }

        $invoice->load('invoiceEntries.shoe');
        return $invoice;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
