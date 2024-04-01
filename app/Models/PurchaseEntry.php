<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseEntry extends Model
{
    use HasFactory;
     // static functions
     public static function getPurchasesOn($date) {
        return self::getPurchasesOnQuery($date)
            ->orderBy('inventories.factory', 'asc')
            ->orderBy('inventories.category', 'asc')
            ->orderBy('inventories.retail_price', 'desc')
            ->get();
    }

    public static function getPurchaseSummaryOn($date) {
        $query = self::getPurchasesOnQuery($date);
        $sql = $query->toSql();
        return DB::table(DB::raw("({$sql}) query_table"))
            ->mergeBindings($query->getQuery())
            ->selectRaw('sum(count) total_count, sum(total_price_a) total_price')
            ->first();
    }

    protected static function getPurchasesOnQuery($date) {
        $current = new \Carbon\CarbonImmutable($date);
        $next = $current->addDay();
        //return self::where('created_at', '>=', $current)->where('created_at', '<', $next)->with('shoe')->get();
        return self::join('inventories', 'inventories.id', '=', 'purchase_entries.shoe_id')
            ->where('purchase_entries.created_at', '>=', $current)->where('purchase_entries.created_at', '<', $next)
            ->groupBy('inventories.factory', 'inventories.retail_price', 'inventories.category')
            ->selectRaw('inventories.factory, inventories.category, group_concat(distinct inventories.color separator "+") color, inventories.retail_price, group_concat(distinct inventories.purchase_price separator "/") purchase_price, sum(purchase_entries.count) count, sum(purchase_entries.count * inventories.purchase_price / 12) total_price_a');
    }
    
    // Relationships
    public function purchase() {
    	return $this->belongsTo(Purchase::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }

    // Attributes
    public function getTotalPriceAttribute() {
    	if(isset($this->shoe)) {
    		$shoe = $this->shoe;
    	} else {
    		$shoe = $this->shoe()->first();
    	}
		return $shoe->purchase_price * $this->count / 12;
    }

    protected $fillable = ['shoe_id', 'count'];
    protected $appends = ['total_price'];
}
