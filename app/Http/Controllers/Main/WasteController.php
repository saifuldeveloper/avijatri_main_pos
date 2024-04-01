<?php

namespace App\Http\Controllers\Main;

use App\Models\WasteEntry;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WasteController extends Controller
{
    public function shoe(Request $request) {
    	$wasteEntry = new WasteEntry();
    	$wasteEntry->fill($request->all());
		$wasteEntry->entries_id =0;
		$wasteEntry->entries_type ='other';
    	$wasteEntry->save();

    	return $wasteEntry;
    }

    public function gift(Request $request) {
    	$giftTransaction = new GiftTransaction();
    	$giftTransaction->fill($request->all());
    	$giftTransaction->type = 'waste';
    	$giftTransaction->save();

    	return $giftTransaction;
    }
}
