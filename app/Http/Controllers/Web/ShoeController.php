<?php

namespace App\Http\Controllers\Web;

use App\Models\Shoe;
use ZipArchive;
use App\Views\InventoryEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ShoeController extends \App\Http\Controllers\Main\ShoeController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage shoes']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data =Shoe::where('id',$request->id)->first();
        /*$stock = \App\Views\InventoryEntry::totalStock();
        $stock_purchase_price = \App\Views\InventoryEntry::totalStockPurchasePrice();*/
        if($request->has('orderby')) {
            $orderby = $request->input('orderby');
            $order = $request->input('order');
        } else {
            $orderby = 'id';
            $order = 'desc';
        }
        $data = parent::index($request);
        $shoes = $data->shoes;
        $stock = $data->count;
        $stock_purchase_price = $data->total_purchase_price;
        $total_retail_price  = $data->total_retail_price;
        if($request->ajax()) {
            $tbody = view('shoe.search.tbody', compact('shoes'))->render();
            $pagination = view('shoe.search.pagination', compact('shoes'))->render();
            return compact('stock', 'stock_purchase_price', 'tbody', 'pagination');
        }
        return view('shoe.index', compact('stock', 'stock_purchase_price','total_retail_price', 'shoes', 'orderby', 'order'));
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
        $shoe = parent::show($request, $shoe);
        return view('shoe.show', compact('shoe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shoe  $shoe
     * @return \Illuminate\Http\Response
     */
    public function edit(Shoe $shoe)
    {
        preventHttp();
        return view('shoe.edit', compact('shoe'));
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
        $shoe = parent::update($request, $shoe);
        return back()->with('success-alert', 'জুতার তথ্য এডিট হয়েছে।');
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

    public function barcodePage() {
        return view('barcode.page');
    }

    public function barcode(Request $request) {
        $entries = [];

        foreach($request->input('entries') as $entry) {
            $entries[] = (object)[
                'shoe' => Shoe::find($entry['shoe_id']),
                'count' => $entry['count'],
            ];
        }
        return view('barcode.printer', compact('entries'));
    }

    public function barcodeTr(Request $request) {
        preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
        return view('barcode.tr', compact('index'));
    }



    // public function download(Request $request)
    // {


    //     $imagePaths = Shoe::whereIn('id', $request->id)->pluck('image')->toArray();
    //     if($imagePaths == null){
           
    //         return response()->json(['success' => false, 'error' => 'জুতা সিলেক্ট  করা হয় নাই '], 500);
 
    //     }


    //     $zipFileName = 'images.zip';
    //     $zipFilePath =public_path('zip/' . $zipFileName);
    //     $publicZipFilePath = 'zip/' . $zipFileName;
    
    //     $zip = new ZipArchive;
    //     if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
    //         foreach ($imagePaths as $imagePath) {
    //             $filePath = public_path('images/small-thumbnail/' . $imagePath);
    //             if (file_exists($filePath)) {
    //                 $zip->addFile($filePath, basename($filePath));
    //             }
    //         }
    //         $zip->close();
    //         // exec("unlink $zipFilePath > /dev/null 2>&1 &");
    //         session(['zip_file_path' => $zipFilePath]);


    //         return response()->json([
    //             'success' => true,
    //             'file_url' => $zipFilePath,
    //             'file_name' => asset($publicZipFilePath),
    //         ]);

             
    //     } else {
    //         return response()->json(['success' => false, 'error' => 'Unable to create zip archive'], 500);
    //     }
    // }

    public function download(Request $request)
{
    // Retrieve image paths based on provided shoe IDs
    $imagePaths = Shoe::whereIn('id', $request->id)->pluck('image')->toArray();

    // If no image paths are found, return an error response
    if(empty($imagePaths)) {
        return response()->json(['success' => false, 'error' => 'জুতা সিলেক্ট  করা হয় নাই '], 500);
    }

    // Define the name and path of the zip file to be created
    $zipFileName = 'images.zip';
    $zipDirectory = public_path('zip');
    $zipFilePath = $zipDirectory . '/' . $zipFileName;
    $publicZipFilePath = 'zip/' . $zipFileName;

    // Create the directory if it does not exist
    if (!file_exists($zipDirectory)) {
        mkdir($zipDirectory, 0755, true);
    }

    // Create a new ZipArchive instance
    $zip = new ZipArchive;
    // Attempt to open the zip file for writing
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Loop through each image path
        foreach ($imagePaths as $imagePath) {
            // Construct the full file path of the image
            $filePath = public_path('images/small-thumbnail/' . $imagePath);
            // If the image file exists, add it to the zip archive
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }
        // Close the zip archive
        $zip->close();

        // Store the path to the zip file in the session
        session(['zip_file_path' => $zipFilePath]);

        // Return a JSON response indicating success along with file URL and name
        return response()->json([
            'success' => true,
            'file_url' => $zipFilePath,
            'file_name' => asset($publicZipFilePath),
        ]);
    } else {
        // If unable to create the zip archive, return an error response
        return response()->json(['success' => false, 'error' => 'Unable to create zip archive'], 500);
    }
}

    

    public function downloadDeleted(Request $request) {
        // $fileUrl = $request->input('file_url');
    
        $zipFilePath = session('zip_file_path');
        if (!empty($zipFilePath) && file_exists($zipFilePath)) {
            unlink($zipFilePath);
            session()->forget('zip_file_path');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'File not found']);
        }
    }
    
}
