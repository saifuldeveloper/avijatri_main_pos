<?php

namespace App\Http\Controllers\Web;

use App\Models\Factory;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseEntry;
use App\Models\ReturnToFactory;

class FactoryController extends \App\Http\Controllers\Main\FactoryController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage factories'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $factories = parent::index();



        return view('factory.index', compact('factories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $factory = null;
        return view('factory.form', compact('factory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factory = parent::store($request);
        return back()->with('success-alert', 'নতুন কারখানাদারের তথ্য সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factory = parent::show($id);
        $purchases = Purchase::with('purchaseEntries.shoe')->where('account_book_id', $factory->id)->get();
        $qty = 0;
        $purchases_amount = 0;
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
        
        $returns = ReturnToFactory::where('account_id', $factory->id)
            ->with('returnentries.shoe')
            ->get();
        $returns_amount = $returns->flatMap(function ($item) {
            return $item->returnentries->map(function ($entry) {
                return $entry->shoe->purchase_price * $entry->count / 12;
            });

        })->sum();

        $balance = $purchases_amount - $payment_amount - $returns_amount;


        return view('factory.show', compact('factory', 'balance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function edit(Factory $factory)
    {
        preventHttp();
        return view('factory.form', compact('factory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factory $factory)
    {
        $factory = parent::update($request, $factory);
        return back()->with('success-alert', 'কারখানাদারের তথ্য এডিট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factory $factory)
    {
        $message = parent::destroy($factory);
        return redirect()->route('factory.index')->with('success-alert', $message['success']);
    }

    public function closingPage(Factory $factory)
    {
        $factory->append('current_book');
        $factory->current_book->appendClosingAttributes();
        $bankAccounts = BankAccount::all();
        return view('factory.closing', compact('factory', 'bankAccounts'));
    }

    public function closingPaymentTr(Request $request)
    {
        preventHttp();

        $bankAccounts = BankAccount::all();
        if ($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        return view('factory.closing-payment-tr', compact('bankAccounts', 'index'));
    }

    public function closingChequeTr(Request $request)
    {
        preventHttp();

        if ($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        return view('factory.closing-cheque-tr', compact('index'));
    }

    public function closing(Request $request, Factory $factory)
    {
        parent::closing($request, $factory);
        return redirect()->route('factory.show', ['factory' => $factory])->with('success-alert', 'ক্লোজিং সম্পন্ন হয়েছে।');
    }

    public function datalist()
    {
        preventHttp();
        $model = 'factory';
        $list = Factory::all();
        return view('layouts.datalist', compact('model', 'list'));
    }
}
