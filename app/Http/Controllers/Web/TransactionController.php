<?php

namespace App\Http\Controllers\Web;

use App\Models\Transaction;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends \App\Http\Controllers\Main\TransactionController
{
    public function __construct() {
        $this->middleware(['permission:manage transactions']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('date')) {
            $date = \Carbon\CarbonImmutable::parse($request->input('date'));
        } else {
            $date = \Carbon\CarbonImmutable::today();
        }
        $data = parent::index($request);
        $accountTypes = [
            (object)[ 'id' => 'withdraw',       'name' => 'ব্যাংক থেকে তোলা' ],
            (object)[ 'id' => 'deposit',        'name' => 'ব্যাংক জমা' ],
            (object)[ 'id' => 'factory',        'name' => 'মহাজন তাগাদা' ],
            (object)[ 'id' => 'retail-store' ,  'name' => 'পার্টি জমা' ],
            (object)[ 'id' => 'retail-closing', 'name' => 'পার্টি জমা (ক্লোজিং)' ],
            (object)[ 'id' => 'gift-supplier',  'name' => 'গিফট মহাজন তাগাদা' ],
            (object)[ 'id' => 'cheque',         'name' => 'চেক পরিশোধ' ],
            (object)[ 'id' => 'employee',       'name' => 'স্টাফ' ],
            (object)[ 'id' => 'loan-receipt',   'name' => 'হাওলাত আনা' ],
            (object)[ 'id' => 'loan-payment',   'name' => 'হাওলাত ফেরত' ],
            (object)[ 'id' => 'expense',        'name' => 'অন্যান্য খরচ' ],
        ];
        $paymentTypes = [
            (object)[ 'id' => 'income',  'name' => 'জমা' ],
            (object)[ 'id' => 'expense', 'name' => 'খরচ' ],
        ];
        $bankAccounts = BankAccount::all();
        $bankAccounts->push((object)[ 'id' => 'cheque', 'name' => 'চেক' ]);
        return view('transaction.index', compact('date', 'data', 'accountTypes', 'paymentTypes', 'bankAccounts'));
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
        $transaction = parent::store($request);
        return redirect()->back()->with('success-alert', 'টাকার হিসাব সংরক্ষণ করা হয়েছে।');
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
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
