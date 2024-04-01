<?php

namespace App\Http\Controllers\Main;

use App\Models\InventoryCheckEntry;
use App\Models\View\InventoryCheckDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Shoe;

class InventoryCheckEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        $inventoryCheckEntry   = InventoryCheckEntry::create($request->all());
        $inventoryCheckDetails = InventoryCheckDetail::where('inventory_check_id' ,$request->inventory_check_id)
                                                        ->where('id', $request->shoe_id)->first();
        $inventory             = Inventory::find($inventoryCheckEntry->shoe_id);
        $shoe                  = Shoe::find($inventoryCheckEntry->shoe_id);
        if (!$inventoryCheckDetails) {
            $inventory_details                     = new InventoryCheckDetail;
            $inventory_details->serial_no          = $inventoryCheckEntry->id;
            $inventory_details->id                 = $shoe->id;
            $inventory_details->inventory_check_id = $inventoryCheckEntry->inventory_check_id;
            $inventory_details->factory            = $shoe->factory->name;
            $inventory_details->category           = $shoe->category->name;
            $inventory_details->color              = $shoe->color->name;
            $inventory_details->retail_price       = $shoe->retail_price;
            $inventory_details->purchase_price     = $shoe->purchase_price;
            $inventory_details->count              = $inventoryCheckEntry->count;
            $count                                 = InventoryCheckEntry::where('inventory_check_id', $inventoryCheckEntry->inventory_check_id)->
                                                                          where('shoe_id', $shoe->id)->get();
            $totalCountBreakdown                   = [];
            $totalCount                            = 0;
            foreach ($count as $item) {
                $totalCountBreakdown[]             = $item->count;
                $totalCount                        += $item->count;
            }
            $totalCountString                      = implode('+', $totalCountBreakdown);

            $inventory_details->total_count_breakdown = $totalCountString;
            $inventory_details->remaining          = $inventory->count - $totalCount;
            $inventory_details->count              = $totalCount;
            $inventory_details->image = $shoe->image;
            $inventory_details->save();
        } else {
            $inventory_details = $inventoryCheckDetails;
            $count                                 = InventoryCheckEntry::where('inventory_check_id', $inventoryCheckEntry->inventory_check_id)
                                                   ->where('shoe_id', $shoe->id)->get();
            $totalCountBreakdown                   = [];
            $totalCount                            = 0;
            foreach ($count as $item) {
                $totalCountBreakdown[]             = $item->count;
                $totalCount                        += $item->count;
            }
            $totalCountString                      = implode('+', $totalCountBreakdown);
            $inventory_details->total_count_breakdown = $totalCountString;
            $inventory_details->remaining          = $inventory->count - $totalCount;
            $inventory_details->count              = $totalCount;
            $inventory_details->image              = $shoe->image;
            $inventory_details->save();
        }

        return $inventoryCheckEntry;
    }




    //     return $inventoryCheckEntry;
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCheckEntry  $inventoryCheckEntry
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryCheckEntry $inventoryCheckEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCheckEntry  $inventoryCheckEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCheckEntry $inventoryCheckEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCheckEntry  $inventoryCheckEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCheckEntry $inventoryCheckEntry)
    {
        //
    }
}
