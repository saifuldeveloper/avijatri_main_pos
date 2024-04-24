<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\View\FactoryAccountEntry;
use Illuminate\Database\Query\Expression;


class Transaction extends Model
{
    use HasFactory;

    
	public static function createBankToCashTransaction($type, $bankAccount, $amount, $description = '') {
		$cashAccount = BankAccount::getCashAccount();
		$bankAccount = BankAccount::find($bankAccount);

		switch($type) {
			case 'deposit':
			$from_account_id = $cashAccount->getCurrentAccountBook()->id;
			$to_account_id = $bankAccount->getCurrentAccountBook()->id;
			break;

			case 'withdraw':
			$from_account_id = $bankAccount->getCurrentAccountBook()->id;
			$to_account_id = $cashAccount->getCurrentAccountBook()->id;
			break;

			default:
			return null;
		}

		return self::create(compact('from_account_id', 'to_account_id', 'amount', 'description'));
	}

	public static function createTransaction($accountType, $account, $type, $bankAccount, $amount, $description = '', $attachment = null, $closing = null) {
		$bankAccount = BankAccount::where('id',$bankAccount)->first();

		switch($accountType) {
			case 'account-book':
			break;
			case 'factory':
			$account = Factory::find($account);
			break;
			case 'retail-store':
			$account = RetailStore::find($account);
			break;
			case 'gift-supplier':
			$account = GiftSupplier::find($account);
			break;
			case 'cheque':
			$account = Cheque::find($account);
			break;
			case 'employee':
			$account = Employee::find($account);
			break;
			case 'loan':
			$account = Loan::find($account);
			break;
			case 'expense':
			$account = Expense::find($account);
			break;	
			default:
			return null;
		}

		switch($type) {
			case 'income':
			if($accountType == 'account-book') {
				$from_account_id = $account;
			} else {
				$from_account_id = $account->getCurrentAccountBook()->id;
			}
			if ($bankAccount !== null) {
				$to_account_id = $bankAccount->getCurrentAccountBook()->id;
			}else{
				$to_account_id =0;
			}
			
			break;
			case 'expense':
			if($accountType == 'account-book') {
				$to_account_id = $account;
			} else {
				$to_account_id = $account->getCurrentAccountBook()->id;
			}
			$from_account_id =$bankAccount->id;
			break;
		}

		$transaction = new Transaction;
		$transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
		if($closing !== null) {
			$transaction->closing_id = $closing;
		}
		if($attachment !== null) {
			$transaction->attachment()->associate($attachment);
		}
		$transaction->save();

	

		return $transaction;
	}

	public static function getIncomesOn($date) {
		return self::queryIncomesOn($date)->get();
	}

	public static function sumIncomesOn($date) {
		$query = self::queryIncomesOn($date);
		$sql = $query->toSql();
		return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
	}

	public static function sumIncomesWithPreviousBalanceOn($date) {
		$previousCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
		$incomeSum = self::sumIncomesOn($date);

		return $previousCashBalance + $incomeSum;
	}

	public static function getExpensesOn($date) {
		return self::queryExpensesOn($date)->get();
	}

	public static function sumExpensesOn($date) {
		$query = self::queryExpensesOn($date);
		$sql = $query->toSql();
		return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
	}

	// Private static functions
	// private static function queryIncomesOn($date) {
	// 	$current = new \Carbon\CarbonImmutable($date);
	// 	$next = $current->addDay();

	// 	return self::where('created_at', '>=', $current)->where('created_at', '<', $next)
	// 		->whereHas('toAccount', function($query) {
	// 			$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 		})->whereDoesntHave('fromAccount', function($query) {
	// 			$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 		})->groupBy('from_account_id')->selectRaw('0 id, 0 bank_withdrawal, from_account_id, null to_account_id, sum(amount) amount')
	// 		->union(self::where('created_at', '>=', $current)->where('created_at', '<', $next)
	// 			->where(function($query) {
	// 				$query->where(function($query) {
	// 					$query->whereHas('toAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', 'cash');
	// 						});
	// 					})->whereHas('fromAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', '<>', 'cash');
	// 						});
	// 					});
	// 				})->orWhere(function($query) {
	// 					$query->whereHas('fromAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', '<>', 'cash');
	// 						});
	// 					})->whereDoesntHave('toAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 					});
	// 				});
	// 			})->groupBy('from_account_id')
	// 			->selectRaw('0 id, 1 bank_withdrawal, from_account_id, null to_account_id, sum(amount) amount')
	// 		);
	// }




private static function queryIncomesOn($date) {
    $current = \Carbon\CarbonImmutable::parse($date);
    $next = $current->addDay();

    return self::where('created_at', '>=', $current)
        ->where('created_at', '<', $next)
        ->where(function($query) {
            // Subquery to check if the related model is an instance of BankAccount
            $query->whereExists(function ($subquery) {
                $subquery->select(new Expression(1))
                    ->from('bank_accounts')
                    ->whereColumn('bank_accounts.id', '=', 'transactions.from_account_id'); // Adjust column name
            });
        })
        ->groupBy('from_account_id')
        ->selectRaw('0 id, 0 bank_withdrawal, from_account_id, null to_account_id, sum(amount) amount')
        ->union(self::where('created_at', '>=', $current)
            ->where('created_at', '<', $next)
            ->where(function($query) {
                $query->whereNotExists(function ($subquery) {
                    $subquery->select(new Expression(1))
                        ->from('bank_accounts')
                        ->whereColumn('bank_accounts.id', '=', 'transactions.from_account_id'); 
                });
            })
            ->groupBy('from_account_id')
            ->selectRaw('0 id, 1 bank_withdrawal, from_account_id, null to_account_id, sum(amount) amount')
        );
}

	


	

