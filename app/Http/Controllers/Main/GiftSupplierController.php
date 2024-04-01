<?php

namespace App\Http\Controllers\Main;

use App\Models\GiftSupplier;
use App\Models\AccountBook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GiftSupplier::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $giftSupplier = new GiftSupplier;
        $giftSupplier->fill($request->all());
        $giftSupplier->save();

        if($giftSupplier){
            $account_book = new AccountBook;
            $account_book->account_id =$giftSupplier->id;
            $account_book->account_type ='gift-supplier';
            $account_book->save();
        }
        // $giftSupplier->accountBooks()->save(new AccountBook());

        return $giftSupplier;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(GiftSupplier $giftSupplier)
    {
        $giftSupplier->append('current_book');
        $giftSupplier->current_book->append('entries');
        return $giftSupplier;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GiftSupplier $giftSupplier)
    {
        $giftSupplier->fill($request->all());
        $giftSupplier->save();

        return $giftSupplier;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(GiftSupplier $giftSupplier)
    {
        $giftSupplier->delete();
        return collect(['success' => 'কারখানাদারের তথ্য মুছে ফেলা হয়েছে।']);
    }
}
