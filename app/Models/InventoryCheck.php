<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\View\InventoryCheckDetail;

class InventoryCheck extends Model
{
    use HasFactory;

    
    // Static functions
    public static function getRunningCheck() {
    	return self::where('complete', false)->orWhere('resolved', false)->latest()->first();
    }

    // Relationships
    public function inventoryCheckEntries() {
    	return $this->hasMany(InventoryCheckEntry::class);
    }

    public function inventoryCheckDetails() {
    	return $this->hasMany(InventoryCheckDetail::class);
    }

    public function fullMatchEntries() {
    	return $this->inventoryCheckDetails()->where('remaining', 0)->orderBy('serial_no', 'asc');
    }

    public function partialMatchEntries() {
    	return $this->inventoryCheckDetails()->where('remaining', '>', 0)->orderBy('serial_no', 'asc');
    }

    public function extraMatchEntries() {
    	return $this->inventoryCheckDetails()->where('remaining', '<', 0)->orderBy('serial_no', 'asc');
    }

    protected $fillable = ['start_date'];
}
