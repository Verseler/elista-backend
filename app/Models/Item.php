<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price',
        'quantity',
        'borrower_id',
        'transaction_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public static function computeTotalPrice(array $item): float
    {
        return $item['price'] * $item['quantity'];
    }
}
