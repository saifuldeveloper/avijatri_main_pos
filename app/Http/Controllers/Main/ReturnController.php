<?php

namespace App\Http\Controllers\Main;

use App\Models\ReturnToFactoryEntry;
use App\Models\ReturnFromRetailEntry;
use App\Models\View\FactoryAccountEntry;
use App\Models\WasteEntry;
use App\Models\Factory;
use App\Models\RetailStore;
use App\Models\Shoe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\ReturnToFactory;
use App\Models\View\RetailStoreAccountEntry;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ReturnController extends Controller
{
    public function factory(Request $request)
    {
        $factory = Factory::find($request->input('factory_id'));
        $accountBook = $factory->getCurrentAccountBook();
        $returnfactory = new ReturnToFactory;
        $returnfactory->account_id = $factory->id;
        $returnfactory->account_book_id = $accountBook->id;
        $returnfactory->save();

        foreach ($request->input('returns') as $i => $row) {
            $returnEntry = new ReturnToFactoryEntry();
            $returnEntry->return_id = $returnfactory->id;
            $returnEntry->account_book_id = $returnfactory->account_book_id;
            $returnEntry->shoe_id = $row['shoe_id'];
            $returnEntry->count = $row['count'];
            $returnEntry->save();

            // $returnEntry->fill($row);
            $accountBook->returnToFactoryEntries()->save($returnEntry);
        }
        $accountEntries = new FactoryAccountEntry;
        $accountEntries->account_id = $factory->id;
        $accountEntries->account_book_id = $accountBook->id;
        $accountEntries->entry_type = 1;
        $accountEntries->entry_id = $returnfactory->id;
        $accountEntries->status = 0;
        $accountEntries->save();
        return true;
    }

    public function retailStore(Request $request)
    {

      
      
       
        $retailStore = RetailStore::find($request->input('retail_store_id'));
        $accountBook = $retailStore->getCurrentAccountBook();
        $count =0;
        $return_amount = 0;
        foreach ($request->input('returns') as $i => $row) {
            if (isset($row['category_id'])) {
                if ($request->file("returns.{$i}.image")) {
                    $image = $request->file("returns.{$i}.image");
                    $extension = time() . '.' . $image->getClientOriginalExtension();
                    $imageName = $row['shoe_id'] . '_' . uniqid() . '.' . $extension;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($image);
                    $image->resize(300, 200);
                    $image->save('images/small-thumbnail/' . $imageName);
                    $row['image'] = $imageName;
                }
                if (empty($row['purchase_price'])) {
                    $row['purchase_price'] = 0;
                }
                $shoe = new Shoe;
                $shoe->fill($row);
                $shoe->id = $row['shoe_id'];
                $shoe->save();
                $count +=$row['count'];
                $return_amount +=$shoe->retail_price * $row['commision']/100;
            }
            $returnEntry = new ReturnFromRetailEntry();
            $returnEntry->fill($row);
            /*if(isset($row['category_id'])) {
                $returnEntry->status = 'pending';
            } else if($row['destination'] == 'factory-return') {*/
            if ($row['destination'] == 'pending') {
                $returnEntry->status = 'pending';
            } else if ($row['destination'] == 'factory-return') {
                // $shoe = Shoe::find($row['shoe_id']);
                // $factory = Factory::find($shoe->factory_id);
                // $accountBook = $factory->getCurrentAccountBook();
                // $returnfactory = new ReturnToFactory;
                // $returnfactory->account_id = $factory->id;
                // $returnfactory->account_book_id = $accountBook->id;
                // $returnfactory->save();
                // $returnFactoryEntry = new ReturnToFactoryEntry();
                // $returnFactoryEntry->return_id = $returnfactory->id;
                // $returnFactoryEntry->account_book_id = $returnfactory->account_book_id;
                // $returnFactoryEntry->shoe_id = $row['shoe_id'];
                // $returnFactoryEntry->count = $row['count'];
                // $returnFactoryEntry->save();
                // $accountEntries = new FactoryAccountEntry;
                // $accountEntries->account_id = $factory->id;
                // $accountEntries->account_book_id = $accountBook->id;
                // $accountEntries->entry_type = 1;
                // $accountEntries->entry_id = $returnfactory->id;
                // $accountEntries->status = 0;
                // $accountEntries->save();
            } else if ($row['destination'] == 'waste') {
                $wasteEntry = new WasteEntry();
                $wasteEntry->shoe_id = $row['shoe_id'];
                $wasteEntry->count = $row['count'];
                $wasteEntry->description = 'পার্টি ফেরত থেকে জোলাপ';
                $wasteEntry->entries_id = $retailStore->id;
                $wasteEntry->entries_type = 'retail_store';
                $wasteEntry->save();
            } else {
                if (isset($row['category_id'])) {
                    $inventory = new Inventory;
                    $inventory->id = $row['shoe_id'];
                    $inventory->category = $row['category'];
                    $inventory->color = $row['color'];
                    $inventory->purchase_price = $row['purchase_price'];
                    $inventory->retail_price = $row['retail_price'];
                    $inventory->count = $row['count'];
                    $inventory->image = $row['image'];
                    $inventory->save();
                }
                $inventory = Inventory::find($returnEntry->shoe_id);
                $inventory->increment('count', $returnEntry->count);
                $returnEntry->status = 'accepted';

            }
            $returnEntry->account_book_id = $accountBook->id;
            $returnEntry->save();
            // $accountBook->returnFromRetailEntries()->save($returnEntry);
        }


      

     
        $entry =new RetailStoreAccountEntry;
        $entry->entry_type ='1';
        $entry->account_book_id =$accountBook->id;
        $entry->return_count =$count;
        $entry->save();



        return true;
    }

    public function pendingFactory(Request $request)
    {


        $returnEntry = ReturnToFactoryEntry::find($request->input('id'));
        if ($request->input('accept') == 'reject') {
            $returnEntry->status = 'rejected';
        }else if ($request->input('accept') == 'waste') {

            $returnToFactory = ReturnToFactory::find($returnEntry->return_id);
            $wasteEntry = new WasteEntry;
            $wasteEntry->shoe_id = $returnEntry->shoe_id;
            $wasteEntry->count = $returnEntry->count;
            $wasteEntry->description = 'মহাজন ফেরত না নেয়ায় জোলাপ';
            $wasteEntry->entries_id = $returnToFactory->account_id;
            $wasteEntry->entries_type = 'factory';
            $wasteEntry->save();
            $inventory = Inventory::find($returnEntry->shoe_id);
            $inventory->decrement('count', $returnEntry->count);
            $returnEntry->status ='accepted';
        } else {
            $returnEntry->status = 'accepted';
            $returnToFactory = ReturnToFactory::find($returnEntry->return_id);
            $returnToFactory->status = 'accepted';
            $returnToFactory->save();
            $accountEntries = FactoryAccountEntry::where('entry_type', 1)->where('entry_id', $returnToFactory->id)->first();
            $accountEntries->status = 1;
            $accountEntries->save();
            $inventory = Inventory::find($returnEntry->shoe_id);
            $inventory->decrement('count', $returnEntry->count);
            $inventory->save();
            $returnEntry->status ='accepted';
        }
        $returnEntry->save();
        return $request->input('accept');
    }

    public function pendingRetailStore(Request $request)
    {
        $returnEntry = ReturnFromRetailEntry::find($request->input('id'));
        $returnEntry->load('shoe','accountBook');
        if ($request->input('destination') == 'reject') {
            $returnEntry->status = 'rejected';
        } else if($request->input('destination') == 'inventory') {
            $returnEntry->status = 'accepted';
            $inventory =Inventory::find($returnEntry->shoe_id);
            $inventory->increment('count', $returnEntry->count);
            $returnEntry->status = 'accepted';
        }
        $returnEntry->save();
        switch ($request->input('destination')) {
            case 'factory-return':
                // $factoryReturnEntry = new ReturnToFactoryEntry;
                // $factoryReturnEntry->shoe_id = $returnEntry->shoe_id;
                // $factoryReturnEntry->count = $returnEntry->count;
                // $returnEntry->shoe->factory->getCurrentAccountBook()->returnToFactoryEntries()->save($factoryReturnEntry);
                $shoe = Shoe::find($returnEntry->shoe_id);
                $factory = Factory::find($shoe->factory_id);
                $accountBook = $factory->getCurrentAccountBook();
                $returnfactory = new ReturnToFactory;
                $returnfactory->account_id = $factory->id;
                $returnfactory->account_book_id = $accountBook->id;
                $returnfactory->status = 'accepted';
                $returnfactory->save();
                $returnFactoryEntry = new ReturnToFactoryEntry();
                $returnFactoryEntry->return_id = $returnfactory->id;
                $returnFactoryEntry->account_book_id = $returnfactory->account_book_id;
                $returnFactoryEntry->shoe_id = $returnEntry->shoe_id;
                $returnFactoryEntry->count = $returnEntry->count;
                $returnFactoryEntry->status = 'accepted';
                $returnFactoryEntry->save();
                $accountEntries = new FactoryAccountEntry;
                $accountEntries->account_id = $factory->id;
                $accountEntries->account_book_id = $accountBook->id;
                $accountEntries->entry_type = 1;
                $accountEntries->entry_id = $returnfactory->id;
                $accountEntries->status = 1;
                $accountEntries->save();
                $returnEntry->status ='accepted';
                $returnEntry->save();
                break;

            case 'waste':
                $retailStore =RetailStore::find($returnEntry->accountBook->account_id);
                $wasteEntry = new WasteEntry;
                $wasteEntry->shoe_id = $returnEntry->shoe_id;
                $wasteEntry->count = $returnEntry->count;
                $wasteEntry->description = 'পার্টি ফেরত থেকে জোলাপ';
                $wasteEntry->entries_id = $retailStore->id;
                $wasteEntry->entries_type = 'retail_store';
                $wasteEntry->save();
                $returnEntry->status ='accepted';
                $returnEntry->save();
        }
        return $request->input('destination');
    }
}
