<?php

namespace App\Models;

use App\Models\View\FactoryAccountEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory extends Model
{
    use HasFactory, SoftDeletes;

    public  function shoes()
    {
        return $this->hasMany(Shoe::class);
    }


    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'factory');
    }

    public function entries()
    {
        return $this->hasMany(FactoryAccountEntry::class, 'entry_id', 'id')->where('status',1)->orderBy('created_at', 'desc');
    }
    
    public function getCurrentBalanceAttribute()
    {
        $final_balance = 0;
        $desired_balances = [];
        foreach ($this->entries->reverse() as $entry) {
            if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
            } else {
                $final_balance -= $entry->total_amount;
            }
            $desired_balances[] = $final_balance;
        }

        return array_reverse($desired_balances);
    }

    public function purchaseBalance()
    {
        return $this->entries->where('entry_type', '0')->sum('total_amount');
    }

    public function payAmount()
    {
        return $this->entries->where('entry_type', '2')->sum('total_amount');
    }
    public function returnAmount()
    {
        return $this->entries->where('entry_type', '2')->where('status',1)->sum('total_amount');
    }
    protected $fillable = ['name', 'address', 'mobile_no'];
}
