<?php

namespace App\Http\Controllers\Main;

use App\Models\RetailStore;
use App\Models\AccountBook;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class RetailStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regular = RetailStore::where('onetime_buyer', false)->get();
        $onetime = RetailStore::where('onetime_buyer', true)->get();
        $trashRetailStores = RetailStore::onlyTrashed()->get();
        return compact('regular', 'onetime', 'trashRetailStores');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $retailStore = new RetailStore;
        $retailStore->fill($request->all());
        $retailStore->save();

        $account = new Account;
        $account->id = $retailStore->id;
        $account->type = 'retail-store';
        $account->name = $retailStore->shop_name;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $retailStore->id;
        $accountBook->account_type = "retail-store";
        $accountBook->save();



        // $retailStore->accountBooks()->save(new AccountBook());

        return $retailStore;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function show(RetailStore $retailStore)
    {
        $retailStore->load('accountBooks');
        return $retailStore;
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
        $retailStore->fill($request->all());
        $retailStore->save();

        return $retailStore;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RetailStore  $retailStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetailStore $retailStore)
    {
        $retailStore->delete();
        return collect(['success' => 'পার্টির তথ্য মুছে ফেলা হয়েছে।']);
    }

    public function forceDelete($id)
    {
        $color = RetailStore::withTrashed()->find($id);
        $color->forceDelete();
        return collect(['success' => 'পার্টির তথ্য স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $color = RetailStore::withTrashed()->find($id);
        $color->restore();
        return collect(['success' => 'পার্টির তথ্য পুনরুদ্ধার করা হয়েছে।']);
    }

    public function closing(Request $request, RetailStore $retailStore)
    {
        $accountBook = $retailStore->getCurrentAccountBook();
        $balance = $accountBook->balance;

        $accountBook->fill($request->all());

        $payments = $request->input('payment');
        $payment_sum = 0;
        foreach ($payments as $payment) {
            if (empty($payment['amount'])) {
                continue;
            }
            $transaction = Transaction::createTransaction('retail-store', $retailStore->id, 'income', $payment['method'], $payment['amount'], '', null, $accountBook->id);
            $payment_sum += $payment['amount'];
        }

        $accountBook->closing_balance = $balance - $accountBook->commission - $accountBook->staff - $payment_sum;
        $accountBook->open = false;
        $accountBook->save();
        $retailStore->accountBooks()->save(new AccountBook());

        $retailStore->append('current_book');
        return $retailStore;
    }
}
