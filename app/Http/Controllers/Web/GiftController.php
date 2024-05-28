<?php

namespace App\Http\Controllers\Web;

use App\Models\Gift;
use App\Enums\GiftType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftController extends \App\Http\Controllers\Main\GiftController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage gifts']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gifts = parent::index();
        $trashGifts = Gift::onlyTrashed()->get();
        return view('gift.index', compact('gifts', 'trashGifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $gift = null;
        $giftTypes = GiftType::all();
        return view('gift.form', compact('gift', 'giftTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gift = parent::store($request);
        return back()->with('success-alert', 'নতুন গিফট তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gift  $gift
     * @return \Illuminate\Http\Response
     */
    public function show(Gift $gift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gift  $gift
     * @return \Illuminate\Http\Response
     */
    public function edit(Gift $gift)
    {
        preventHttp();
        $giftTypes = GiftType::all();
        return view('gift.form', compact('gift', 'giftTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gift  $gift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gift $gift)
    {
        $gift = parent::update($request, $gift);
        return back()->with('success-alert', 'গিফট এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gift  $gift
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gift $gift)
    {
        $message = parent::destroy($gift);
        return back()->with('success-alert', $message['success']);
    }

    public function forceDelete($color)
    {
        $message = parent::forceDelete($color);
        return back()->with('success-alert', $message['success']);
    }

    public function restore($color)
    {
        $message = parent::restore($color);
        return back()->with('success-alert', $message['success']);
    }
}
