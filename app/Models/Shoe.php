<?php

namespace App\Models;

use App\Models\View\InventoryEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoe extends Model
{
	use HasFactory;
	// Queries
	public static function getNextId()
	{
		$last = self::where('id', 'not like', 'X-%')->orderByRaw('convert(conv(id, 16, 10), signed) desc')->first();
		if (!$last) {
			return '100';
		}
		$lastId = hexdec($last->id);
		$nextId = dechex($lastId + 1);
		return $nextId;
	}

	public static function getNextTrashId()
	{
		$last = self::where('id', 'like', 'X-%')->orderByRaw('convert(conv(substring(id, 3), 16, 10), signed) desc')->first();
		if (!$last) {
			return '100';  
		}
		$lastId = hexdec(substr($last->id, 2));
		$nextId = dechex($lastId + 1);
		return $nextId;
	}

	// Relationships
	private function inventoryEntry()
	{
		return $this->hasOne(InventoryEntry::class, 'id');
	}

	public function factory()
	{
		return $this->belongsTo(Factory::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function color()
	{
		return $this->belongsTo(Color::class);
	}

	public function box()
	{
		return $this->belongsTo(Gift::class, 'box_id');
	}

	public function bag()
	{
		return $this->belongsTo(Gift::class, 'bag_id');
	}

	public function purchaseEntries()
	{
		return $this->hasMany(PurchaseEntry::class)->orderByDesc('created_at');
	}

	public function invoiceEntries()
	{
		return $this->hasMany(InvoiceEntry::class)->orderByDesc('created_at');
	}

	public function returnToFactoryEntries()
	{
		return $this->hasMany(ReturnToFactoryEntry::class)->orderByDesc('created_at');
	}

	public function acceptedFactoryReturnEntries()
	{
		return $this->returnToFactoryEntries()->where('status', '<>', 'rejected');
	}

	public function returnFromRetailEntries()
	{
		return $this->hasMany(ReturnFromRetailEntry::class);
	}

	public function acceptedRetailReturnEntries()
	{
		return $this->returnFromRetailEntries()->where('status', 'accepted');
	}

	public function inventoryCheckEntries()
	{
		return $this->hasMany(inventoryCheckEntry::class ,'shoe_id','id');
	}
	public function adjustmentEntries()
	{
		return $this->hasMany(AdjustmentEntry::class ,'shoe_id','id')->orderByDesc('created_at');
	}

	// Attributes
	public function getAvailableAttribute()
	{
		return $this->inventoryEntry()->first()->count ?? 0;
	}

	public function getImageUrlAttribute()
	{
		return imageRoute($this->image, 'small-thumbnail');
	}

	public function getFullImageUrlAttribute()
	{
		return imageRoute($this->image, 'original');
	}

	public function getThumbnailUrlAttribute()
	{
		return imageRoute($this->image, 'thumbnail');
	}

	public function getPreviewUrlAttribute()
	{
		return imageRoute($this->image, 'preview');
	}

	public $incrementing = false;

	protected $with = ['factory', 'category', 'color'];
	protected $appends = ['image_url', 'full_image_url', 'thumbnail_url', 'preview_url', 'available'];
	protected $fillable = [
		'factory_id',
		'category_id',
		'color_id',
		'image',
		'purchase_price',
		'retail_price',
		'box_id',
		'bag_id',
		'initial_count'
	];
	protected $casts = [
		'factory_id' => 'integer',
		'category_id' => 'integer',
		'color_id' => 'integer',
		'box_id' => 'integer',
		'bag_id' => 'integer',
		'initial_count' => 'integer',
		'purchase_price' => 'double',
		'retail_price' => 'double',
	];

	protected $attributes = [
		'box_id' => 0, // Set your default value here
		'bag_id' => 0,
	];
}
