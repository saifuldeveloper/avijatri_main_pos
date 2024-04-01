<?php

namespace App\Http\Controllers\Web;

use App\Models\RetailStoreExpense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RetailStoreExpenseController extends \App\Http\Controllers\Main\RetailStoreExpenseController
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $retailStoreExpense = parent::store($request);
        return back()->with('success-alert', 'অন্যান্য খরচ সংরক্ষণ করা হয়েছে।');
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RetailStoreExpense  $retailStoreExpense
     * @return \Illuminate\Http\Response
     */
    public function edit(RetailStoreExpense $retailStoreExpense)
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
