<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceEntry extends Model
{
    use HasFactory;
      // Relationships
      public function invoice() {
    	return $this->belongsTo(Invoice::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }

    public function giftTransactions() {
        return $this->morphMany(GiftTransaction::class, 'attachment');
    }

    // Attributes
    public function getTotalPriceAttribute() {
    	if(isset($this->shoe)) {
    		$shoe = $this->shoe;
    	} else {
    		$shoe = $this->shoe()->first();
    	}
		return $shoe->retail_price * $this->count;
    }

    protected $fillable = ['shoe_id', 'count'];
    protected $appends = ['total_price'];
}
