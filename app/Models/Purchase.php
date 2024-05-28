<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory,SoftDeletes;
    // Relationships
    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function purchaseEntries() {
        return $this->hasMany(PurchaseEntry::class);
    }

    public function transaction() {
        return $this->morphOne(Transaction::class, 'attachment');
    }

    public function cheque() {
        return $this->morphOne(Cheque::class, 'attachment');
    }

    // Attributes
    public function getTotalAmountAttribute() {
        return $this->purchaseEntries()
            ->join('shoes', 'shoes.id', '=', 'purchase_entries.shoe_id')
            ->sum(DB::raw('shoes.purchase_price * purchase_entries.count / 12'));
    }


    public function ruturnfactory(){
        return $this->hasMany(ReturnToFactoryEntry::class,'account_book_id' ,'account_book_id');
    }

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
