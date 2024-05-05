<?php

namespace App\Http\Controllers\Web;

use App\Models\Invoice;
use App\Models\Shoe;
use App\Models\RetailStore;
use App\Models\RetailStoreExpense;
use App\Models\ReturnFromRetailEntry;
use App\Models\BankAccount;
use App\Models\Gift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GiftTransaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class InvoiceController extends \App\Http\Controllers\Main\InvoiceController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage invoices']);
    // }

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
        $memoNo       = Invoice::getNextId();
        $gifts        = Gift::all();
        $bankAccount  = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {

            $bankAccounts->push((object) [
                'id'   => $item->id,
                'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
            ]);
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        $transactions      = null;
        $giftTransactions = null;
        return view('invoice.form', compact('memoNo', 'bankAccounts', 'gifts','transactions','giftTransactions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

  
        if($request->retail_store_id == null){
            return redirect()->route('invoice.create')->with('info-alert', 'পার্টি  সঠিক নয় ');
        }

        if($request->has('submit') && $request->input('submit') === 'preview') {
            $retailStore = RetailStore::find($request->input('retail_store_id'));
            $sales = $request->input('sales');
            $total_amount = 0;
            foreach($sales as $i => $sale) {
            
                $shoe = Shoe::find($sale['shoe_id']);
                if ($shoe) {
                    $sales[$i]['shoe'] = $shoe;
                    $total_amount += $sales[$i]['count'] * $shoe['retail_price'];
                } else {
                    $total_amount += $sales[$i]['count'] * 0;
                }
            }
            $preview = true;
            $invoice_id           = Invoice::getNextId();
            $commission           = $request->input('commission');
            $total_commission     = $total_amount * $commission / 100;
            $commission_deducted  = $total_amount - $total_commission;
            $return_count         = $retailStore->return_count;
            $return_amount        = $retailStore->return_amount;
            $return_deducted      = $commission_deducted - $return_amount;
            $transport            = $request->input('transport');
            $transport_added      = $return_deducted + $transport;
            $other_costs          = $retailStore->unlistedExpenses()->sum('amount');
            $other_costs_deducted = $transport_added - $other_costs;
            $discount             = $request->input('discount');
            $total_receivable     = $other_costs_deducted - $discount;
            $payments             = $request->input('payments');
            $payment_amount       = 0;
            foreach($payments as $payment) {
                $payment_amount  += $payment['amount'];
            }
            $previous_due         = $retailStore->getCurrentAccountBook()->balance + $other_costs;
            $total_due            = $previous_due + $total_receivable - $payment_amount;

            $sales = collect($sales);
            $sales = $sales->sortBy('shoe.category.parent.id')
                ->groupBy('shoe.category.parent.name')
                ->transform(function($item, $key) {
                    return $item->groupBy(function($item, $key) {
                            return isset($item['shoe']['retail_price']) ? '' . $item['shoe']['retail_price'] : '0';
                        })->sortByDesc(function($item, $key) {
                            return doubleval($key);
                        })->transform(function($item, $key) {
                            return $item->sortBy('shoe.category.id')
                                ->groupBy('shoe.category.name')
                                ->transform(function($item, $key) {
                                    return $item->sum('count');
                                });
                        });
                });

               

                $payments = $request->input('payments');
                $validPayments = [];
                
                foreach ($payments as $item) {
                    if (empty($item['payment_method']) || empty($item['cheque_no']) || empty($item['amount'])) {
                        continue;
                    }
                    $bankAccount = BankAccount::find($item['payment_method']);
                    if (!$bankAccount) {
                        continue;
                    }
                    $payment = [
                        'payment_method' => $bankAccount['name'],
                        'cheque_no' => $item['cheque_no'],
                        'amount' => $item['amount'],
                    ];
                
                    $validPayments[] = $payment;
                }
                
             
                
                
            $gifts_input = $request->input('gifts');
            $gifts = [];

            foreach($gifts_input as $i => $gift_input) {
                if(empty($gift_input['gift_id']) || empty($gift_input['count']))
                    continue;
                 $gift =Gift::find($gift_input['gift_id']);
                 if (!$bankAccount) {
                    continue;
                  }

                $gift['gift'] = Gift::find($gift_input['gift_id']);

                $gift['count'] = $gift_input['count'];


                $gifts[] = $gift; 
            }

            return view('invoice.show', compact('retailStore', 'sales', 'preview', 'invoice_id', 'total_amount', 'commission', 'total_commission', 'commission_deducted', 'return_count', 'return_amount', 'return_deducted', 'transport', 'transport_added', 'other_costs', 'other_costs_deducted', 'discount', 'total_receivable', 'payment_amount', 'previous_due', 'total_due', 'gifts','validPayments'));
        } else {
            if($request->retail_store_id == null){
                return redirect()->route('invoice.create')->with('info-alert', 'পার্টি  সঠিক নয় ');
            }
            $invoice = parent::store($request);
            return redirect()->route('invoice.show', ['invoice' => $invoice]);
        }
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

     
        $invoice = parent::show($request, $invoice);
        if($request->input('view') == 'id') {
            return view('invoice.id-view', compact('invoice'));
        }
        $transactions =Transaction::with('fromAccount.BankAccount','toAccount.BankAccount')->where('attachment_id', $invoice->id)->where('attachment_type','App\Models\Invoice')->get();

        $giftTransactions  =GiftTransaction::where('attachment_id',$invoice->id)->where('type','sale')->where('attachment_type','App\Models\Invoice')->get();

        return view('invoice.show', compact('invoice','transactions','giftTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //$invoice->load('accountBook.account', 'sales.shoe');
        $invoice->load('accountBook.BankAccount', 'invoiceEntries.shoe');
        $bankAccounts     = BankAccount::all();
        $gifts            = Gift::all();
        $giftTransactions = GiftTransaction::where('attachment_id',$invoice->id)->where('type','sale')->where('attachment_type','App\Models\Invoice')->get();
        $transactions     = Transaction::with('fromAccount.BankAccount','toAccount.BankAccount')->where('attachment_id' ,$invoice->id)->where('attachment_type','App\Models\Invoice')->get();
        return view('invoice.form', compact('invoice', 'bankAccounts', 'gifts','giftTransactions','transactions'));
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

     


        
        $invoice = parent::update($request, $invoice);
        return redirect()->route('invoice.show', ['invoice' => $invoice]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($invoice)
    {
        //
        $delete =parent::destroy($invoice);
        return redirect()->route('invoice.create')->with('success-alert', 'জুতা বিক্রয়   ডিলিট করা হয়েছে ।');

        
    }

    public function tr(Request $request) {
        preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        return view('invoice.tr', compact('index'));
    }

    public function giftTr(Request $request) {
        preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        $gifts = Gift::all();
        return view('invoice.gift-tr', compact('index', 'gifts'));
    }

    public function paymentTr(Request $request) {
        preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            $bankAccounts->push((object) [
                'id' => $item->id,
                'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
            ]);
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        return view('invoice.payment-tr', compact('index', 'bankAccounts'));
    }
}
