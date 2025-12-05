<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo'
    ];

     protected $casts = [
        'name' => 'encrypted',
        'photo' => 'encrypted',
    ];

     

     public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
