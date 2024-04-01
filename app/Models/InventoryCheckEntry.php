<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheckEntry extends Model
{
    use HasFactory;
     // Relationships
     public function inventoryCheck() {
    	return $this->belongsTo(InventoryCheck::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }

    protected $fillable = ['inventory_check_id', 'shoe_id', 'count'];
}
