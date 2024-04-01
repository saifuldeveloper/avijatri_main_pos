<?php

namespace App\Models\View;

use App\Models\PurchaseEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryEntry extends Model
{
    use HasFactory;
    // public static methods
	public static function totalStock() {
		return self::sum('count');
	}

	public function totalQty(){
		return $this->belongsTo(PurchaseEntry::class, 'shoe_id', 'id')
                ->selectRaw('sum(count) as total_qty')
                ->groupBy('shoe_id');

	}

	public static function totalStockPurchasePrice() {
		return self::sum(DB::raw('purchase_price * count / 12'));
	}

	public static function totalStockRetailPrice() {
		return self::sum(DB::raw('retail_price * count'));
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

	public function getPreviewUrlAttribute() {
		return imageRoute($this->image, 'preview');
	}

    public $incrementing = false;

    protected $table = 'inventories';
    protected $appends = ['image_url', 'full_image_url', 'thumbnail_url', 'preview_url'];
}
