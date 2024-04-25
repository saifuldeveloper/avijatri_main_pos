<?php

namespace App\Http\Controllers\Web;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseController extends \App\Http\Controllers\Main\ExpenseController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage expenses'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = parent::index();
        return view('expense.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $expense = null;
        return view('expense.form', compact('expense'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $expense = parent::store($request);
        return back()->with('success-alert', 'নতুন খরচের খাত তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense = parent::show($expense);
        $entreis = $expense->entries()->paginate(10);
        return view('expense.show', compact('expense','entreis'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        preventHttp();
        return view('expense.form', compact('expense'));
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
        $expense = parent::update($request, $expense);
        return back()->with('success-alert', 'খরচের খাত এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $message = parent::destroy($expense);
        return back()->with('success-alert', $message['success']);
    }

    public function datalist() {
        preventHttp();
        $model = 'expense';
        $list = Expense::all();
        return view('layouts.datalist', compact('model', 'list'));
    }
}