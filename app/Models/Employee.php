<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    public function getCurrentAccountBook()
    {
        return $this->accountBooks()->latest()->first();
    }
    public function accountBooks()
    {
        return $this->hasMany(AccountBook::class, 'account_id', 'id')->where('account_type', 'employee');
    }

    public function entries()
    {
        return $this->hasMany(EmployeeAccountEntry::class, 'account_id', 'id');
    }


    protected $fillable = ['name', 'address', 'mobile_no', 'limit'];
}
