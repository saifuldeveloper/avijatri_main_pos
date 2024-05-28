<?php

namespace App\Http\Controllers\Main;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Color::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $color = new Color;
        $color->fill($request->all());
        $color->save();

        return $color;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        return $color;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $color->fill($request->all());
        $color->save();

        return $color;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return collect(['success' => 'রং মুছে ফেলা হয়েছে।']);
    }

    public function forceDelete($id)
    {
        $color = Color::withTrashed()->find($id);
        $color->forceDelete();
        return collect(['success' => 'রং স্থায়ীভাবে মুছে ফেলা হয়েছে।']);
    }

    public function restore($id)
    {
        $color = Color::withTrashed()->find($id);
        $color->restore();
        return collect(['success' => 'রং পুনরুদ্ধার করা হয়েছে।']);
    }
}
