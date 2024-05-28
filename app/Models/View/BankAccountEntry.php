<?php

namespace App\Models\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountBook;

class BankAccountEntry extends Model
{
    use HasFactory;
    // Relationships
    public function accountBook()
    {
        return $this->belongsTo(AccountBook::class);
    }

    public function account()
    {
        return $this->morphTo();
    }
}
