<?php

namespace App\Http\Controllers\Web;

use App\Models\InventoryCheck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryCheckController extends \App\Http\Controllers\Main\InventoryCheckController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage shoes']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventoryCheck = InventoryCheck::getRunningCheck();

    
        if($inventoryCheck === null) {
            return redirect()->route('inventory-check.create');
        } else {
            // return redirect()->route('inventory-check.show', compact('inventoryCheck'));
            return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inventory-check.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventoryCheck = parent::store($request);
        // return redirect()->route('inventory-check.show', compact('inventoryCheck'));
        return view('inventory-check.show', compact('inventoryCheck'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryCheck  $inventoryCheck
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryCheck $inventoryCheck)
    {
        $inventoryCheck = parent::show($inventoryCheck);
        

        return view('inventory-check.show', compact('inventoryCheck'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryCheck  $inventoryCheck
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCheck $inventoryCheck)
    {
        //
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
        $inventoryCheck = parent::complete($inventoryCheck);
        
        // return redirect()->route('inventory-check.show'./$inventoryCheck->id , compact('inventoryCheck'));
        return redirect()->route('inventory-check.show', ['inventory_check' => $inventoryCheck->id]);
    }

    public function resolve(Request $request, InventoryCheck $inventoryCheck)
    {
        $inventoryCheck = parent::resolve($request, $inventoryCheck);
        return redirect()->route('shoe.index')->with('success-alert', 'ইনভেন্টরি চেক শেষ হয়েছে।');
    }
}
