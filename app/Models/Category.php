<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shoe;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    public static function parentCategoriesQuery()
    {
        return self::where('parent_id', 0)->orderBy('id', 'asc');
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function shoes()
    {
        return $this->hasMany(Shoe::class);
    }

    // Attributes
    // public function getFullNameAttribute() {
    // 	if($this->parent_id === 0) {
    // 		return $this->name;
    // 	}
    // 	return $this->parent()->first()->name . '-' . $this->name;
    // }
    public function getFullNameAttribute()
    {
        if ($this->parent_id === 0) {
            return $this->attributes['name'];
        }
        $parentCategory = Category::find($this->parent_id);
        if ($parentCategory) {
            return $parentCategory->name . '-' . $this->attributes['name'];
        } else {
            return $this->attributes['name'];
        }
    }

    protected $fillable = ['name', 'parent_id'];
    protected $with = ['parent'];
    protected $appends = ['full_name'];
}
