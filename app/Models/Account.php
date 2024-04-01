<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\View\BankAccountEntry;

class Account extends Model
{
    use HasFactory;

    public function accountBooks() {
		return $this->morphMany(AccountBook::class, 'account');
	}


     public function bankAccountEntries() {
		return $this->morphMany(BankAccountEntry::class, 'account');
	}

  // Attributes
  public function getCurrentBookAttribute() {
		return $this->getCurrentAccountBook();
	}
	
	// Methods
    public function getCurrentAccountBook() {
    	return $this->accountBooks()->where('open', true)->first();
    }
}
