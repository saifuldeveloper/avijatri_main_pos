<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RetailStore extends Model
{
    use HasFactory;
    // Relationships
	public function unlistedReturns() {
		return $this->hasManyThrough(ReturnFromRetailEntry::class, AccountBook::class, 'account_id')
			->where('account_type', 'retail-store')->where('invoice_id', 0)->where('status', '<>', 'rejected');
	}

	public function unlistedExpenses() {
		return $this->hasManyThrough(RetailStoreExpense::class, AccountBook::class, 'account_id')
			->where('account_type', 'retail-store')->where('invoice_id', 0);
	}

	// Attributes
	public function getNameAttribute() {
		return $this->shop_name . ($this->address !== null ? ' - ' . $this->address : '');
	}

	public function getReturnCountAttribute() {
		return $this->unlistedReturns()->sum('count');
	}

	public function getReturnAmountAttribute() {
		return $this->unlistedReturns()
			->join('shoes', 'return_from_retail_entries.shoe_id', '=', 'shoes.id')
			->sum(DB::raw('return_from_retail_entries.count * shoes.retail_price * (100 - return_from_retail_entries.commission) / 100'));
	}

	public function getOtherCostsAttribute() {
		return $this->unlistedExpenses()->sum('amount');
	}

	public function getUnlistedReturnUrlAttribute() {
		return route('ajax.return.unlisted', ['retailStore' => $this]);
	}

	public function getPreviousBookAttribute() {
		return $this->accountBooks()->where('open', false)->latest()->first();
	}


	public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')
            ->where('account_type', 'retail-store');
    }


	public function getCurrentAccountBook(){
		return $this->accountBooks()->latest()->first();
	}

    protected $fillable = ['shop_name', 'address', 'mobile_no', 'onetime_buyer'];
    protected $appends = ['name'];
}
