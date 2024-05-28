<?php

namespace App\Http\Controllers\Main;

use App\Models\BankAccount;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BankAccount::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bankAccount = new BankAccount;
        $bankAccount->fill($request->all());
        $bankAccount->save();

        $account = new Account;
        $account->id = $bankAccount->id;
        $account->type = 'bank-account';
        $account->name = $bankAccount->bank;
        $account->save();

        $accountBook = new AccountBook;
        $accountBook->account_id = $bankAccount->id;
        $accountBook->account_type = 'bank-account';
        $accountBook->save();

        // $bankAccount->accountBooks()->save(new AccountBook());

        return $bankAccount;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        // $bankAccount->append('current_book');
        $bankAccount->getCurrentAccountBook()->append('entries');
        return $bankAccount;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $bankAccount->fill($request->all());
        $bankAccount->save();

        return $bankAccount;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return collect(['success' => 'ব্যাংক অ্যাকাউন্টের তথ্য মুছে ফেলা হয়েছে।']);
    }

    public function forceDelete($id)
    {
        $color = BankAccount::withTrashed()->find($id);
        $color->forceDelete();
        return collect(['success' => 'ব্যাংক অ্যাকাউন্টের তথ্য স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $color = BankAccount::withTrashed()->find($id);
        $color->restore();
        return collect(['success' => 'ব্যাংক অ্যাকাউন্টের তথ্য পুনরুদ্ধার করা হয়েছে।']);
    }
}
