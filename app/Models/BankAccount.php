<?php

namespace App\Models;

use App\Models\View\BankAccountEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    public function account()
    {
        return $this->morphTo();
    }
    public static function getCashAccount()
    {
        if (self::$cashAccount === null) {
            self::$cashAccount = BankAccount::where('account_no', 'cash')->first();
        }
        return self::$cashAccount;
    }
    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class ,'account_id','id')->where('account_type','bank-account');
    }
    public function entries(){
        return $this->hasMany(BankAccountEntry::class,'account_id','id')->orderBy('created_at','desc');
    }

    // Attributes
    public function getNameAttribute()
    {
        return $this->bank;
        if($this->account_no == 'cash') {
            return $this->bank;
        }
        return $this->bank . ', ' . $this->branch . ' শাখা (অ্যাকাউন্ট নং ' . $this->account_no . ')';
    }

    protected static $cashAccount = null;

    protected $fillable = ['account_no', 'bank', 'branch'];
    protected $appends = ['name'];
}
