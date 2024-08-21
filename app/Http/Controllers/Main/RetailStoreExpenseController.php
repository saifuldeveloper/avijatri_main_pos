<?php

namespace App\Http\Controllers\Main;

use App\Models\RetailStoreExpense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\View\RetailStoreAccountEntry;


class RetailStoreExpenseController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $retailStoreExpense = RetailStoreExpense::create($request->all());
        $entry                      = new RetailStoreAccountEntry;
        $entry->account_book_id     = $request->account_book_id;
        $entry->entry_type          = '2';
        $entry->expense_id          = $retailStoreExpense->id;
        $entry->amount              = $request->total_amount;
        $entry->expense_description = $request->description;
        $entry->expense_amount      = $request->amount;
        $entry->save();
        return $retailStoreExpense;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RetailStoreExpense  $retailStoreExpense
     * @return \Illuminate\Http\Response
     */
    public function show(RetailStoreExpense $retailStoreExpense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RetailStoreExpense  $retailStoreExpense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetailStoreExpense $retailStoreExpense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RetailStoreExpense  $retailStoreExpense
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetailStoreExpense $retailStoreExpense)
    {
        //
    }
}
