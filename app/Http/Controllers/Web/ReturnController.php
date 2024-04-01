<?php

namespace App\Http\Controllers\Web;

use App\Models\ReturnToFactoryEntry;
use App\Models\ReturnFromRetailEntry;
use App\Models\RetailStore;
use App\Models\Shoe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Factory;

class ReturnController extends \App\Http\Controllers\Main\ReturnController
{
    // public function __construct() {
    //     $this->middleware(['permission:manage returns to factory'])->only(['factoryPage', 'factory']);
    //     $this->middleware(['permission:manage returns from retail stores'])->only(['retailStorePage', 'retailStore']);
    //     $this->middleware(['permission:manage pending returns'])->only(['pendingPage', 'pendingFactory', 'pendingRetailStore']);
    // }

    public function factoryPage() {
    	return view('return.factory');
    }

    public function factory(Request $request) {
        $return = parent::factory($request);
        return redirect()->route('home')->with('success-alert', 'জুতা ফেরত সম্পন্ন হয়েছে।');
    }

    public function retailStorePage() {
        $nextShoe = Shoe::getNextTrashId();
    	return view('return.retail-store', compact('nextShoe'));
    }

    public function retailStore(Request $request) {
        $return = parent::retailStore($request);
        return redirect()->route('home')->with('success-alert', 'জুতা ফেরত সম্পন্ন হয়েছে।');
    }

    public function pendingPage() {
        $returnToFactoryEntries = ReturnToFactoryEntry::where('status', 'pending')->with('shoe', 'accountBook.account')->get();
        $returnFromRetailEntries = ReturnFromRetailEntry::where('status', 'pending')->with('shoe', 'accountBook.account')->get();
        return view('return.pending', compact('returnToFactoryEntries', 'returnFromRetailEntries'));
    }

    public function pendingFactory(Request $request) {
        $accept = parent::pendingFactory($request);
        switch($accept) {
            case 'accept':
            $successAlert = 'জুতা ফেরত নগদে সম্পন্ন হয়েছে।';
            break;

            case 'waste':
            $successAlert = 'জুতা জোলাপ হয়েছে।';
            break;

            case 'reject':
            $successAlert = 'জুতা ফেরত বাতিল হয়েছে।';
            break;
        }
        return redirect()->back()->with('success-alert', $successAlert);
    }

    public function pendingRetailStore(Request $request) {
        $destination = parent::pendingRetailStore($request);
   
        switch($destination) {
            case 'factory-return':
            $successAlert = 'ফেরত জুতা কারখানায় ফেরত পাঠানো হয়েছে।';
            break;

            case 'inventory':
            $successAlert = 'ফেরত জুতা গ্রহণ করা হয়েছে।';
            break;

            case 'reject':
            $successAlert = 'জুতা ফেরত বাতিল হয়েছে।';
            break;
            case 'waste':
                $successAlert = 'জুতা জোলাপ হয়েছে।';
                break;
        }
        return redirect()->back()->with('success-alert', $successAlert);
    }

    public function unlistedReturns(RetailStore $retailStore) {
        preventHttp();

        $retailStore->load('unlistedReturns');
        return view('return.unlisted', ['returns' => $retailStore->unlistedReturns]);
    }

    public function factoryTr(Request $request) {
    	preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
    	return view('return.tr-factory', compact('index'));
    }

    public function retailStoreTr(Request $request) {
    	preventHttp();

        if($request->has('index')) {
            $index = $request->input('index');
        } else {
            $index = 0;
        }
    	return view('return.tr-retail-store', compact('index'));
    }
}
