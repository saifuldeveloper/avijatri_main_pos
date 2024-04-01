<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnToFactory extends Model
{
    use HasFactory;

    public function returnentries(){
        return $this->hasMany(ReturnToFactoryEntry::class,'return_id','id')->where('status', 'accepted');
    }

}
