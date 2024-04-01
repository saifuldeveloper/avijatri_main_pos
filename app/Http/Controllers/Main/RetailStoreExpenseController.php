<?php

namespace App\Http\Controllers\Main;

use App\Models\RetailStoreExpense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
