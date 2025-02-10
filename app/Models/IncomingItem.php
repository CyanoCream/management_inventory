<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingItem extends Model
{
    protected $fillable = [
        'user_id',
        'sub_category_id',
        'source',
        'letter_number',
        'attachment',
        'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function details()
    {
        return $this->hasMany(IncomingItemDetail::class);
    }
}
