<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiftPurchase extends Model
{
    use HasFactory,SoftDeletes;


    // Relationships
	public function accountBook() {
		return $this->belongsTo(AccountBook::class);
	}
	
	public function giftTransactions() {
		return $this->morphMany(GiftTransaction::class, 'attachment');
	}

    public function transaction() {
        return $this->morphOne(Transaction::class, 'attachment');
    }

	// Attributes
	public function getTotalAmountAttribute() {
		return $this->giftTransactions()->sum(DB::raw('count * unit_price'));
	}

    protected $fillable = ['account_book_id'];
    protected $appends = ['total_amount'];



   
    public static function getNextId()
    {
        $dbname = config('database.connections.mysql.database');
        $table = (new self)->getTable();
        $query = DB::select("SELECT AUTO_INCREMENT 
                             FROM information_schema.TABLES
                             WHERE TABLE_SCHEMA = '{$dbname}'
                             AND TABLE_NAME = '{$table}'");

        return $query[0]->AUTO_INCREMENT;
    }
}