	// private static function queryExpensesOn($date) {
	// 	$current = new \Carbon\CarbonImmutable($date);
	// 	$next = $current->addDay();

	// 	return self::where('created_at', '>=', $current)->where('created_at', '<', $next)
	// 		->whereHas('fromAccount', function($query) {
	// 			$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 		})->whereDoesntHave('toAccount', function($query) {
	// 			$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 		})->groupBy('to_account_id')->selectRaw('0 id, 0 bank_deposit, null from_account_id, to_account_id, sum(amount) amount')
	// 		->union(self::where('created_at', '>=', $current)->where('created_at', '<', $next)
	// 			->where(function($query) {
	// 				$query->where(function($query) {
	// 					$query->whereHas('fromAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', 'cash');
	// 						});
	// 					})->whereHas('toAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', '<>', 'cash');
	// 						});
	// 					});
	// 				})->orWhere(function($query) {
	// 					$query->whereHas('toAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount', function($query) {
	// 							$query->where('account_no', '<>', 'cash');
	// 						});
	// 					})->whereDoesntHave('fromAccount', function($query) {
	// 						$query->whereHasMorph('account', 'App\Models\BankAccount');
	// 					});
	// 				});
	// 			})->groupBy('to_account_id')
	// 			->selectRaw('0 id, 1 bank_deposit, null from_account_id, to_account_id, sum(amount) amount')
	// 		);
	// }

	// Relationships

	private static function queryExpensesOn($date) {
		$current = \Carbon\CarbonImmutable::parse($date);
		$next = $current->addDay();
	
		return self::where('created_at', '>=', $current)
			->where('created_at', '<', $next)
			->where(function($query) {
				// Subquery to check if the related model is an instance of BankAccount
				$query->whereExists(function ($subquery) {
					$subquery->select(new Expression(1))
						->from('bank_accounts')
						->whereColumn('bank_accounts.id', '=', 'transactions.from_account_id'); // Adjust column name
				});
			})
			->groupBy('to_account_id')
			->selectRaw('0 id, 0 bank_deposit, null from_account_id, to_account_id, sum(amount) amount')
			->union(self::where('created_at', '>=', $current)
				->where('created_at', '<', $next)
				->where(function($query) {
					// Subquery to check if the related model is not an instance of BankAccount
					$query->whereNotExists(function ($subquery) {
						$subquery->select(new Expression(1))
							->from('bank_accounts')
							->whereColumn('bank_accounts.id', '=', 'transactions.from_account_id'); // Adjust column name
					});
				})
				->groupBy('to_account_id')
				->selectRaw('0 id, 1 bank_deposit, null from_account_id, to_account_id, sum(amount) amount')
			);
	}
	public function fromAccount() {
		return $this->belongsTo(AccountBook::class, 'from_account_id');
	}
	
	public function toAccount() {
		return $this->belongsTo(AccountBook::class, 'to_account_id');
	}

	public function closingAccount() {
		return $this->belongsTo(AccountBook::class, 'closing_id');
	}

	public function attachment() {
		return $this->morphTo();
	}

	// Attributes
	public function getTransactionTypeAttribute() {
		if(isset($this->bank_withdrawal)) {
			if($this->bank_withdrawal == 1) {
				return 'ব্যাংক তোলা - ' . $this->fromAccount->account->name;
			} else {
				return ($this->fromAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->fromAccount->account->name;
			}
		}
		if(isset($this->bank_deposit)) {
			if($this->bank_deposit == 1) {
				return 'ব্যাংক জমা - ' . $this->toAccount->account->name;
			} else {
				return ($this->toAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->toAccount->account->name;
			}
		}
		if($this->from_account_id === null) {
			if($this->toAccount->account_type == 'loan') {
				return 'হাওলাত - ' . $this->toAccount->account->name;
			} else {
				return $this->toAccount->account->name;
			}
		} else if($this->to_account_id === null) {
			if($this->fromAccount->account_type == 'loan') {
				return 'হাওলাত - ' . $this->fromAccount->account->name;
			} else {
				return $this->fromAccount->account->name;
			}
		} else if($this->fromAccount->account->account_no == 'cash' && $this->toAccount->account_type == 'bank-account') {
			return 'ব্যাংক জমা';
		} else if($this->toAccount->account->account_no == 'cash' && $this->fromAccount->account_type == 'bank-account') {
			return 'ব্যাংক তোলা';
		} else if($this->fromAccount->account_type == 'bank-account') {
			return $this->toAccount->account->name;
		} else {
			return $this->fromAccount->account->name;
		}
	}
	
	protected $with = ['fromAccount.account', 'toAccount.account'];
	protected $appends = ['transaction_type'];
    protected $fillable = ['from_account_id', 'to_account_id', 'amount', 'description'];
}
