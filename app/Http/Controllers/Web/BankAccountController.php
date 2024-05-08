<?php

namespace App\Http\Controllers\Web;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankAccountController extends \App\Http\Controllers\Main\BankAccountController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage bank accounts']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bankAccounts = parent::index();
        return view('bank-account.index', compact('bankAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $bankAccount = null;
        return view('bank-account.form', compact('bankAccount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bankAccount = parent::store($request);
        return back()->with('success-alert', 'নতুন ব্যাংক অ্যাকাউন্টের তথ্য সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {

        $bankAccount = parent::show($bankAccount);
        $entries = $bankAccount->entries()->orderBy('id','desc')->paginate(10);
        return view('bank-account.show', compact('bankAccount','entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        preventHttp();
        return view('bank-account.form', compact('bankAccount'));
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
        $bankAccount = parent::update($request, $bankAccount);
        return back()->with('success-alert', 'ব্যাংক অ্যাকাউন্টের তথ্য এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        $message = parent::destroy($bankAccount);
        return back()->with('success-alert', $message['success']);
    }
}
