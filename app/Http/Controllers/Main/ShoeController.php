<?php

namespace App\Http\Controllers\Main;

use App\Models\Shoe;
use App\Models\View\InventoryEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ShoeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            $order = $request->input('order');
        } else {
            $orderby = 'id';
            $order = 'desc';
        }
        if($orderby === 'id') {
            $query = InventoryEntry::orderByRaw("convert(conv({$orderby}, 16, 10), signed) {$order}");
        } else {
            $query = InventoryEntry::orderBy($orderby, $order);
        }
        if($request->filled('id')) {
            $query = $query->where('id', 'like', $this->likeString($request->input('id')));
        
        }
        if($request->filled('factory')) {
            $query = $query->where('factory', 'like', $this->likeString($request->input('factory')));
        }
        if($request->filled('category')) {
            $query = $query->where('category', 'like', $this->likeString($request->input('category')));
        }
        if($request->filled('color')) {
            $query = $query->where('color', 'like', $this->likeString($request->input('color')));
        }
        if($request->filled('retail_price')) {
            $query = $query->where('retail_price', doubleval($request->input('retail_price')));
        }
        if($request->filled('purchase_price')) {
            $query = $query->where('purchase_price', doubleval($request->input('purchase_price')));
        }
        if($request->filled('count')) {
            $query = $query->where('count', intval($request->input('count')));
        }
        if($request->filled('stock')) {
          
            $query->where('count', '>', 0);
        }
        return (object)[
            'shoes' => $query->paginate(20),
            'count' => $query->sum('count'),
            'total_purchase_price' => $query->sum(DB::raw('purchase_price * count / 12')),
            'total_retail_price' => $query->sum(DB::raw('retail_price * count')),
        ];
        //return $query->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shoe  $shoe
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Shoe $shoe)
    {

        if(!$request->ajax()) {
            $shoe->load('purchaseEntries', 'invoiceEntries', 'acceptedFactoryReturnEntries', 'acceptedRetailReturnEntries','adjustmentEntries');
        }
        return $shoe;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shoe  $shoe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shoe $shoe)
    {

        $array = $request->all();
        if($request->has('image')) {
            $filename = randomImageFileName();
            // Image::make($request->file('image'))->save(imagePath($filename));
            // $array['image'] = $filename;
            if ($request->file("image")) {
                $image = $request->file("image");
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image);
                $image->resize(300, 200);
                $image->save('images/small-thumbnail/' . $imageName);
                $array['image'] = $imageName;
            }
        }
        $shoe->fill($array);
        $shoe->save();
        $inventory = Inventory::find($shoe->id);
        $inventory->factory =$shoe->factory->name;
        $inventory->category =$shoe->category->name;
        $inventory->purchase_price =$shoe->purchase_price;
        $inventory->retail_price =$shoe->retail_price;
        $inventory->image =$shoe->image;
        $inventory->save();

        return $shoe;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shoe  $shoe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shoe $shoe)
    {
        //
    }

    function likeString($string) {
        return '%' . $string . '%';
    }
}
