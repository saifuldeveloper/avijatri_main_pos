<?php

namespace App\Http\Controllers\Main;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseEntry;
use App\Models\Factory;
use App\Models\Shoe;
use App\Models\Transaction;
use App\Models\Cheque;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AccountBook;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use App\Models\View\FactoryAccountEntry;





class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->filled('id')) {
            return ['message' => 'No id'];
        }
        $purchase = $request->input('id');
        return route('purchase.show', compact('purchase'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factory     = Factory::find($request->input('factory_id'));
        $accountBook = $factory->getCurrentAccountBook();
        $purchase    = new Purchase;
        $accountBook->purchases()->save($purchase);
        foreach ($request->input('purchases') as $i => $row) {
            if (isset($row['category_id'])) {
                if ($request->file("purchases.{$i}.image")) {
                    $image     = $request->file("purchases.{$i}.image");
                    $extension = $image->getClientOriginalExtension();
                    $imageName = $row['shoe_id'] . '_' . uniqid() . '.' . $extension;
                    $manager   = new ImageManager(new Driver());
                    $image     = $manager->read($image);
                    $image->resize(720, 720);
                    $image->save('images/small-thumbnail/' . $imageName);
                    $row['image'] = $imageName;
                }
                if (empty($row['purchase_price'])) {
                    $row['purchase_price'] = 0;
                }
                $shoe = new Shoe;
                $shoe->fill($row);
                $shoe->id = $row['shoe_id'];
                $factory->shoes()->save($shoe);
               
            }
            /*$shoeTransaction = new ShoeTransaction;
            $shoeTransaction->fill($row);
            $shoeTransaction->type = ShoeTransaction::PURCHASE;
            $purchase->shoeTransactions()->save($shoeTransaction);*/
            $purchaseEntry = new PurchaseEntry;
            $purchaseEntry->fill($row);
            $purchase->purchaseEntries()->save($purchaseEntry);
            /*--------inventory quentity------- */
            $inventory =Inventory::find($row['shoe_id']);
             if(isset($inventory)){
                $inventory->increment('count', $row['count']);
             }else{
             $inventory                 = new Inventory;
             $inventory->id             = $row['shoe_id'];
             $inventory->factory        = $factory->name;
             $inventory->category       = $row['category'];
             $inventory->color          = $row['color'];
             $inventory->purchase_price = $row['purchase_price'];
             $inventory->retail_price   = $row['retail_price'];
             $inventory->count          = $row['count'];
             $inventory->image          = $row['image'];
             $inventory->save(); 
             }
        }
        if ($request->filled('payment_amount') && $request->input('payment_amount') > 0) {

            $purchase->payment_amount = $request->input('payment_amount');
            $purchase->save();
            if ($request->input('payment_method') == 'cheque') {
                Cheque::issue($request->input('cheque_no'), $accountBook, $request->input('payment_amount'), $request->input('cheque_date'), $purchase);
            } else {
                $description = $request->has('cheque_no') ? 'চেক নং ' . $request->input('cheque_no') : null;

                $transaction= Transaction::createTransaction('factory', $factory->id, 'expense', $request->input('payment_method'), $request->input('payment_amount'), $description, $purchase);
                $accountEntries             = new FactoryAccountEntry;
                $accountEntries->account_id = $factory->id;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type =2;
                $accountEntries->entry_id   =$transaction->id;
                $accountEntries->save();
            }
        }

        $accountEntries             = new FactoryAccountEntry;
        $accountEntries->account_id = $factory->id;
        $accountEntries->account_book_id = $accountBook->id;
        $accountEntries->entry_type =0;
        $accountEntries->entry_id   =$purchase->id;
        $accountEntries->save();

        $purchase->load('accountBook.account', 'purchaseEntries.shoe', 'transaction', 'cheque');
        return $purchase;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //$purchase->load('accountBook.account', 'shoeTransactions.shoe');
        $purchase->load('accountBook.account', 'purchaseEntries.shoe');
        return $purchase;
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
        $purchase->load('accountBook.account');
        $survived = [];
        $factory = Factory::find($request->input('factory_id'));
        if ($purchase->accountBook->open && $purchase->accountBook->account_id != $request->input('factory_id')) {
            // $factory = Factory::find($request->input('factory_id'));
            $accountBook = $factory->getCurrentAccountBook();
            $purchase->accountBook()->associate($accountBook);
            $purchase->save();
        }

        foreach ($request->input('purchases') as $i => $row) {
            if (isset($row['id'])) {
                /*$shoeTransaction = ShoeTransaction::find($row['id']);
                $shoeTransaction->fill($row);
                $shoeTransaction->save();*/
                $purchaseEntry = PurchaseEntry::find($row['id']);
                $purchaseEntry->fill($row);
                $purchaseEntry->save();
                $inventory =Inventory::find($row['shoe_id']);
                $total =$purchaseEntry->count - $inventory->count;
                $inventory->count = max(0, $inventory->count + $total);
                $inventory->save();

            } else {
                if (isset($row['category_id'])) {
                    $filename = randomImageFileName();
                    if ($request->file("purchases.{$i}.image")) {
                        $image = $request->file("purchases.{$i}.image");
                        $extension = $image->getClientOriginalExtension();
                        $imageName = $row['shoe_id'] . '_' . uniqid() . '.' . $extension;
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($image);
                        $image->resize(720, 720);
                        $image->save('images/small-thumbnail/' . $imageName);
                        $row['image'] = $imageName;
                    }
                    $shoe = new Shoe;
                    $shoe->fill($row);
                    $shoe->id = $row['shoe_id'];
                    $purchase->accountBook->account->shoes()->save($shoe);
                }
                /*$shoeTransaction = new ShoeTransaction();
                $shoeTransaction->fill($row);
                $shoeTransaction->type = ShoeTransaction::PURCHASE;
                $purchase->shoeTransactions()->save($shoeTransaction);*/
                $purchaseEntry = new PurchaseEntry();
                $purchaseEntry->fill($row);
                $purchase->purchaseEntries()->save($purchaseEntry);
                    $inventory =Inventory::find($row['shoe_id']);
                    if(isset($inventory)){
                        $inventory->increment('count', $row['count']);
                    }else{
                    $inventory                 = new Inventory;
                    $inventory->id             = $row['shoe_id'];
                    $inventory->factory        = $factory->name;
                    $inventory->category       = $row['category'];
                    $inventory->color          = $row['color'];
                    $inventory->purchase_price = $row['purchase_price'];
                    $inventory->retail_price   = $row['retail_price'];
                    $inventory->count          = $row['count'];
                    $inventory->image          = $row['image'];
                    $inventory->save(); 
                    }
            }
            //$survived[] = $shoeTransaction->id;
            $survived[] = $purchaseEntry->id;
        }

        $purchaseEntries = $purchase->purchaseEntries()->get();
        foreach ($purchaseEntries as $purchaseEntry) {
            if (!in_array($purchaseEntry->id, $survived)) {
                $purchaseEntry->delete();
                $inventory = Inventory::find($purchaseEntry['shoe_id']);
                $inventory->delete();
            }
        }

        $purchase->load('purchaseEntries.shoe');
        return $purchase;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        dd('ok');
    }
}
