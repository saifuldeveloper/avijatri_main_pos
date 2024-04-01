<?php

namespace App\Models\View;

use App\Models\AccountBook;
use App\Models\Purchase;
use App\Models\ReturnToFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryAccountEntry extends Model
{
    use HasFactory;

    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function bankAccountBook() {
    	return $this->belongsTo(AccountBook::class, 'bank_account_book_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'entry_id','id');
    }

    public function returnshoe(){
        return $this->belongsTo(ReturnToFactory::class,'entry_id','id')
         ->where('status','accepted');
            
        }



    
}
