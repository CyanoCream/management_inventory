<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['category_id', 'name', 'price_limit'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

