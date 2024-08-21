<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftTransaction extends Model
{
    use HasFactory;
     // Relationships
     public function gift() {
    	return $this->belongsTo(Gift::class);
    }
    
    public function attachment() {
    	return $this->morphTo();
    }

    public function giftPurchase() {
    	return $this->belongsTo(GiftPurchase::class,'attachment_id','id');
    }


    public static function getGiftPurchasesOn($date){
        $current = new \Carbon\CarbonImmutable($date);
        $next = $current->addDay();
        return GiftTransaction::with('gift.giftType','giftPurchase.accountBook.giftSupplierAccount')->whereBetween('created_at', [$current, $next])->where('type', 'purchase')->get();

    }

    // Attributes
    public function getAmountAttribute() {
    	return $this->count * $this->unit_price;
    }

    protected $with = ['gift'];
    protected $appends = ['amount'];
    protected $fillable = ['gift_id', 'count', 'unit_price', 'description'];
}
