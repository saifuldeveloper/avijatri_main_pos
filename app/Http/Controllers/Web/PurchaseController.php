<?php

namespace App\Http\Controllers\Web;

use App\Models\Purchase;
use App\Models\Factory;
use App\Models\Shoe;
use App\Models\Gift;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PurchaseEntry;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class PurchaseController extends \App\Http\Controllers\Main\PurchaseController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage purchases']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!$request->filled('id')) {
            return back()->with('error-alert', 'আইডি প্রদান করতে হবে।');
        }
        return redirect(parent::index($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $memoNo = Purchase::getNextId();
        $nextShoe = Shoe::getNextId();
        // $boxes = Gift::where('gift_type_id', '1')->get();
        // $bags = Gift::where('gift_type_id', '2')->get();
        $bankAccount = BankAccount::all();
        $bankAccounts = new Collection();
        foreach ($bankAccount as $item) {
            $bankAccounts->push((object) [
                'id' => $item->id,
                'name' => $item->bank . ' - ' . $item->branch . ' - (' . $item->account_no . ')'
            ]);
        }
        $bankAccounts->push((object) ['id' => 'cheque', 'name' => 'চেক']);
        return view('purchase.form', compact('memoNo', 'nextShoe', 'bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('submit') && $request->input('submit') === 'preview') {
            $factory = Factory::find($request->input('factory_id'));
            $purchases = $request->input('purchases');
            $purchase_id = Purchase::getNextId();
            $total_payable = 0;
            $payment_amount = $request->payment_amount;
            foreach ($purchases as $i => $purchase) {
                if (isset($purchase['category_id'])) {
                    $purchases[$i]['total_price'] = doubleval($purchase['purchase_price']) * intval($purchase['count']) / 12;
                    if ($request->file("purchases.{$i}.image")) {
                        $image = $request->file("purchases.{$i}.image");
                        $extension = $image->getClientOriginalExtension();
                        $imageName = $purchase['shoe_id'] . '_' . uniqid() . '.' . $extension;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($image);
                        $image->resize(300, 200);
                        $image->save('images/small-thumbnail/' . $imageName);
                        $purchases[$i]['image'] = $imageName;
                    }
                } else {
                    $purchases[$i]['shoe'] = Shoe::find($purchase['shoe_id']);
                    $purchases[$i]['total_price'] = doubleval($purchases[$i]['shoe']->purchase_price) * intval($purchase['count']) / 12;
                }
                $total_payable += $purchases[$i]['total_price'];
            }
            $preview = true;
            return view('purchase.show', compact('factory', 'purchases', 'purchase_id', 'total_payable', 'preview', 'payment_amount'));
        } else {

            if($request->factory_id == null){
                return redirect()->route('purchase.create')->with('info-alert', 'মহাজন সঠিক নয় ');

            }
            $purchase = parent::store($request);
            return redirect()->route('purchase.show', ['purchase' => $purchase])->with('success-alert', 'জুতা ক্রয় সম্পন্ন হয়েছে।');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase = parent::show($purchase);
        return view('purchase.show', compact('purchase'));
    }

    public function barcode(Purchase $purchase)
    {
        $purchase->load('purchaseEntries.shoe');
        return view('barcode.barcode_printer', ['entries' => $purchase->purchaseEntries]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //$purchase->load('accountBook.account', 'shoeTransactions.shoe');
        $purchase->load('accountBook.account', 'purchaseEntries.shoe');
        $nextShoe = Shoe::getNextId();
        $boxes = Gift::where('gift_type_id', 'box')->get();
        $bags = Gift::where('gift_type_id', 'bag')->get();
        session()->flash('info-alert', 'আগে কেনা জুতার তথ্য এডিট করতে ইনভেন্টরিতে যান।');
        return view('purchase.form', compact('purchase', 'nextShoe', 'boxes', 'bags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        if ($request->has('submit') && $request->input('submit') === 'preview') {
            $factory = Factory::find($request->input('factory_id'));
            $purchases = $request->input('purchases');
            $purchase_id = Purchase::getNextId();
            $total_payable = 0;

            foreach ($purchases as $i => $purchase) {
                if (isset($purchase['category_id'])) {
                    $purchases[$i]['total_price'] = doubleval($purchase['purchase_price']) * intval($purchase['count']) / 12;
                    $filename = randomImageFileName();
                    Image::make($request->file("purchases.{$i}.image"))->save(tempImagePath($filename));
                    $purchases[$i]['image_url'] = imageRoute($filename, 'small-thumbnail');
                    $purchases[$i]['preview_url'] = imageRoute($filename, 'preview');
                } else {
                    $purchases[$i]['shoe'] = Shoe::find($purchase['shoe_id']);
                    $purchases[$i]['total_price'] = doubleval($purchases[$i]['shoe']->purchase_price) * intval($purchase['count']) / 12;
                }
                $total_payable += $purchases[$i]['total_price'];
            }
            $preview = true;
            return view('purchase.show', compact('factory', 'purchases', 'purchase_id', 'total_payable', 'preview'));
        } else {
            $purchase = parent::update($request, $purchase);
            return redirect()->route('purchase.show', ['purchase' => $purchase])->with('success-alert', 'জুতা ক্রয়ের রশিদ এডিট হয়েছে।');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        if($purchase){
            $purchases =PurchaseEntry::where('purchase_id' ,$purchase->id)->get();
            foreach($purchases as $key => $purchase){
                 $shoe =Shoe::find($purchase->shoe_id);
                 $shoe->delete();
                 $purchase->delete();
            }
            $purchase->delete(); 
            return redirect()->route('purchase.create', ['purchase' => $purchase])->with('success-alert', 'জুতা ক্রয়  ডিলিট করা হয়েছে ।');
        }
      
    }

    public function tr(Request $request)
    {
        preventHttp();

        if ($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        $boxes = Gift::where('gift_type_id', 'box')->get();
        $bags = Gift::where('gift_type_id', 'bag')->get();

        return view('purchase.tr', compact('index', 'boxes', 'bags'));
    }
}
