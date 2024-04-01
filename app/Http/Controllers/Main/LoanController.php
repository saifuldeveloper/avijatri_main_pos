<?php

namespace App\Http\Controllers\Main;

use App\Models\Loan;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $accountBook =new AccountBook;
        $accountBook->account_id =$loan->id;
        $accountBook->account_type ='loan';
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
        $loan->append('current_book');
        $loan->current_book->append('entries');
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
}
