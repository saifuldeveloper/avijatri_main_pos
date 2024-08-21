<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\View\InvoiceItem;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{

    use HasFactory, SoftDeletes;
      // Relationships
      public function accountBook() {
    	return $this->belongsTo(AccountBook::class);

    }

    public function invoiceEntries()
    {
        return $this->hasMany(InvoiceEntry::class);
    }

    public function sales()
    {
        return $this->shoeTransactions()->where('type', 'sale')->orderBy('id', 'asc');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class)
            ->orderBy('parent_id', 'asc')->orderBy('retail_price', 'desc');
    }

    public function returns()
    {
        return $this->hasMany(ReturnFromRetailEntry::class);
    }

    public function retailStoreExpenses()
    {
        return $this->hasMany(RetailStoreExpense::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'attachment_id' ,'id')->where('attachment_type','App\Models\Invoice');
    }

    public function giftTransactions()
    {
        return $this->morphMany(GiftTransaction::class, 'attachment');
    }

    // Attributes
    public function getTotalPairsAttribute()
    {
        return $this->invoiceEntries()->sum('count');
    }

    public function getTotalAmountAttribute()
    {
        /*return $this->shoeTransactions()
            ->join('shoes', 'shoes.id', '=', 'shoe_transactions.shoe_id')
            ->sum(\DB::raw('shoes.retail_price * shoe_transactions.count'));*/
        return $this->invoiceEntries()
            ->join('shoes', 'shoes.id', '=', 'invoice_entries.shoe_id')
            ->sum(DB::raw('shoes.retail_price * invoice_entries.count'));
    }

    public function getTotalCommissionAttribute()
    {
        return $this->total_amount * $this->commission / 100;
    }

    public function getCommissionDeductedAttribute()
    {
        return $this->total_amount - $this->total_commission;
    }

    public function getReturnCountAttribute()
    {
        return $this->returns()->sum('count');
    }

    public function getReturnAmountAttribute()
    {
        return $this->returns()->join('shoes', 'shoes.id', '=', 'return_from_retail_entries.shoe_id')
            ->sum(DB::raw('return_from_retail_entries.count * shoes.retail_price * (100 - return_from_retail_entries.commission) / 100'));
    }

    public function getReturnDeductedAttribute()
    {
        return $this->commission_deducted - $this->return_amount;
    }

    public function getTransportAddedAttribute()
    {
        return $this->return_deducted + $this->transport;
    }

    public function getOtherCostsAttribute()
    {
        return $this->retailStoreExpenses()->sum('amount');
    }

    public function getOtherCostsDeductedAttribute()
    {
        return $this->transport_added - $this->other_costs;
    }

    public function getTotalReceivableAttribute()
    {
        return $this->other_costs_deducted - $this->discount;
    }

    public function getTotalPaymentAttribute()
    {
        return $this->transactions()->sum('amount');
    }

    public function getAccountBookPreviousBalanceAttribute()
    {



        return $this->account_book_balance - $this->total_receivable + $this->total_payment;
    }

    public function getAccountBookBalanceAttribute()
    {
        $accountBook = $this->accountBook()->first();
        $accountEntry = $accountBook->entriesQuery()->where('invoice_id', $this->id)->first();
        return $accountEntry->balance;
    }

    protected $appends = [
        'total_pairs', 'total_amount', 'total_commission', 'commission_deducted',
        'return_count', 'return_amount', 'return_deducted',
        'transport_added', 'other_costs', 'other_costs_deducted',
        'total_receivable', 'total_payment',
        'account_book_previous_balance', 'account_book_balance'
    ];
    protected $fillable = ['commission', 'transport', 'discount'];

    public static function getNextId()
    {
        $dbname = config('database.connections.mysql.database');
        $table = (new self)->getTable();
        $query = DB::select("SELECT AUTO_INCREMENT
                             FROM information_schema.TABLES
                             WHERE TABLE_SCHEMA = '{$dbname}'
                             AND TABLE_NAME = '{$table}'");

        return $query[0]->AUTO_INCREMENT;
    }
}
