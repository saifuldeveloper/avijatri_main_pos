<?php

namespace App\Models\View;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailStoreAccountEntry extends Model
{
    use HasFactory;


    public function invoices(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
}
