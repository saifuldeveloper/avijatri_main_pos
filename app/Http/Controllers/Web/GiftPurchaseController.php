<?php

namespace App\Http\Controllers\Web;

use App\Models\Gift;
use App\Models\GiftPurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GiftPurchaseRequest;
use App\Models\BankAccount;
use Illuminate\Support\Collection;

class GiftPurchaseController extends \App\Http\Controllers\Main\GiftPurchaseController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage gifts']);
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
        $gifts = Gift::all();
        $memoNo = GiftPurchase::getNextId();
        $bankAccount  = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) [
                    'id'   => $item->id,
                    'name' => 'ক্যাশ' 
                ]);
            } else {
                $bankAccounts->push((object) [
                    'id'   => $item->id,
                    'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
                ]);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        return view('gift-purchase.form', compact('gifts', 'memoNo','bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GiftPurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(GiftPurchaseRequest $request)
    // {
    //     $giftPurchase = parent::store($request);
    //     return redirect()->route('gift-purchase.show', ['gift-purchase' => $giftPurchase])->with('success-alert', 'গিফট ক্রয় সম্পন্ন হয়েছে।');
    // }


    public function store(GiftPurchaseRequest $request)
    {
        $giftPurchase = parent::store($request);
        return redirect()->route('gift-purchase.show', ['gift_purchase' => $giftPurchase->id])->with('success-alert', 'গিফট ক্রয় সম্পন্ন হয়েছে।');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(GiftPurchase $giftPurchase)
    {
        $giftPurchase = parent::show($giftPurchase);
        
        return view('gift-purchase.show', compact('giftPurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(GiftPurchase $giftPurchase)
    {
        $gifts = Gift::all();
        $giftPurchase->load('accountBook.account', 'giftTransactions');
        $bankAccount  = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            if ($item->bank === 'ক্যাশ') {
                $bankAccounts->push((object) [
                    'id'   => $item->id,
                    'name' => 'ক্যাশ' 
                ]);
            } else {
                $bankAccounts->push((object) [
                    'id'   => $item->id,
                    'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
                ]);
            }
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        return view('gift-purchase.form', compact('gifts', 'giftPurchase','bankAccounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GiftPurchaseRequest  $request
     * @param  \App\Models\GiftPurchase  $giftPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(GiftPurchaseRequest $request, GiftPurchase $giftPurchase)
    {
        $giftPurchase = parent::update($request, $giftPurchase);
        return redirect()->route('gift-purchase.show', ['gift-purchase' => $giftPurchase])->with('success-alert', 'মেমো এডিট সম্পন্ন হয়েছে।');
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

    // AJAX actions
    public function tr(Request $request)
    {
        preventHttp();

        if ($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        $gifts = Gift::all();

        return view('gift-purchase.tr', compact('index', 'gifts'));
    }
}
