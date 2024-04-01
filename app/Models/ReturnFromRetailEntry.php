<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnFromRetailEntry extends Model
{
    use HasFactory;
    // Relationships
    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }

    public function invoice() {
    	return $this->belongsTo(Invoice::class);
    }

    // Attributes
    public function getTotalPriceAttribute() {
        return $this->shoe->retail_price * $this->count;
    }

    public function getTotalCommissionAttribute() {
        return $this->shoe->retail_price * $this->count * $this->commission / 100;
    }

    public function getCommissionDeductedAttribute() {
        return $this->shoe->retail_price * $this->count * (100 - $this->commission) / 100;
    }

    protected $fillable = ['shoe_id', 'count', 'commission'];
    protected $with = ['shoe'];
    protected $appends = ['total_price', 'total_commission', 'commission_deducted'];
}
