<?php

namespace App\Http\Controllers\Main;

use App\Models\Cheque;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChequeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $cheques = Cheque::with('accountBook.account', 'accountBook.giftSupplierAccount')->paginate(10);
    $cheques->map(function ($cheque) {
        $cheque->getCurrentAccountBook();
        if ($cheque->getCurrentAccountBook() !== null) {
            $cheque->load('entries');
        }
        return $cheque;
    });

    return $cheques;
}

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function show(Cheque $cheque)
    {
        return $cheque;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cheque $cheque)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cheque  $cheque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cheque $cheque)
    {
        //
    }
}
