<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Enums\GiftType;
use App\Models\GiftType;
use App\Models\GiftTransaction;
use App\Models\Shoe;


class Gift extends Model
{
        use HasFactory;
    public function giftType()
    {
        return $this->belongsTo(GiftType::class);
    }
    public function giftTransactions()
    {
        return $this->hasMany(GiftTransaction::class);
    }

    public function shoes()
    {
        if ($this->gift_type_id === 'box') {
            return $this->hasMany(Shoe::class, 'box_id');
        } elseif ($this->gift_type_id === 'bag') {
            return $this->hasMany(Shoe::class, 'bag_id');
        } else {
            return null;
        }
    }

    protected $fillable = ['name', 'gift_type_id'];
}
