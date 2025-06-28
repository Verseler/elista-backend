<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'location'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function borrowers()
    {
        return $this->hasMany(User::class)->role('borrower');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function lentItems()
    {
        return $this->hasManyThrough(
            Item::class,
            Transaction::class
        );
    }
}
