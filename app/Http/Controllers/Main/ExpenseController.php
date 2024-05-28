<?php

namespace App\Http\Controllers\Main;

use App\Models\Expense;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Expense::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expense = new Expense;
        $expense->fill($request->all());
        $expense->save();

        $account       = new Account;
        $account->id   = $expense->id;
        $account->type = 'expense';
        $account->name = $expense->name;
        $account->save();

        $accountBook               = new AccountBook;
        $accountBook->account_id   = $expense->id;
        $accountBook->account_type = 'expense';
        $accountBook->save();

        // $expense->accountBooks()->save(new AccountBook());

        return $expense;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense->append('current_book');
        $expense->load('entries');
        return $expense;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $expense->fill($request->all());
        $expense->save();

        return $expense;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return collect(['success' => 'খরচের খাত মুছে ফেলা হয়েছে।']);
    }

    public function forceDelete($id)
    {
        $giftSupplier = Expense::onlyTrashed()->find($id);
        $giftSupplier->forceDelete();
        return collect(['success' => 'খরচের খাত স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $giftSupplier = Expense::onlyTrashed()->find($id);
        $giftSupplier->restore();
        return collect(['success' => 'খরচের খাত পুনরুদ্ধার করা হয়েছে।']);
    }
}
