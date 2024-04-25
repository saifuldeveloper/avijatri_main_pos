<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['name'];



    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class ,'account_id','id')->where('account_type','expense');
    }
    public function entries(){
        return $this->hasMany(ExpenseAccountEntry::class,'account_id','id');

    }
}
