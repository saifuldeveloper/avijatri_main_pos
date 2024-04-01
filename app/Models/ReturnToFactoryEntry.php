<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnToFactoryEntry extends Model
{
    use HasFactory;
    
    public function accountBook() {
    	return $this->belongsTo(AccountBook::class);
    }

    public function shoe() {
    	return $this->belongsTo(Shoe::class);
    }


    protected $fillable = ['shoe_id', 'count','return_id'];
}
