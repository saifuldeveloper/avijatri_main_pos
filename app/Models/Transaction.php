<?php

namespace App\Models;

use App\Models\View\BankAccountEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\View\FactoryAccountEntry;
use Illuminate\Database\Query\Expression;


class Transaction extends Model
{
    use HasFactory;


    public static function createBankToCashTransaction($type, $bankAccount, $amount, $description = '')
    {
        $cashAccount = BankAccount::getCashAccount();
        $bankAccount = BankAccount::find($bankAccount);

        switch ($type) {
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
        // BankAccount entry
        $entry                   = new BankAccountEntry;
        $entry->entry_id         = $bankAccount->id;
        $entry->entry_type       = ($type == 'deposit') ? 0 : 1;
        $entry->account_book_id  = $bankAccount->getCurrentAccountBook()->id;
        $entry->account_name     = $bankAccount->bank;
        $entry->account_id       = $bankAccount->id;
        $entry->account_type     = 'bank-account';
        $entry->type             = $type;
        $entry->description      = $description;
        $entry->total_amount     = $amount;
        $entry->save();


        $transaction = new Transaction;
        $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
        $transaction->transaction_type = $type;
        $transaction->payment_type = 'bank-account';
        $transaction->save();
        return $transaction;
        // return self::create(compact('from_account_id', 'to_account_id', 'amount', 'description'));
    }

    public static function createTransaction($accountType, $account, $type, $bankAccount, $amount, $description = '', $attachment = null, $closing = null)
    {

        $bankAccount = BankAccount::where('id', $bankAccount)->first();
        switch ($accountType) {
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
            case 'loan-receipt':
            case 'loan-payment':
                $account = Loan::find($account);
                break;
            case 'expense':
                $account = Expense::find($account);
                break;
            default:
                return null;
        }
        switch ($type) {
            case 'income':
                if ($accountType == 'account-book') {
                    $from_account_id = $account;
                } else {
                    $from_account_id = $account->getCurrentAccountBook()->id;
                }
                $to_account_id       = $bankAccount->getCurrentAccountBook()->id;
                break;
            case 'expense':
                if ($accountType == 'account-book') {
                    $to_account_id = $account;
                } else {
                    $to_account_id = $account->getCurrentAccountBook()->id;
                }
                $from_account_id = $bankAccount->getCurrentAccountBook()->id;
                break;
        }



        // for bank transaction

        if ($bankAccount->account_no !== 'cash') {
            $transaction               = new Transaction;
            $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
            $transaction->transaction_type = ($type == 'deposit') ? 'deposit' : (($type == 'expense') ? 'withdraw' : (($type == 'income') ? 'deposit' :
                'withdraw'));

            $transaction->payment_type     = $accountType;

            $transaction->save();
        }

        $transaction = new Transaction;
        $transaction->fill(compact('from_account_id', 'to_account_id', 'amount', 'description'));
        if ($closing !== null) {
            $transaction->closing_id = $closing;
        }
        if ($attachment !== null) {
            $transaction->attachment()->associate($attachment);
        }
        if ($accountType == 'loan-receipt' ||  $accountType == 'loan-payment') {
            $transaction->transaction_type = $accountType == 'loan-receipt' ? 'income' : 'expense';
        }
        $transaction->transaction_type = $type;
        $transaction->payment_type     = $accountType;

        $transaction->save();


        $entry                   = new BankAccountEntry;
        $entry->entry_id         = $bankAccount->id;
        $entry->entry_type       = ($type == 'deposit') ? 0 : 1;
        $entry->account_book_id  = $bankAccount->getCurrentAccountBook()->id;
        $entry->account_name     = $bankAccount->bank;
        $entry->account_id       = $bankAccount->id;
        $entry->account_type     = $accountType;
        $entry->description      = $description;
        $entry->type             = $type;
        $entry->total_amount     = $amount;
        $entry->save();

        return $transaction;
    }

    public static function getIncomesOn($date)
    {
        return self::queryIncomesOn($date)->get();
    }
    public static function sumIncomesOn($date)
    {
        $query = self::queryIncomesOn($date);
        $sql = $query->toSql();
        return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
    }

    public static function sumIncomesWithPreviousBalanceOn($date)
    {
        $previousCashBalance = BankAccount::getCashAccount()->getCurrentAccountBook()->getBalanceBefore($date);
        $incomeSum = self::sumIncomesOn($date);

        return $previousCashBalance + $incomeSum;
    }

    public static function getExpensesOn($date)
    {
        return self::queryExpensesOn($date)->get();
    }

    public static function sumExpensesOn($date)
    {
        $query = self::queryExpensesOn($date);
        $sql = $query->toSql();
        return DB::table(DB::raw("({$sql}) as query_table"))->mergeBindings($query->getQuery())->sum('amount');
    }



    private static function queryIncomesOn($date)
    {
        $current = \Carbon\CarbonImmutable::parse($date);
        $next = $current->copy()->addDay();
        return self::where('created_at', '>=', $current)
            ->where('created_at', '<', $next)
            ->groupBy('from_account_id',)
            ->selectRaw('0 id, 0 bank_withdrawal,  from_account_id, sum(amount) amount')
            ->whereIn('transaction_type', ['withdraw', 'income']);
    }



    private static function queryExpensesOn($date)
    {
        $current = \Carbon\CarbonImmutable::parse($date);
        $next = $current->addDay();
        return self::where('created_at', '>=', $current)
            ->where('created_at', '<', $next)
            ->groupBy('to_account_id',)
            ->selectRaw('0 id, 0 bank_deposit,  to_account_id, sum(amount) amount')
            ->whereIn('transaction_type', ['deposit', 'expense']);
    }



    public function fromAccount()
    {
        return $this->belongsTo(AccountBook::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(AccountBook::class, 'to_account_id');
    }

    public function closingAccount()
    {
        return $this->belongsTo(AccountBook::class, 'closing_id');
    }

    public function attachment()
    {
        return $this->morphTo();
    }

    // Attributes
    public function getTransactionTypeAttribute()
    {
        if (isset($this->bank_withdrawal)) {
            if ($this->bank_withdrawal == 1) {
                return 'ব্যাংক তোলা - ' . $this->fromAccount->BankAccount->name;
            } else {
                return ($this->fromAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->fromAccount->BankAccount->name;
            }
        }
        if (isset($this->bank_deposit)) {
            if ($this->bank_deposit == 1) {
                return 'ব্যাংক জমা - ' . $this->toAccount->BankAccount->name;
            } else {
                return ($this->toAccount->account_type == 'loan' ? 'হাওলাত - ' : '') . $this->toAccount->BankAccount->name;
            }
        }
        if ($this->from_account_id === null) {
            if ($this->toAccount->account_type == 'loan') {
                return 'হাওলাত - ' . $this->toAccount->BankAccount->name;
            } else {
                return $this->toAccount->BankAccount->name;
            }
        } else if ($this->to_account_id === null) {
            if ($this->fromAccount->account_type == 'loan') {
                return 'হাওলাত - ' . $this->fromAccount->BankAccount->name;
            } else {
                return $this->fromAccount->BankAccount->name;
            }
        } else if ($this->fromAccount->BankAccount->account_no == 'cash' && $this->toAccount->account_type == 'bank-account') {
            return 'ব্যাংক জমা';
        } else if ($this->toAccount->BankAccount->account_no == 'cash' && $this->fromAccount->account_type == 'bank-account') {
            return 'ব্যাংক তোলা';
        } else if ($this->fromAccount->account_type == 'bank-account') {
            return $this->toAccount->BankAccount->name;
        } else {
            return $this->fromAccount->BankAccount->name;
        }
    }

    protected $with = ['fromAccount.BankAccount', 'toAccount.BankAccount'];
    protected $appends = ['transaction_type'];
    protected $fillable = ['from_account_id', 'to_account_id', 'amount', 'description'];
}
