<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftSupplier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'mobile_no'];

    public function accountBooks()
    {
        // return $this->hasMany(AccountBook::class);
        return $this->hasOne(AccountBook::class ,'account_id','id')->where('account_type','gift-supplier');
        
    }

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }

}
