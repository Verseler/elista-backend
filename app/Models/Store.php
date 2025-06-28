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
}
