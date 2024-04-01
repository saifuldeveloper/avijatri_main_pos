<?php

namespace App\Http\Controllers\Web;

use App\Models\RetailStore;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RetailStoreController extends \App\Http\Controllers\Main\RetailStoreController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage retail stores'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $retailStores = parent::index();
        return view('retail-store.index', $retailStores);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        preventHttp();
        $retailStore = null;
        if($request->has('onetime') && $request->input('onetime') == 1) {
            $one_time = true;
        } else {
            $one_time = false;
        }
        return view('retail-store.form', compact('retailStore', 'one_time'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $retailStore = parent::store($request);
        if($retailStore->onetime_buyer) {
            return back()->with(['success-alert' => 'খুচরা বিক্রি শুরু করুন।', 'retail-store' => $retailStore]);
        }
        return back()->with('success-alert', 'নতুন পার্টির তথ্য সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function show(RetailStore $retailStore)
    {
        $retailStore = parent::show($retailStore);
        $parameter = 'retail_store';
        return view('retail-store.show', compact('retailStore','parameter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function edit(RetailStore $retailStore)
    {
        preventHttp();
        return view('retail-store.form', compact('retailStore'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetailStore $retailStore)
    {
        $retailStore = parent::update($request, $retailStore);
        return back()->with('success-alert', 'পার্টির তথ্য এডিট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetailStore $retailStore)
    {
        $message = parent::destroy($retailStore);
        return redirect()->route('retail-store.index')->with('success-alert', $message['success']);
    }

    public function closingPage(RetailStore $retailStore) {
        $retailStore->append('current_book');
        $retailStore->current_book->appendClosingAttributes();
        $bankAccounts = BankAccount::all();
        return view('retail-store.closing', compact('retailStore', 'bankAccounts'));
    }

    public function closing(Request $request, RetailStore $retailStore) {
        parent::closing($request, $retailStore);
        return redirect()->route('retail-store.show', ['retailStore' => $retailStore])->with('success-alert', 'ক্লোজিং সম্পন্ন হয়েছে।');
    }

    public function closingTr(Request $request) {
        preventHttp();

        $bankAccounts = BankAccount::all();
        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        return view('retail-store.closing-tr', compact('bankAccounts', 'index'));
    }

    public function datalist(Request $request) {
        preventHttp();
        
        $model = 'retail-store';
        $list = RetailStore::where('onetime_buyer', false)->get();
        $extend = false;

        if($request->has('extend')) {
            $extend = true;
            foreach($list as $retailStore) {
                $retailStore->append(['return_count', 'return_amount', 'other_costs', 'unlisted_return_url']);
            }
        }
        return view('layouts.datalist', compact('model', 'list', 'extend'));
    }

    public function closingDatalist(Request $request) {
        preventHttp();
        
        $model = 'retail-closing';
        $list = RetailStore::where('onetime_buyer', false)->get();

        foreach($list as $retailStore) {
            $retailStore->append(['previous_book']);
        }
        return view('layouts.datalist', compact('model', 'list'));
    }
}
