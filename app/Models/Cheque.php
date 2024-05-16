<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;
    // Static functions
	public static function issue($id, $account_book, $amount, $due_date, $attachment = null, $closing = null) {
		$cheque = new Cheque();
		$cheque->fill(compact( 'id', 'amount', 'due_date'));
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

		$entry                  =new ChequeAccountEntries;
		$entry->entry_id        =$cheque->id;
		$entry->account_book_id ==$cheque->account_book_id;
		$entry->total_amount    =$amount;
		$entry->save();
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

	public function attachment() {
		return $this->morphTo();
	}

	// Attributes
	public function getNameAttribute() {
		return 'চেক - ' . $this->accountBook->account->name;
	}

	public function getDueAmountAttribute() {
		return $this->amount - $this->accountBooks()->first()->transactionsTo()->sum('amount');
	}

	public $incrementing = false;
	
    protected $fillable = ['id', 'account_book_id', 'amount', 'due_date'];
    protected $appends = ['name', 'due_amount'];
}
