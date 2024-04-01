<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteEntry extends Model
{
    use HasFactory;
    public function shoe() {
		return $this->belongsTo(Shoe::class);
	}



	protected $with = ['shoe'];
    protected $fillable = ['shoe_id', 'count', 'description'];
}
