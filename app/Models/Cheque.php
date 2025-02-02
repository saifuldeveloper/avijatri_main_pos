<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;
    // Static functions
	public static function issue($id, $account_book, $amount, $attachment_type, $due_date, $attachment = null, $closing = null) {

		$cheque = new Cheque();
		$cheque->fill(compact( 'id', 'amount', 'due_date'));
		$cheque->attachment_type = $attachment_type;
		if($attachment !== null) {
			$cheque->attachment()->associate($attachment);
		}
		if($closing !== null) {
			$cheque->closing_id = $closing;
		}
		$account_book->cheques()->save($cheque);
		$account_book               = new AccountBook;
		$account_book->account_id   =$cheque->id;
		$account_book->account_type ='cheque';
		$account_book->save();

		// $entry                  =new ChequeAccountEntries;
		// $entry->entry_id        =$cheque->id;
		// $entry->account_book_id =$cheque->account_book_id;
		// $entry->total_amount    =$amount;
		// $entry->save();
		return $cheque;
	}


	public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }

	// Relationships
	public function accountBooks()
    {
        return $this->hasMany(AccountBook::class ,'account_id','id')->where('account_type','cheque');
    }
	public function accountBook() {
		return $this->belongsTo(AccountBook::class);
	}
	

	public function closingAccount() {
		return $this->belongsTo(AccountBook::class, 'closing_id');
	}

	// public function attachment() {
	// 	return $this->morphTo();
	// }

	public function entries()
    {
        return $this->hasMany(ChequeAccountEntries::class, 'entry_id', 'id')->orderBy('created_at', 'desc');
    }

	public function getCurrentBalanceAttribute()
    {
        $final_balance = 0;
        $desired_balances = [];
        foreach ($this->entries->reverse() as $entry) {
            if ($entry->entry_type == 0) {
                $final_balance += $entry->total_amount;
            }
            $desired_balances[] = $final_balance;
        }

        return array_reverse($desired_balances);
    }




	// Attributes
	public function getNameAttribute() {
		 if($this->accountBook->account_type == 'factory'){
			return 'চেক - ' . $this->accountBook->account->name;
		 }
		return 'চেক - ' . $this->accountBook->giftSupplierAccount->name;
	}

	public function getDueAmountAttribute() {
		return $this->amount - $this->accountBooks()->first()->transactionsTo()->sum('amount');
	}





	public $incrementing = false;
    protected $fillable = ['id', 'account_book_id', 'amount', 'due_date'];
    protected $appends = ['name', 'due_amount'];
}
