<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailStoreExpense extends Model
{
    use HasFactory;
    // Relationships
    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function invoice() {
    	return $this->belongsTo(Invoice::class);
    }

    protected $fillable = ['account_book_id', 'description', 'amount'];
}

