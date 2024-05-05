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
use App\Models\Gift;
use App\Models\Inventory;
use App\Models\View\RetailStoreAccountEntry;

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

    public function store(Request $request)
    {
        $retailStore = RetailStore::find($request->input('retail_store_id'));
        $accountBook = $retailStore->getCurrentAccountBook();
        $invoice = new Invoice;
        $fill = $request->all();
        if (empty($fill['transport'])) unset($fill['transport']);
        if (empty($fill['discount'])) unset($fill['discount']);
        $invoice->fill($fill);
        $accountBook->invoices()->save($invoice);

        $count = 0;
        $total_retail_price = 0;
        foreach ($request->input('sales') as $row) {
            if (empty($row['shoe_id'])) {
                continue;
            }
            $invoiceEntry = new InvoiceEntry;
            $invoiceEntry->fill($row);
            $invoice->invoiceEntries()->save($invoiceEntry);
            $shoe               = Shoe::find($row['shoe_id']);
            $count              += $row['count'];
            $total_retail_price += $shoe->retail_price * $row['count'];
            $inventory           = Inventory::find($shoe->id);
            $inventory->decrement('count', $row['count']);

            // $boxSale            = new GiftTransaction;
            // $boxSale->fill([
            //     'gift_id' => $shoe->box_id,
            //     'count' => $row['count'],
            // ]);
            // $boxSale->type = 'sale';

            // $bagSale     =     new GiftTransaction;
            // $bagSale->fill([
            //     'gift_id' => $shoe->bag_id,
            //     'count' => $row['count'],
            // ]);
            // $bagSale->type = 'sale';

            // $invoiceEntry->giftTransactions()->save($boxSale);
            // $invoiceEntry->giftTransactions()->save($bagSale);
        }

        $returns = $retailStore->unlistedReturns()->get();

        foreach ($returns as $return) {
            $invoice->returns()->save($return);
        }

        $expenses = $retailStore->unlistedExpenses()->get();
        foreach ($expenses as $expense) {
            $invoice->retailStoreExpenses()->save($expense);
        }
        foreach ($request->input('gifts') as $row) {
            if (empty($row['gift_id'])) {
                continue;
            }
            $giftSale = new GiftTransaction;
            $giftSale->fill($row);
            $giftSale->type = 'sale';
            $invoice->giftTransactions()->save($giftSale);
        }

        $payments     = $request->input('payments');
        $padid_amount = 0;
        foreach ($payments as $payment) {
            if (empty($payment['amount']))
                continue;
            $description = isset($payment['cheque_no']) ? 'চেক নং ' . $payment['cheque_no'] : null;
            Transaction::createTransaction('retail-store', $retailStore->id, 'income', $payment['payment_method'], $payment['amount'], $description, $invoice);
            $padid_amount += $payment['amount'];
        }

        $entry                      = new RetailStoreAccountEntry;
        $entry->entry_id            = $invoice->id;
        $entry->entry_type          = '0';
        $entry->account_book_id     = $accountBook->id;
        $entry->invoice_id          = $invoice->id;
        $entry->count               = $count;
        $entry->total_retail_price  = $total_retail_price;

        $entry->return_count      = $returns->sum('count');
        $entry->return_amount      =  $returns->sum(function ($return) {
            return $return->shoe->retails_price;
        });
        $entry->expense_amount      = $expenses->sum('amount');
        $entry->expense_description = $expenses->pluck('description')->implode(', ');
        $entry->total_commission    = $request->commission;
        $entry->transport           = $request->transport;
        $entry->discount            = $request->discount;
        $entry->paid_amount         = $padid_amount;
        $entry->amount              = $request->total_amount;
        $entry->save();
        $invoice->load('accountBook.account', 'invoiceItems', 'transactions');
        return $invoice;
    }


    public function show(Request $request, Invoice $invoice)
    {
        if ($request->input('view') == 'id') {
            $invoice->load('accountBook.retailAccount', 'invoiceEntries', 'giftTransactions', 'transactions');
        } else {
            $invoice->load('accountBook.retailAccount', 'invoiceItems', 'giftTransactions', 'transactions');
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

        // dd($request->all());
        $invoice->load('accountBook.account');
        $survived = [];
        $retailStore = RetailStore::find($request->input('retail_store_id'));
        if ($invoice->accountBook->open && $invoice->accountBook->account_id != $request->input('retail_store_id')) {
            $retailStore = RetailStore::find($request->input('retail_store_id'));
            $accountBook = $retailStore->getCurrentAccountBook();
            $accountBook->invoices()->save($invoice);
        }

        $invoice->fill($request->all());
        $invoice->save();


        $count = 0;
        $total_retail_price = 0;
        foreach ($request->input('sales') as $i => $row) {
            if (isset($row['id'])) {
                /*$shoeTransaction = ShoeTransaction::find($row['id']);
                $shoeTransaction->fill($row);
                $shoeTransaction->commission = $request->input('commission');
                $shoeTransaction->save();*/
                $invoiceEntry        = InvoiceEntry::find($row['id']);

                $difference          = $row['count'] - $invoiceEntry->count;
                $inventory           = Inventory::find($invoiceEntry->shoe_id);
                $inventory->count    = max(0, $inventory->count - $difference);
                $count              += $row['count'];
                $total_retail_price += $invoiceEntry->shoe->retail_price * $row['count'];
                $inventory->save();
                $invoiceEntry->fill($row);
                $invoiceEntry->save();
            } else if (empty($row['shoe_id'])) {
                continue;
            } else {
                /*$shoeTransaction = new ShoeTransaction;
                $shoeTransaction->fill($row);
                $shoeTransaction->type = ShoeTransaction::SALE;
                $shoeTransaction->commission = $request->input('commission');
                $invoice->shoeTransactions()->save($shoeTransaction);*/
                $invoiceEntry         = new InvoiceEntry;
                $invoiceEntry->fill($row);
                $count                += $row['count'];
                $total_retail_price   += $invoiceEntry->shoe->retail_price * $row['count'];
                $invoice->invoiceEntries()->save($invoiceEntry);
            }

            //$survived[] = $shoeTransaction->id;
            $survived[] = $invoiceEntry->id;
        }
        $invoiceEntries = $invoice->invoiceEntries()->get();
        foreach ($invoiceEntries as $invoiceEntry) {
            if (!in_array($invoiceEntry->id, $survived)) {
                $invoiceEntry->delete();
            }
        }

        $removedPayment    = [];
        $payments     = $request->input('payments');
        foreach ($payments as $payment) {
            if (isset($payment['id'])) {
                $transaction = Transaction::find($payment['id']);
                $difference             = $payment['amount'] - $transaction->amount;
                $transaction->amount = max(0, $transaction->amount + $difference);
                $transaction->description =  $payment['cheque_no'] ?? null;
                $transaction->save();
            } else {

                if (empty($payment['amount']))
                    continue;
                $description = isset($payment['cheque_no']) ? 'চেক নং ' . $payment['cheque_no'] : null;
                $transaction = Transaction::createTransaction('retail-store', $retailStore->id, 'income', $payment['payment_method'], $payment['amount'], $description, $invoice);
            }
            $removedPayment[] = $transaction->id;
        }
        $transaction = Transaction::where('attachment_id',$invoice->id)->where('attachment_type','App\Models\Invoice')->get();
        foreach ($transaction as $item) {
            if (!in_array($item->id, $removedPayment)) {
                $item->delete();
            }
        }

        $giftRemoved = [];
        $gifts_input = $request->input('gifts');
        if (isset($gifts_input)) {
            foreach ($gifts_input as $i => $gift) {
                // if (empty($gift_input['gift_id']) || empty($gift_input['count']))
                //     continue;
                if (isset($gift['id'])) {
                    $giftTransaction       =  GiftTransaction::find($gift['id']);
                    $difference             = $gift['count'] - $giftTransaction->count;
                    $giftTransaction->count = max(0, $giftTransaction->count + $difference);
                    $giftTransaction->save();
                } else {
                    if (empty($gift['gift_id'])) {
                        continue;
                    }
                    $giftTransaction = new GiftTransaction;
                    $giftTransaction->fill($gift);
                    $giftTransaction->type = 'sale';
                    $giftTransaction->attachment_id = $invoice->id;
                    $invoice->giftTransactions()->save($giftTransaction);
                    $giftTransaction->save();
                }
                $giftRemoved[] = $giftTransaction->id;
            }
        }
        $transaction = GiftTransaction::where('attachment_id',$invoice->id)->where('type','sale')->where('attachment_type','App\Models\Invoice')->get();
        foreach ($transaction as $item) {
            if (!in_array($item->id, $giftRemoved)) {
                $item->delete();
            }
        }





        $transactions = Transaction::where('attachment_id', $invoice->id)->where('attachment_type', 'App\Models\Invoice')->get();
        $entry                      = RetailStoreAccountEntry::where('invoice_id', $invoice->id)->first();
        $entry->count               = $count;
        $entry->total_retail_price  = $total_retail_price;
        $entry->total_commission    = $request->commission;
        $entry->transport           = $request->transport;
        $entry->discount            = $request->discount;
        $entry->amount              = $request->total_amount;
        $entry->paid_amount         = $transactions->sum('amount');
        $entry->save();
        $invoice->load('invoiceEntries.shoe');
        return $invoice;
    }


    public function destroy($invoice)
    {
        $invoice         = Invoice::with('accountBook.retailAccount')->find($invoice);
        $invoice_entries = InvoiceEntry::where('invoice_id', $invoice->id)->get();
        foreach ($invoice_entries as $item) {
            $inventory   = Inventory::find($item->shoe_id);
            $inventory->increment('count', $item->count);
            $item->delete();
        }
        $gift_transaction = GiftTransaction::where('type', 'sale')
            ->where('attachment_id', $invoice->id)
            ->delete();

        $transaction = Transaction::where('attachment_id', $invoice->id)
            ->where('attachment_type', 'App\Models\Invoice')
            ->delete();
        $retail_store_account_entry = RetailStoreAccountEntry::where('invoice_id', $invoice->id)->delete();
    }
}
