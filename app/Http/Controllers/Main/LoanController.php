<?php

namespace App\Http\Controllers\Main;

use App\Models\Loan;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Loan::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loan = new Loan;
        $loan->fill($request->all());
        $loan->save();
        $account       = new Account;
        $account->id   = $loan->id;
        $account->type = 'loan';
        $account->name = $loan->name;
        $account->save();
        $accountBook               = new AccountBook;
        $accountBook->account_id   = $loan->id;
        $accountBook->account_type = 'loan';
        $accountBook->save();

        return $loan;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        $loan->getCurrentAccountBook();
        $loan->load('entries');
        return $loan;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        $loan->fill($request->all());
        $loan->save();

        return $loan;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return collect(['success' => 'হাওলাত খাতা মুছে ফেলা হয়েছে।']);
    }

    public function forceDelete($id)
    {
        $loan = Loan::onlyTrashed()->find($id);
        $loan->forceDelete();
        return collect(['success' => 'হাওলাত খাতা স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $loan = Loan::onlyTrashed()->find($id);
        $loan->restore();
        return collect(['success' => 'হাওলাত খাতা পুনরুদ্ধার করা হয়েছে।']);
    }
}
