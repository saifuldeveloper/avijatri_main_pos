<?php

namespace App\Models;

use App\Models\View\BankAccountEntry;
use App\Models\View\FactoryAccountEntry;
use App\Models\View\RetailStoreAccountEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class AccountBook extends Model
{
    use HasFactory;

    // public function account() {
    // 	return $this->morphTo();
    // }
    public function account()
    {
        return $this->belongsTo(Factory::class);
    }
    public function retailAccount()
    {
        return $this->belongsTo(RetailStore::class, 'account_id', 'id');
    }
    public function giftSupplierAccount()
    {
        return $this->belongsTo(GiftSupplier::class, 'account_id', 'id');
    }

    public function BankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'account_id', 'id');
    }
    public function retailStoreExpenses() {
    	if($this->account_type !== 'retail-store') {
    		return null;
    	}
    	return $this->hasMany(RetailStoreExpense::class);
    }

    public function purchases() {
        if($this->account_type !== 'factory') {
            return null;
        }
        return $this->hasMany(Purchase::class);
    }

    public function invoices() {
        if($this->account_type !== 'retail-store') {
            return null;
        }
        return $this->hasMany(Invoice::class);
    }

    public function returnToFactoryEntries() { 
        if($this->account_type !== 'factory') {
            return null;
        }
        return $this->hasMany(ReturnToFactoryEntry::class);
    }

    public function returnFromRetailEntries() {
        if($this->account_type !== 'retail-store') {
            return null;
        }
        return $this->hasMany(ReturnFromRetailEntry::class);
    }

    public function giftPurchases() {
        if($this->account_type !== 'gift-supplier') {
            return null;
        }
        return $this->hasMany(GiftPurchase::class);
    }

    public function transactionsFrom() {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo() {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }

    public function closingTransactions() {
        return $this->hasMany(Transaction::class, 'closing_id');
    }

    public function cheques() {
        return $this->hasMany(Cheque::class);
    }

    public function closingCheques() {
        return $this->hasMany(Cheque::class, 'closing_id');
    }



    public function accountEntries() {
        if($this->account_type == 'factory') {
            return $this->hasMany(FactoryAccountEntry::class);
        }
        if($this->account_type == 'retail-store') {
            return $this->hasMany(RetailStoreAccountEntry::class);
        }
        if($this->account_type == 'gift-supplier') {
            return $this->hasMany(GiftSupplierAccountEntry::class);
        }
        if($this->account_type == 'cheque') {
            return $this->hasMany(ChequeAccountEntries::class);
        }
        if($this->account_type == 'bank-account') {
            return $this->hasMany(BankAccountEntry::class);
        }
        if($this->account_type == 'employee') {
            return $this->hasMany(EmployeeAccountEntry::class);
        }
        return null;
    }

     public function factoryentries(){
        return $this->hasMany(FactoryAccountEntry::class, 'account_book_id', 'id')->where('status',1)->orderBy('created_at', 'desc');
     }

     public function retailEntries(){
        return $this->hasMany(RetailStoreAccountEntry::class, 'account_book_id', 'id')->orderBy('created_at', 'desc');
     }

    // Attributes
    public function getDescriptionAttribute() {
        $description = dateFormat($this->created_at) . ' থেকে ';
        if($this->open) {
            $description .= 'চলমান';
        } else {
            $description .= dateFormat($this->closing_date, 'd/m/Y', 'Y-m-d');
        }
        return $description;
    }

    public function getEntriesAttribute() {
        return $this->entriesQuery()->paginate(20);
    }

    public function getBalanceAttribute() {
        return $this->entriesQuery()->first()->balance ?? $this->opening_balance;
    }

    public function getBalanceBeforeClosingAttribute() {
        //return $this->total_sale_minus_commission - $this->total_return_minus_commission - $this->total_payment + $this->total_transport - $this->total_expense - $this->total_discount + $this->opening_balance;
        return $this->balance + $this->total_closing_payment;
    }

    public function getDescriptionBalanceAttribute() {
        if($this->open) {
            return $this->balance;
        }
        return $this->closing_balance;
    }

    public function getOpeningBalanceAttribute() {
        if ($this->account_type == 'retail-store') {
            if ($this->account !== null) {
                $previous_book = $this->account->accountBooks()->where('created_at', '<', $this->created_at)->latest()->first();
                if ($previous_book === null) {
                    return $this->previous_balance;
                }
                if ($previous_book->balance_carry_forward) {
                    return $this->previous_balance + $previous_book->closing_balance;
                } else {
                    return $this->previous_balance;
                }
            } else {
                return $this->previous_balance;
            }
        }
        return $this->previous_balance;
    }

    public function getTotalClosingTransactionAmountAttribute() {
        if($this->open) {
            return 0;
        }
        return $this->closingTransactions()->sum('amount');
    }

    public function getTotalClosingChequeAmountAttribute() {
        if($this->open || $this->account_type != 'factory') {
            return 0;
        }
        return $this->closingCheques()->sum('amount');
    }

    public function getTotalClosingPaymentAttribute() {
        return $this->total_closing_transaction_amount + $this->total_closing_cheque_amount;
    }

    public function getTotalProductsWorthAttribute() {
        if($this->account_type == 'factory') {
            return $this->entriesQuery()->where('entry_type', 0)->sum('total_amount');
        }
        return 0;
    }

    public function getPaymentAttribute() {
        if($this->account_type == 'factory' || $this->account_type == 'gift-supplier') {
            return $this->entriesQuery()->where('entry_type', 1)->orWhere('entry_type', 2)->sum('total_amount');
        }
        return 0;
    }

    public function getPaymentPercentageAttribute() {
        if($this->account_type == 'factory' || $this->account_type == 'gift-supplier') {
            $purchase = $this->entriesQuery()->where('entry_type', 0)->orWhere('entry_type', 3)->sum('total_amount');
            $payment = $this->entriesQuery()->where('entry_type', 1)->orWhere('entry_type', 2)->sum('total_amount');
            if($purchase == 0) {
                return 0;
            }
            return $payment / $purchase * 100;
        }
        return 0;
    }

    public function getTotalPurchasePriceAttribute() {
        if($this->account_type == 'factory') {
            return $this->entriesQuery()->where('entry_type', 0)->sum('total_amount');
        }
        return 0;
    }

    public function getTotalSaleAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->sum('total_retail_price');
        }
    }

    public function getTotalSaleMinusCommissionAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->sum(DB::raw('total_retail_price - total_commission'));
        }
    }

    public function getTotalReturnAmountAttribute() {
        if($this->account_type == 'factory') {
            return $this->entriesQuery()->where('entry_type', 1)->sum('total_amount');
        }
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->orWhere('entry_type', 1)->sum('return_amount_without_commission');
        }
        return 0;
    }

    public function getTotalReturnMinusCommissionAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->orWhere('entry_type', 1)->sum('return_amount');
        }
        return 0;
    }

    public function getTotalPaymentAttribute() {
        if($this->account_type == 'factory') {
            return $this->entriesQuery()->where('entry_type', 2)->whereNull('closing_id')->sum('total_amount');
        }
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->orWhere(function($query) {
                $query->where('entry_type', 3)->whereNull('closing_id');
            })->sum('paid_amount');
        }
        return 0;
    }

    public function getTotalTransportAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->sum('transport');
        }
        return 0;
    }

    public function getTotalExpenseAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->orWhere('entry_type', 2)->sum('expense_amount');
        }
        return 0;
    }

    public function getTotalDiscountAttribute() {
        if($this->account_type == 'retail-store') {
            return $this->entriesQuery()->where('entry_type', 0)->sum('discount');
        }
        return 0;
    }

    // Methods
    public function getBalanceBefore($date) {
        $total_income = $this->transactionsTo()->where('created_at', '<', $date)->sum('amount');
        $total_expense = $this->transactionsFrom()->where('created_at', '<', $date)->sum('amount');
        return $this->opening_balance + $total_income - $total_expense;
    }

    public function appendClosingAttributes() {
        $this->append('total_return_amount');
        $this->append('total_payment');
        $this->append('total_closing_transaction_amount');
        $this->append('total_closing_cheque_amount');
        $this->append('total_closing_payment');

        if($this->account_type == 'factory') {
            $this->append('total_purchase_price');
        }

        if($this->account_type == 'retail-store') {
            $this->append('total_sale');
            $this->append('total_sale_minus_commission');
            $this->append('total_return_minus_commission');
            $this->append('total_transport');
            $this->append('total_expense');
            $this->append('total_discount');
            $this->append('balance_before_closing');
        }
    }

    public function entriesQuery() {
        if($this->account_type == 'retail-store') {
            $query = $this->accountEntries()
                ->crossJoin(DB::raw('(SELECT @cumulative := ' . $this->opening_balance . ') dummy_table'))
                ->orderBy('created_at', 'asc')->orderBy('entry_id', 'asc')
                ->selectRaw('*, @cumulative := @cumulative + if(amount is not null, amount, 0) - if(paid_amount is not null, paid_amount, 0) as balance');
        } else if($this->account_type == 'cheque') {
            $cheque = $this->account;
            $query = $this->accountEntries()
                ->crossJoin(DB::raw('(SELECT @cumulative := ' . $cheque->amount . ') dummy_table'))
                ->orderBy('created_at', 'asc')->orderBy('entry_id', 'asc')
                ->selectRaw('*, @cumulative := @cumulative - total_amount as balance');
        } else {
            $query = $this->accountEntries()
                ->crossJoin(DB::raw('(SELECT @cumulative := ' . $this->opening_balance . ') dummy_table'))
                ->orderBy('created_at', 'asc')->orderBy('entry_id', 'asc')
                ->selectRaw('*, @cumulative := @cumulative + if(entry_type = 0 or entry_type = 3, total_amount, -total_amount) as balance');
        }
        $sql = $query->toSql();
        if($this->account_type == 'cheque') {
            return DB::table(DB::raw("({$sql}) query_table"))
                ->orderBy('created_at', 'asc')->orderBy('entry_id', 'asc')
                ->mergeBindings($query->getQuery()->getQuery());
        } else {
            return DB::table(DB::raw("({$sql}) query_table"))
                ->orderBy('created_at', 'desc')->orderBy('entry_id', 'desc')
                ->mergeBindings($query->getQuery()->getQuery());
        }
    }

    protected $appends = ['description', 'balance', 'description_balance', 'opening_balance'];
    protected $fillable = ['commission', 'staff', 'discount', /*'deadline'*/'balance_carry_forward'];
  
   
    

}
