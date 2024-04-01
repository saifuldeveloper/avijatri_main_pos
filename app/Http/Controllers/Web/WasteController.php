<?php

namespace App\Http\Controllers\Web;

use App\Models\Gift;
use App\Models\WasteEntry;
use App\Models\GiftTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WasteController extends \App\Http\Controllers\Main\WasteController
{
	public function shoePage()
	{
		$wasteEntries = WasteEntry::latest()->paginate(20);
		$factoryCount = $wasteEntries->where('entries_type', 'factory')->count();
		$retailstoreCount = $wasteEntries->where('entries_type', 'retail_store')->count();
		$otherCount = $wasteEntries->whereNotIn('entries_type', ['factory', 'retail_store'])->count();
		return view('waste.shoes', compact('wasteEntries','factoryCount','retailstoreCount','otherCount'));
	}

	public function shoe(Request $request)
	{
		$wasteEntry = parent::shoe($request);
		return back()->with('success-alert', 'জুতা জোলাপের তথ্য সংরক্ষণ করা হয়েছে।');
	}

	public function giftPage()
	{
		$gifts = Gift::all();
		$wasteEntries = GiftTransaction::where('type', 'waste')->latest()->paginate(20);
		return view('waste.gifts', compact('gifts', 'wasteEntries'));
	}

	public function gift(Request $request)
	{
		$giftTransaction = parent::gift($request);
		return back()->with('success-alert', 'গিফট জোলাপের তথ্য সংরক্ষণ করা হয়েছে।');
	}
}
