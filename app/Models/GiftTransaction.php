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

    // Attributes
    public function getAmountAttribute() {
    	return $this->count * $this->unit_price;
    }

    protected $with = ['gift'];
    protected $appends = ['amount'];
    protected $fillable = ['gift_id', 'count', 'unit_price', 'description'];
}
