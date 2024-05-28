<?php

namespace App\Http\Controllers\Web;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanController extends \App\Http\Controllers\Main\LoanController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage loans'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = parent::index();
        $trashLoans = Loan::onlyTrashed()->get();
        return view('loan.index', compact('loans', 'trashLoans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $loan = null;
        return view('loan.form', compact('loan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loan = parent::store($request);
        return back()->with('success-alert', 'নতুন হাওলাত খাতা তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan)
    {
        $loan = parent::show($loan);
        $entries = $loan->entries()->paginate(10);
        return view('loan.show', compact('loan', 'entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        preventHttp();
        return view('loan.form', compact('loan'));
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
        $loan = parent::update($request, $loan);
        return back()->with('success-alert', 'হাওলাত খাতা এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        $message = parent::destroy($loan);
        return back()->with('success-alert', $message['success']);
    }

    public function forceDelete($expense)
    {
        $message = parent::forceDelete($expense);
        return back()->with('success-alert', $message['success']);
    }

    public function restore($expense)
    {
        $message = parent::restore($expense);
        return back()->with('success-alert', $message['success']);
    }

    public function loanReceipt()
    {
        // preventHttp();
        $model = 'loan-receipt';
        $list = Loan::all();

        return view('layouts.datalist', compact('model', 'list'));
    }

    public function loanPayment()
    {
        // preventHttp();
        $model = 'loan-payment';
        $list = Loan::all();

        return view('layouts.datalist', compact('model', 'list'));
    }
}
