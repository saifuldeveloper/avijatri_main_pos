<?php

namespace App\Http\Controllers\Web;

use App\Models\GiftSupplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftSupplierController extends \App\Http\Controllers\Main\GiftSupplierController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage gift suppliers'])->except(['datalist']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $giftSuppliers = parent::index();

        return view('gift-supplier.index', compact('giftSuppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $giftSupplier = null;
        return view('gift-supplier.form', compact('giftSupplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $giftSupplier = parent::store($request);
        return back()->with('success-alert', 'নতুন গিফট মহাজন তথ্য সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(GiftSupplier $giftSupplier)
    {

        $giftSupplier = parent::show($giftSupplier);
        $entries = $giftSupplier->entries()->paginate(10);
        return view('gift-supplier.show', compact('giftSupplier','entries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function edit(GiftSupplier $giftSupplier)
    {
        preventHttp();
        return view('gift-supplier.form', compact('giftSupplier'));
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
        $giftSupplier = parent::update($request, $giftSupplier);
        return back()->with('success-alert', 'গিফট মহাজন তথ্য এডিট করা হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GiftSupplier  $giftSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(GiftSupplier $giftSupplier)
    {
        $message = parent::destroy($giftSupplier);
        return redirect()->route('gift-supplier.index')->with('success-alert', $message['success']);
    }

    public function datalist() {
        preventHttp();
        $model = 'gift-supplier';
        $list = GiftSupplier::all();
        return view('layouts.datalist', compact('model', 'list'));
    }
}
