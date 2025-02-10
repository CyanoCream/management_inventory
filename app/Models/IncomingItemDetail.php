<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingItemDetail extends Model
{
    protected $fillable = [
        'incoming_item_id',
        'name',
        'price',
        'quantity',
        'unit',
        'expired_date'
    ];

    protected $casts = [
        'expired_date' => 'date',
    ];

    public function incomingItem()
    {
        return $this->belongsTo(IncomingItem::class);
    }
}
