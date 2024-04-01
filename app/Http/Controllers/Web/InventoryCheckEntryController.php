<?php

namespace App\Http\Controllers\Web;

use App\Models\InventoryCheckEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryCheckEntryController extends \App\Http\Controllers\Main\InventoryCheckEntryController
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $inventoryCheckEntry = parent::store($request);
        return redirect()->back();
    }

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryCheckEntry  $inventoryCheckEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryCheckEntry $inventoryCheckEntry)
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
