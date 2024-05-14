<?php

namespace App\Http\Controllers\Main;

use App\Models\InventoryCheck;
use App\Models\AdjustmentEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;

class InventoryCheckController extends Controller
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
        $inventoryCheck = InventoryCheck::create($request->all());
        return $inventoryCheck;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCheck  $inventoryCheck
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryCheck $inventoryCheck)
    {
        $inventoryCheck->load(['fullMatchEntries', 'partialMatchEntries', 'extraMatchEntries']);
        return $inventoryCheck;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryCheck  $inventoryCheck
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryCheck $inventoryCheck)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryCheck  $inventoryCheck
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryCheck $inventoryCheck)
    {
        //
    }

    public function complete(InventoryCheck $inventoryCheck)
    {
        $inventoryCheck->complete = true;
        $inventoryCheck->save();
        return $inventoryCheck;
    }

    public function resolve(Request $request, InventoryCheck $inventoryCheck)
    {


       
        if ($request->has('resolve')) {
            $resolve = $request->input('resolve');
            foreach ($resolve as $r) {
                if ($r['action'] == 'adjust') {
                    $r['type'] = 'out';
                    AdjustmentEntry::create($r);
                    $inventory = Inventory::find($r['shoe_id']);
                    if ($inventory) {
                        $inventory->count -= abs($r['count']);

                        $inventory->save();
                    }
                }else if($r['action'] == 'add'){
                    $r['type'] = 'in';
                    AdjustmentEntry::create($r);
                    $inventory = Inventory::find($r['shoe_id']);
                    if ($inventory) {
                        $inventory->count += abs($r['count']);
                        $inventory->save();
                    }
                }
            }
        }
        $inventoryCheck->resolved = true;
        $inventoryCheck->save();
        return $inventoryCheck;
    }
}
