<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends \App\Http\Controllers\Main\CategoryController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage categories']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parents = parent::index();
        return view('category.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        preventHttp();
        $category = null;
        $parents = Category::parentCategoriesQuery()->get();
        return view('category.form', compact('category', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = parent::store($request);
        return back()->with('success-alert', 'নতুন জুতার ধরণ তৈরি হয়েছে।');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        preventHttp();
        $parents = Category::parentCategoriesQuery()->get();
        return view('category.form', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category = parent::update($request, $category);
        return back()->with('success-alert', 'জুতার ধরণ এডিট হয়েছে।');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $message = parent::destroy($category);
        return back()->with('success-alert', $message['success']);
    }

    public function datalist() {

        preventHttp();
        $model = 'category';
        $list = Category::where('parent_id', '<>', '0')->orderBy('parent_id', 'asc')->get();
        $value = 'full_name';
        return view('layouts.datalist', compact('model', 'list', 'value'));
    }
}
