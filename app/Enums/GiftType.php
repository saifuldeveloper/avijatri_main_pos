<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gift;
class GiftType extends Model
{
	public function gifts() {
		return $this->hasMany(Gift::class);
	}
	
	public $incrementing = false;
	
    protected $fillable = ['id', 'name'];
}
