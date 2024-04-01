<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InventoryCheck;

class InventoryCheckDetail extends Model
{
    use HasFactory;
    // Relationships
    public function inventoryCheck() {
    	return $this->belongsTo(InventoryCheck::class);
    }

    // Attributes
	public function getImageUrlAttribute() {
		return imageRoute($this->image, 'small-thumbnail');
	}

	public function getFullImageUrlAttribute() {
		return imageRoute($this->image, 'original');
	}

	public function getThumbnailUrlAttribute() {
		return imageRoute($this->image, 'thumbnail');
	}

	public $incrementing = false;
}
