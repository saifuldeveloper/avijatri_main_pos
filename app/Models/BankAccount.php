<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;


    // Static functions
    public static function getCashAccount()
    {
        if (self::$cashAccount === null) {
            self::$cashAccount = BankAccount::where('account_no', 'cash')->first();
        }
        return self::$cashAccount;
    }

    // Relationships
    public function accountBooks()
    {
        return $this->morphMany(AccountBook::class, 'account');
    }

    public function getCurrentAccountBook()
    {
    
        return $this->accountBooks()->latest()->first();
    }

    // Attributes
    public function getNameAttribute()
    {
        return $this->bank;
        // /*if($this->account_no == 'cash') {
        //     return $this->bank;
        // }
        // return $this->bank . ', ' . $this->branch . ' শাখা (অ্যাকাউন্ট নং ' . $this->account_no . ')';*/
    }

    protected static $cashAccount = null;

    protected $fillable = ['account_no', 'bank', 'branch'];
    protected $appends = ['name'];
}
